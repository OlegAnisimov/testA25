<?php

	namespace UmiCms\System\Data\Object\Property\Value\LangId;

	use UmiCms\System\Data\Object\Property\Value\iMigration;
	use UmiCms\System\Data\Object\Property\Value\Migration as AbstractMigration;

	/**
	 * Класс миграции значений полей с идентификаторами языков в хранилище для полей типа "Ссылка на язык"
	 * @package UmiCms\System\Data\Object\Property\Value\LangId
	 */
	class Migration extends AbstractMigration implements iMigration {

		/** @inheritDoc */
		public function migrate(\iUmiObjectProperty $property, $previousDataType) {
			return $this->moveValues($property, $previousDataType, $property->getDataType());
		}

		/** @inheritDoc */
		public function rollback(\iUmiObjectProperty $property, $previousDataType) {
			return $this->moveValues($property, $property->getDataType(), $previousDataType);
		}

		/** @inheritDoc */
		protected function moveValues(\iUmiObjectProperty $property, $sourceDataType, $targetDataType) {
			$connection = $this->getConnection();
			$format = 'Migrate property value "%s" from "%s" to "%s"';
			$message = sprintf($format, $property->getName(), $sourceDataType, $targetDataType);
			$connection->startTransaction($message);

			try {
				$schema = $this->getSchema();
				$langIdTable = $schema->getLangIdTable();
				$defaultTable = $schema->getDefaultTable();

				$sourceColumn = $this->getColumnByDataType($sourceDataType);
				$targetColumn = $this->getColumnByDataType($targetDataType);
				$sourceTable = ($sourceColumn === 'lang_id') ? $langIdTable : $defaultTable;
				$targetTable = ($sourceTable === $langIdTable) ? $defaultTable : $langIdTable;

				$this->deleteInconsistentRows($property, $sourceTable, $sourceColumn);
				$this->moveRowsToTarget($property, $sourceTable, $sourceColumn, $targetTable, $targetColumn);
				$this->deleteSourceRows($property, $sourceTable);

			} catch (\databaseException $exception) {
				$connection->rollbackTransaction();
				throw $exception;
			}

			$connection->commitTransaction();
			return $this;
		}

		/**
		 * Удаляет неконсистентные строки из исходной таблицы
		 * @param \iUmiObjectProperty $property мигрируемое значение свойства объекта
		 * @param string $sourceTable имя исходной таблицы
		 * @param string $sourceColumn ячейка исходной таблицы
		 * @return $this
		 * @throws \databaseException
		 */
		private function deleteInconsistentRows(\iUmiObjectProperty $property, string $sourceTable, string $sourceColumn) : Migration {
			$objectId = $property->getObjectId();
			$fieldId = $property->getFieldId();
			$deleteSql = <<<SQL
DELETE FROM `$sourceTable` WHERE `obj_id` = $objectId AND `field_id` = $fieldId  
AND `$sourceColumn` NOT IN (SELECT `id` FROM `cms3_langs`)
SQL;
			$this->getConnection()
				->query($deleteSql);
			return $this;
		}
	}