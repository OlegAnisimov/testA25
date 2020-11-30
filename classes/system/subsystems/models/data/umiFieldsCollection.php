<?php
	use UmiCms\Service;

	/** Класс фасада полей */
	class umiFieldsCollection extends singleton implements iUmiFieldsCollection {

		/** @var iUmiField[] $idMap identity map для iUmiField  */
		private $idMap = [];

		/**
		 * @inheritDoc
		 * @return iUmiFieldsCollection
		 */
		public static function getInstance($className = null) {
			return parent::getInstance(__CLASS__);
		}

		/** @inheritDoc */
		public function add($name, $title, $typeId) {
			try {
				$id = $this->addField($name, $title, $typeId);
			} catch (Exception $exception) {
				umiExceptionHandler::report($exception);
				$id = null;
			}

			return $this->getLoadedField($id);
		}

		/** @inheritDoc */
		public function addStrict($name, $title, $typeId) {
			$field = $this->add($name, $title, $typeId);

			if (!$field instanceof iUmiField) {
				$message = getLabel('label-errors-cannot-create-field', false, $title, $name, $typeId);
				throw new ExpectFieldException($message);
			}

			return $field;
		}

		/** @inheritDoc */
		public function addBoolean($name, $title) {
			$typeId = Service::FieldTypeFacade()->getBooleanTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addColor($name, $title) {
			$typeId = Service::FieldTypeFacade()->getColorTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addCounter($name, $title) {
			$typeId = Service::FieldTypeFacade()->getCounterTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addDate($name, $title) {
			$typeId = Service::FieldTypeFacade()->getDateTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addDomainId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getDomainIdTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleDomainId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultipleDomainIdTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addFile($name, $title) {
			$typeId = Service::FieldTypeFacade()->getFileTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addFloat($name, $title) {
			$typeId = Service::FieldTypeFacade()->getFloatTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addImage($name, $title) {
			$typeId = Service::FieldTypeFacade()->getImageTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addInt($name, $title) {
			$typeId = Service::FieldTypeFacade()->getIntTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addObjectTypeId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getObjectTypeIdTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleFile($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultipleFileTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleImage($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultipleImageTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addOfferId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getOfferIdTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleOfferId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultipleOfferIdTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleOption($name, $title, $guideId) {
			$typeId = Service::FieldTypeFacade()->getMultipleOptionTypeId();
			$field = $this->addStrict($name, $title, $typeId);
			return $this->setGuideIdOrDie($field, $guideId);
		}

		/** @inheritDoc */
		public function addPassword($name, $title) {
			$typeId = Service::FieldTypeFacade()->getPasswordTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addPrice($name, $title) {
			$typeId = Service::FieldTypeFacade()->getPriceTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addObjectId($name, $title, $guideId) {
			$typeId = Service::FieldTypeFacade()->getObjectIdTypeId();
			$field = $this->addStrict($name, $title, $typeId);
			return $this->setGuideIdOrDie($field, $guideId);
		}

		/** @inheritDoc */
		public function addMultipleObjectId($name, $title, $guideId) {
			$typeId = Service::FieldTypeFacade()->getMultipleObjectIdTypeId();
			$field = $this->addStrict($name, $title, $typeId);
			return $this->setGuideIdOrDie($field, $guideId);
		}

		/** @inheritDoc */
		public function addString($name, $title) {
			$typeId = Service::FieldTypeFacade()->getStringTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addSwf($name, $title) {
			$typeId = Service::FieldTypeFacade()->getSwfFieldTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultiplePageId($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultiplePageTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addMultipleTag($name, $title) {
			$typeId = Service::FieldTypeFacade()->getMultipleTagTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addSimpleText($name, $title) {
			$typeId = Service::FieldTypeFacade()->getSimpleTextTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addVideo($name, $title) {
			$typeId = Service::FieldTypeFacade()->getVideoTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addHtmlText($name, $title) {
			$typeId = Service::FieldTypeFacade()->getHtmlTextTypeId();
			return $this->addStrict($name, $title, $typeId);
		}

		/** @inheritDoc */
		public function addDomainIdList($name, $title) {
			return $this->addMultipleDomainId($name, $title);
		}

		/** @inheritDoc */
		public function addLinkToObjectType($name, $title) {
			return $this->addObjectTypeId($name, $title);
		}

		/** @inheritDoc */
		public function addOfferIdList($name, $title) {
			return $this->addMultipleOfferId($name, $title);
		}

		/** @inheritDoc */
		public function addOptioned($name, $title, $guideId) {
			return $this->addMultipleOption($name, $title, $guideId);
		}

		/** @inheritDoc */
		public function addRelation($name, $title, $guideId) {
			return $this->addObjectId($name, $title, $guideId);
		}

		/** @inheritDoc */
		public function addMultipleRelation($name, $title, $guideId) {
			return $this->addMultipleObjectId($name, $title, $guideId);
		}

		/** @inheritDoc */
		public function addSymlink($name, $title) {
			return $this->addMultiplePageId($name, $title);
		}

		/** @inheritDoc */
		public function addTags($name, $title) {
			return $this->addMultipleTag($name, $title);
		}

		/** @inheritDoc */
		public function getById($id) {
			return $this->getField($id);
		}

		/** @inheritDoc */
		public function delById($id) {
			return $this->delField($id);
		}

		/** @inheritDoc */
		public function isExists($id) {
			$id = (int) $id;

			if ($id === 0) {
				return false;
			}

			$query = <<<SQL
SELECT `id` FROM `cms3_object_fields` WHERE `id` = $id LIMIT 0, 1;
SQL;
			$result = ConnectionPool::getInstance()
				->getConnection()
				->queryResult($query);

			return $result->length() == 1;
		}

		/** @inheritDoc */
		public function getFieldIdListByType(iUmiFieldType $type) {
			$connection = ConnectionPool::getInstance()
				->getConnection();
			$fieldTypeId = (int) $type->getId();
			$sql = <<<SQL
SELECT `id` FROM `cms3_object_fields` WHERE `field_type_id` = $fieldTypeId;
SQL;
			$result = $connection->queryResult($sql);
			$result->setFetchType(IQueryResult::FETCH_ASSOC);

			$fieldIdList = [];

			foreach ($result as $row) {
				$fieldIdList[] = $row['id'];
			}

			return $fieldIdList;
		}

		/** @inheritDoc */
		public function addField(
			$name,
			$title,
			$fieldTypeId,
			$isVisible = true,
			$isLocked = false,
			$isInheritable = false
		) {
			$connection = ConnectionPool::getInstance()
				->getConnection();
			$sql = 'INSERT INTO cms3_object_fields VALUES()';
			$connection->query($sql);
			$id = $connection->insertId();

			try {
				$field = new umiField($id);
				$field->setName($name);
				$field->setTitle($title);
				$field->setFieldTypeId($fieldTypeId);
				$field->setIsVisible($isVisible);
				$field->setIsLocked($isLocked);
				$field->setIsInheritable($isInheritable);
				$field->commit();
			} catch (Exception $exception) {
				$this->delField($id);
				throw $exception;
			}

			return $this->setLoadedField($field)
				->getLoadedField($id)
				->getId();
		}

		/** @inheritDoc */
		public function getField($id, $data = false) {
			if ($this->isLoaded($id)) {
				return $this->getLoadedField($id);
			}

			return $this->loadField($id, $data);
		}

		/** @inheritDoc */
		public function getFieldList(array $idList) {
			$notLoadedItList = [];

			foreach ($idList as $id) {
				if (!$this->isLoaded($id)) {
					$notLoadedItList[] = $id;
				}
			}

			if (count($notLoadedItList) > 0) {
				$this->loadFieldList($notLoadedItList);
			}

			$fieldList = [];

			foreach ($idList as $id) {
				$field = $this->getLoadedField($id);

				if ($field instanceof iUmiField) {
					$fieldList[] = $field;
				}
			}

			return $fieldList;
		}

		/** @inheritDoc */
		public function delField($id) {
			$field = $this->getField($id);

			if (!$field instanceof iUmiField) {
				return false;
			}

			$field->setIsUpdated(false);
			$connection = ConnectionPool::getInstance()
				->getConnection();
			$sql = "DELETE FROM cms3_object_fields WHERE id = $id";
			$connection->query($sql);

			$this->unloadField($id);

			return $connection->affectedRows() > 0;
		}

		/** @inheritDoc */
		public function clearCache() {
			$this->unloadAllFields();
		}

		/** @inheritDoc */
		public function filterListByNameBlackList(array &$fieldList, array $blackList) {

			foreach ($fieldList as $index => $field) {
				if (in_array($field->getName(), $blackList)) {
					unset($fieldList[$index]);
				}
			}

			return $this;
		}

		/** @inheritDoc */
		public function filterListByTypeWhiteList(array &$fieldList, array $whiteList) {
			foreach ($fieldList as $index => $field) {
				if (!in_array($field->getDataType(), $whiteList)) {
					unset($fieldList[$index]);
				}
			}

			return $this;
		}

		/** @inheritDoc */
		protected function __construct() {}

		/**
		 * Устанавливает идентификатор справочника, в случае ошибки удаляет поле
		 * @param iUmiField $field поле
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws databaseException
		 * @throws expectObjectTypeException
		 */
		private function setGuideIdOrDie(iUmiField $field, $guideId) {
			try {
				$field->setGuideId($guideId);
				$field->commit();
			} catch (Exception $exception) {
				$this->delById($field->getId());
				$message = getLabel('label-errors-incorrect-guide-id-given', false, $guideId);
				throw new expectObjectTypeException($message);
			}

			return $field;
		}

		/**
		 * Загружает список полей
		 * @param array $idList список идентификаторов полей
		 * @return $this
		 * @throws databaseException
		 */
		private function loadFieldList(array $idList) {
			if (isEmptyArray($idList)) {
				return $this;
			}

			$idList = array_map(function ($id) {
				return (int) $id;
			}, $idList);
			$idList = array_unique($idList);
			$limit = count($idList);
			$idList = implode(', ', $idList);

			$sql = <<<SQL
SELECT `id`, `name`, `title`, `is_locked`, `is_inheritable`, `is_visible`, `field_type_id`, `guide_id`, `in_search`, 
`in_filter`, `tip`, `is_required`, `sortable`, `is_system`, `restriction_id`, `is_important` 
FROM `cms3_object_fields`
WHERE `id` IN ($idList)
LIMIT 0, $limit
SQL;
			$result = ConnectionPool::getInstance()
				->getConnection()
				->queryResult($sql)
				->setFetchRow();

			foreach ($result as $row) {
				$id = getFirstValue($row);
				$this->loadField($id, $row);
			}

			return $this;
		}

		/**
		 * Выгружает поле из кеша
		 * @param int $id идентификатор поля
		 * @return $this
		 */
		private function unloadField($id) {
			unset($this->idMap[$id]);
			return $this;
		}

		/**
		 * Выгружает все поля из кеша
		 * @return $this
		 */
		private function unloadAllFields() {
			$this->idMap = [];
			return $this;
		}

		/**
		 * Загружает поле в кеш
		 * @param iUmiField $field
		 * @return $this
		 */
		private function setLoadedField(iUmiField $field) {
			$this->idMap[$field->getId()] = $field;
			return $this;
		}

		/**
		 * Возвращает поля из кеша
		 * @param int $id идентификатор поля
		 * @return iUmiField|bool
		 */
		private function getLoadedField($id) {
			if (!$this->isLoaded($id)) {
				return false;
			}

			return $this->idMap[$id];
		}

		/**
		 * Загружено ли поле в кеш
		 * @param int $id идентификатор поля
		 * @return bool
		 */
		private function isLoaded($id) {
			if (!is_numeric($id)) {
				return false;
			}

			$id = (int) $id;

			return array_key_exists($id, $this->idMap);
		}

		/**
		 * Создает экземпляр коля и возвращает его
		 * @param int $id идентификатор поля
		 * @param array|bool $data список данных поля
		 * @return bool|umiField
		 */
		private function loadField($id, $data = false) {
			try {
				$field = new umiField($id, $data);
			} catch (privateException $e) {
				$e->unregister();
				return false;
			}

			$this->setLoadedField($field);
			return $field;
		}

		/** @deprecated */
		public function getRestrictionIdByFieldId($id) {
			$field = $this->getField($id);
			return ($field instanceof iUmiField) ? $field->getRestrictionId() : false;
		}

		/** @deprecated */
		public function isFieldRequired($id) {
			$field = $this->getField($id);
			return ($field instanceof iUmiField) ? $field->getIsRequired() : false;
		}
	}
