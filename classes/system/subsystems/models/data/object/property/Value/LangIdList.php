<?php

	namespace UmiCms\System\Data\Object\Property\Value;

	/**
	 * Класс значения поля типа "Ссылка на список языков"
	 * @package UmiCms\System\Data\Object\Property\Value
	 */
	class LangIdList extends \umiObjectProperty {

		/** @inheritDoc */
		protected function loadValue() {
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$tableName = $this->getTableName();

			$query = <<<SQL
SELECT `obj_id`, `field_id`, `lang_id` FROM `$tableName` 
WHERE `obj_id` = $objectId AND `field_id` = $fieldId
SQL;

			$result = $this->getConnection()
				->queryResult($query);
			$result->setFetchType(\IQueryResult::FETCH_ASSOC);

			if ($result->length() == 0) {
				return [];
			}

			$idList = [];

			foreach ($result as $row) {
				$idList[] = (int) $row['lang_id'];
			}

			return $idList;
		}

		/** @inheritDoc */
		protected function saveValue() {
			$this->deleteCurrentRows();

			$langIdList = (array) $this->value;
			$langIdList = $this->filterLangIdList($langIdList);

			if (isEmptyArray($langIdList)) {
				return true;
			}

			$tableName = $this->getTableName();
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `lang_id`) VALUES
SQL;
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();

			foreach ($langIdList as $langId) {
				$langId = (int) $langId;
				$query .= sprintf("(%d, %d, %d),", $objectId, $fieldId, $langId);
			}

			$query = rtrim($query, ',') . ';';
			$this->getConnection()->query($query);

			return true;
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$newLangIdList = $newValue;
			$newLangIdList = $this->filterLangIdList($newLangIdList);

			$oldLangIdList = (array) $this->value;
			$oldLangIdList = $this->filterLangIdList($oldLangIdList);

			if (count($newLangIdList) !== count($oldLangIdList)) {
				return true;
			}

			foreach ($newLangIdList as $newLangId) {
				if (!in_array($newLangId, $oldLangIdList)) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Фильтрует некорректные значение из массива идентификаторов языков
		 * @param array $langIdList массив идентификаторов языков
		 * @return array
		 */
		private function filterLangIdList(array $langIdList) : array {
			return array_filter($langIdList, function ($langId) {
				return is_numeric($langId);
			});
		}
	}