<?php

	namespace UmiCms\System\Data\Object\Property\Value;

	/**
	 * Класс значения поля типа "Ссылка на язык"
	 * @package UmiCms\System\Data\Object\Property\Value
	 */
	class LangId extends \umiObjectProperty {
		
		/** @var int|null $valueId идентификатор значения */
		private $valueId;

		/** @inheritDoc */
		protected function loadValue() {
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$tableName = $this->getTableName();
			$query = <<<SQL
SELECT `id`, `obj_id`, `field_id`, `lang_id` FROM `$tableName` 
WHERE `obj_id` = $objectId AND `field_id` = $fieldId LIMIT 0, 1
SQL;
			$result = $this->getConnection()
				->queryResult($query);
			$result->setFetchType(\IQueryResult::FETCH_ASSOC);

			if ($result->length() == 0) {
				return [];
			}

			$row = $result->fetch();
			$this->setValueId($row['id']);
			return [
				(int) $row['lang_id']
			];
		}

		/** @inheritDoc */
		protected function saveValue() {
			$langId = getFirstValue($this->value);
			$langId = is_numeric($langId) ? (int) $langId : null;

			if ($this->getValueId() === null) {
				$this->insertRow($langId);
			} else {
				$this->updateRow($langId);
			}

			return true;
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$newLangId = (int) getFirstValue($newValue);
			$oldLangId = (int) getFirstValue($this->value);
			return $oldLangId !== $newLangId;
		}

		/**
		 * Вставляет новую строку в хранилище
		 * @param null|int $langId идентификатор языка
		 * @throws \databaseException
		 */
		private function insertRow($langId) : void {
			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$langId = ($langId === null) ? 'NULL' : (int) $langId;
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `lang_id`) 
VALUES ($objectId, $fieldId, $langId)
SQL;
			$connection = $this->getConnection();
			$connection->query($query);

			$this->setValueId($connection->insertId());
		}

		/**
		 * Обновляет строку в хранилище
		 * @param null|int $langId идентификатор языка
		 * @throws \databaseException
		 */
		private function updateRow($langId) : void {
			$tableName = $this->getTableName();
			$langId = ($langId === null) ? 'NULL' : (int) $langId;
			$valueId = (int) $this->getValueId();
			$query = <<<SQL
UPDATE `$tableName` SET `lang_id` = $langId WHERE `id` = $valueId
SQL;
			$this->getConnection()
				->query($query);
		}

		/**
		 * Устанавливает идентификатор значения
		 * @param int $id идентификатор
		 * @return $this
		 */
		private function setValueId($id) : LangId {
			$this->valueId = (int) $id;
			return $this;
		}

		/**
		 * Возвращает идентификатор значения
		 * @return int|null
		 */
		private function getValueId() : ?int {
			return $this->valueId;
		}
	}