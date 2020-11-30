<?php

	namespace UmiCms\Manifest\Migrate\Field;

	use UmiCms\Service;

	/**
	 * Класс команды переноса значений полей типа "Файл" в хранилище для полей типа "Набор файлов"
	 * @package UmiCms\Manifest\Migrate\Field
	 */
	class MoveFileValuesAction extends MoveValueAction {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		protected function loadTargetList() {
			$fieldTypeCollection = \umiFieldTypesCollection::getInstance();
			$fieldType = $fieldTypeCollection->getFieldTypeByDataType('file');

			$fieldCollection = \umiFieldsCollection::getInstance();
			$fieldIdList = $fieldCollection->getFieldIdListByType($fieldType);
			$fieldIdList = array_flip($fieldIdList);
			$targetList = [];

			foreach ($fieldIdList as $index => $value) {
				$targetList[$index] = [
					'id' => $index,
					'from' => 'text_val'
				];
			}

			return $this->setTargetList($targetList);
		}

		/** @inheritDoc */
		protected function getFieldIdByTarget(array $target) {
			if (!isset($target['id'], $target['from'])) {
				throw new \RuntimeException('Incorrect target given: ' . var_export($target, true));
			}

			return (int) $target['id'];
		}

		/** @inheritDoc */
		protected function getMigration() {
			return Service::get('ObjectPropertyValueFileMigration');
		}
	}