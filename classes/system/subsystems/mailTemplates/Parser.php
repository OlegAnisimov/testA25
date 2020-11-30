<?php

	namespace UmiCms\System\MailTemplates;

	use iUmiObject;
	use MailTemplate;
	use UmiCms\Service;

	/** Парсер шаблона письма */
	class Parser implements iParser {

		/** @const string Символ, обрамляющий идентификаторы (placeholders) полей в шаблоне */
		const FIELD_WRAPPER_SYMBOL = '%';

		/** @var MailTemplate Шаблон */
		private $template;

		/** @inheritDoc */
		public function __construct(MailTemplate $template) {
			$this->template = $template;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function parse(array $params = [], array $objectList = []) {
			$params = $this->stripSpecialCharacters($params);
			$rootVariables = array_filter($params, function ($param) {
				return !is_array($param);
			});
			$recursiveVariables = array_filter($params, 'is_array');

			$content = $this->parseRootVariables($rootVariables);
			$content = $this->parseObjectFields($content, $objectList);
			$content = $this->parseRecursiveVariables($content, $recursiveVariables, $objectList);
			$content = $this->parseTplMacros($content);

			return $content;
		}

		/**
		 * Рекурсивно вырезает все специальные символы из идентификаторов полей.
		 * Пример:
		 *   %price% => price
		 *   +items => items
		 *
		 * @param mixed $params массив идентификаторов полей и их значений или отдельное значение
		 * @return array
		 */
		private function stripSpecialCharacters($params) {
			if (!is_array($params)) {
				return $params;
			}

			foreach (array_keys($params) as $field) {
				$value = $params[$field];
				unset($params[$field]);
				$field = preg_replace('/^\W*(\w+)\W*$/', '$1', $field);
				$params[$field] = $this->stripSpecialCharacters($value);
			}

			return $params;
		}

		/**
		 * Возвращает обработанное содержимое шаблона, такое, что в нем (содержимом)
		 * заменены вставки идентификаторов полей на конкретные значения.
		 * @param array $params массив идентификаторов полей и их значений
		 * @return string
		 */
		private function parseRootVariables(array $params) {
			$fields = array_keys($params);
			$values = array_values($params);
			$wrappedFields = array_map(function ($value) {
				return self::FIELD_WRAPPER_SYMBOL . $value . self::FIELD_WRAPPER_SYMBOL;
			}, $fields);

			return (string) str_replace($wrappedFields, $values, $this->template->getContent());
		}

		/**
		 * Возвращает результат обработки всех вложенных шаблонов.
		 *
		 * Пример вызова вложенного шаблона:
		 *  <p>Товары:</p>
		 *  %parse.emarket-status-notification-item.items%
		 *
		 * Где `parse` - ключевое слово для вызова
		 *     `emarket-status-notification-item` - название вложенного шаблона
		 *     `items` - название поля с массивом переменных для шаблона
		 *
		 * @param string $content содержимое основного шаблона
		 * @param array $params массив идентификаторов полей и их значений
		 * @param array $objectList Объекты, из которых могут подставляться значения в шаблон
		 * @return mixed
		 * @throws \Exception
		 */
		private function parseRecursiveVariables(string $content, array $params, array $objectList) : string {

			while (preg_match('/%parse\.([^%]+)%/', $content, $matches, PREG_OFFSET_CAPTURE)) {
				$start = $matches[0][1];
				$length = mb_strlen($matches[0][0]);
				list($templateName, $paramName) = explode('.', $matches[1][0]);

				$subTemplate = $this->template
					->getNotification()
					->getTemplateByName($templateName);

				$replacement = $this->parseTemplateList($subTemplate, $params[$paramName], $objectList);
				$content = (string) substr_replace($content, $replacement, $start, $length);
			}

			return $content;
		}

		/**
		 * Обрабатывает tpl макросы в шаблоне уведомления
		 * @param string $content шаблон уведомления
		 * @return string
		 * @throws \coreException
		 */
		private function parseTplMacros(string $content) : string {
			/** @var \umiTemplaterTPL $templateEngine */
			$templateEngine = \umiTemplater::create('TPL');
			return (string) $templateEngine->parse([], $content);
		}

		/**
		 * Возвращает обработанное содержимое шаблона для каждой комбинации переменных и их значений.
		 * Используется для массового парсинга вложенного шаблона.
		 *
		 * @param MailTemplate|null $template шаблон
		 * @param array $paramsCollection массив, каждый элемент которого -
		 *  массив переменных и их значений для обработки шаблона
		 * @param array $objectList Объекты, из которых могут подставляться значения в шаблон
		 *
		 * @return string конкатенация обработки шаблона для всех переменных
		 * @throws \Exception
		 */
		private function parseTemplateList($template, array $paramsCollection, array $objectList) {

			if (!$template instanceof MailTemplate) {
				return '';
			}

			$content = [];
			foreach ($paramsCollection as $params) {
				$content[] = $template->parse($params, $objectList);
			}

			return implode("\n", $content);
		}

		/**
		 * Возвращает обработанное содержимое шаблона для добавленных полей.
		 * @param string $content
		 * @param iUmiObject[] $objectList
		 * @return string
		 * @throws \coreException|\Exception
		 */
		private function parseObjectFields(string $content, array $objectList) : string {

			while (preg_match('/%((?!parse\.)[\w\-]+\.[^%]+)%/', $content, $matches, PREG_OFFSET_CAPTURE)) {
				$start = $matches[0][1];
				$length = mb_strlen($matches[0][0]);
				$fieldInfoList = explode('.', $matches[1][0]);
				list($typeGuid, $fieldName, $subFieldName) = array_pad($fieldInfoList, 3, '');

				$object = $this->getObject($typeGuid, $objectList);
				$value = ($object instanceof iUmiObject) ? $this->getFieldValue($object, $fieldName, $subFieldName) : '';

				$content = (string) substr_replace($content, $value, $start, $length);
			}

			return $content;
		}

		/**
		 * Возвращает объект по гуиду его типа из списка объектов
		 * @param string $typeGuid
		 * @param iUmiObject[] $objectList
		 * @return iUmiObject|null
		 * @throws \coreException
		 */
		private function getObject($typeGuid, array $objectList) {
			$variableTypeGroup = $this->template->getVariableForRelatedTypeList();
			$typeGuidList = isset($variableTypeGroup[$typeGuid]) ? $variableTypeGroup[$typeGuid] : [$typeGuid];

			foreach ($objectList as $object) {
				if (!$object instanceof iUmiObject) {
					continue;
				}

				$objectType = $object->getType();

				if (!$objectType instanceof \iUmiObjectType) {
					continue;
				}

				$objectTypeGuid = $objectType->getGUID();

				$parentTypeId = $objectType->getParentId();
				$parentType = \umiObjectTypesCollection::getInstance()
					->getType($parentTypeId);

				$parentTypeGuid = $parentType ? $parentType->getGUID() : '';

				foreach ($typeGuidList as $subTypeGuid) {
					if ($objectTypeGuid == $subTypeGuid || $parentTypeGuid == $subTypeGuid) {
						return $object;
					}
				}
			}

			return null;
		}

		/**
		 * Возвращает значение поля
		 * @param iUmiObject $object объект
		 * @param string $fieldName имя поля
		 * @param string $subFieldName имя поля в подобъекте
		 * @return mixed
		 * @throws \Exception
		 */
		private function getFieldValue(iUmiObject $object, $fieldName, $subFieldName = '') {
			$field = $object->getPropByName($fieldName);

			if (!$field instanceof \iUmiObjectProperty) {
				return '';
			}

			$fieldValue = $object->getValue($fieldName);

			switch ($field->getDataType()) {
				case 'boolean':
					return $this->getBooleanFieldValue($fieldValue);
				case 'file':
				case 'video':
				case 'img_file':
					return $this->getImageFieldValue($fieldValue);
				case 'relation':
					return $this->getRelationFieldValue($field, $subFieldName);
				case 'tags':
					return $this->getTagsFieldValue($fieldValue);
				case 'optioned':
					return $this->getOptionedFieldValue($field);
				default:
					return $object->getValue($fieldName);
			}
		}

		/**
		 * Возвращает значение для поля типа boolean
		 * @param mixed $fieldValue значение поля
		 * @return string
		 */
		private function getBooleanFieldValue($fieldValue) {
			return $fieldValue
				? getLabel('boolean-yes', 'umiNotifications')
				: getLabel('boolean-no', 'umiNotifications');
		}

		/**
		 * Возвращает значение для поля типа image
		 * @param mixed $fieldValue значение поля
		 * @return string
		 * @throws \Exception
		 */
		private function getImageFieldValue($fieldValue) {
			$domain = Service::DomainDetector()->detectHost();

			return getServerProtocol() . '://' . $domain . $fieldValue;
		}

		/**
		 * Возвращает значение для поля с типом relation
		 * @param \iUmiObjectProperty $field
		 * @param string $subFieldName имя поля в подобъекте
		 * @return string
		 * @throws \Exception
		 */
		private function getRelationFieldValue(\iUmiObjectProperty $field, $subFieldName = '') {
			$umiObjectsCollection = \umiObjectsCollection::getInstance();
			$fieldValue = $field->getValue();

			if (!$field->getIsMultiple()) {
				$object = $umiObjectsCollection->getObject($fieldValue);

				if (!$object instanceof iUmiObject) {
					return '';
				}

				return $this->prepareRelationFieldValue($object, $subFieldName);
			}

			$result = [];

			foreach ($umiObjectsCollection->getObjectList($fieldValue) as $object) {
				if (!$object instanceof iUmiObject) {
					continue;
				}

				$result[] = $this->prepareRelationFieldValue($object, $subFieldName);
			}

			return implode(', ', $result);
		}

		/**
		 * Подготавливает значение для поля с типом relation
		 * @param iUmiObject $object объект значения
		 * @param string $subFieldName имя поля в подобъекте
		 * @return string
		 * @throws \Exception
		 */
		private function prepareRelationFieldValue(iUmiObject $object, $subFieldName) {
			return $subFieldName === '' ? $object->getName() : $this->getFieldValue($object, $subFieldName);
		}

		/**
		 * Возвращает значение для поля с типом optioned
		 * @param \iUmiObjectProperty $field
		 * @return string
		 */
		private function getOptionedFieldValue(\iUmiObjectProperty $field) {
			$idList = [];

			foreach ($field->getValue() as $option) {
				$idList[$option['rel']] = $option['float'];
			}

			$result = [];
			$objectList = \umiObjectsCollection::getInstance()
				->getObjectList(array_keys($idList));

			foreach ($objectList as $object) {
				if (!$object instanceof iUmiObject) {
					continue;
				}

				$result[] = "{$object->getName()}: {$idList[$object->getId()]}" ;
			}

			return implode("\n", $result);
		}

		/**
		 * Возвращает значение для поля с типом tags
		 * @param array $fieldValue
		 * @return string
		 */
		private function getTagsFieldValue($fieldValue) {
			return implode(', ', $fieldValue);
		}
	}
