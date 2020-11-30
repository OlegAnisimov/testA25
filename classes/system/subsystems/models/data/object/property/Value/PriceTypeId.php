<?php

	namespace UmiCms\System\Data\Object\Property\Value;

	/**
	 * Класс значения поля типа "Ссылка на тип цены"
	 * @package UmiCms\System\Data\Object\Property\Value
	 */
	class PriceTypeId extends \umiObjectProperty {
		
		/** @var int|null $valueId идентификатор значения */
		private $valueId;

		/** @inheritDoc */
		protected function loadValue() {
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$tableName = $this->getTableName();
			$query = <<<SQL
SELECT `id`, `obj_id`, `field_id`, `price_type_id` FROM `$tableName` 
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
				(int) $row['price_type_id']
			];
		}

		/** @inheritDoc */
		protected function saveValue() {
			$priceTypeId = getFirstValue($this->value);
			$priceTypeId = is_numeric($priceTypeId) ? (int) $priceTypeId : null;

			if ($this->getValueId() === null) {
				$this->insertRow($priceTypeId);
			} else {
				$this->updateRow($priceTypeId);
			}

			return true;
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$newPriceTypeId = (int) getFirstValue($newValue);
			$oldPriceTypeId = (int) getFirstValue($this->value);
			return $oldPriceTypeId !== $newPriceTypeId;
		}

		/**
		 * Вставляет новую строку в хранилище
		 * @param null|int $priceTypeId идентификатор языка
		 * @throws \databaseException
		 */
		private function insertRow($priceTypeId) : void {
			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$priceTypeId = ($priceTypeId === null) ? 'NULL' : (int) $priceTypeId;
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `price_type_id`) 
VALUES ($objectId, $fieldId, $priceTypeId)
SQL;
			$connection = $this->getConnection();
			$connection->query($query);

			$this->setValueId($connection->insertId());
		}

		/**
		 * Обновляет строку в хранилище
		 * @param null|int $priceTypeId идентификатор языка
		 * @throws \databaseException
		 */
		private function updateRow($priceTypeId) : void {
			$tableName = $this->getTableName();
			$priceTypeId = ($priceTypeId === null) ? 'NULL' : (int) $priceTypeId;
			$valueId = (int) $this->getValueId();
			$query = <<<SQL
UPDATE `$tableName` SET `price_type_id` = $priceTypeId WHERE `id` = $valueId
SQL;
			$this->getConnection()
				->query($query);
		}

		/**
		 * Устанавливает идентификатор значения
		 * @param int $id идентификатор
		 * @return $this
		 */
		private function setValueId($id) : PriceTypeId {
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