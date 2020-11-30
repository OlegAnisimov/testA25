<?php

	namespace UmiCms\System\Data\Object\Property\Value\ImgFile;

	use UmiCms\System\Data\Object\Property\Value\iMigration;
	use UmiCms\System\Data\Object\Property\Value\Table\iSchema;
	use UmiCms\System\Data\Object\Property\Value\Migration as AbstractMigration;

	/**
	 * Класс миграции значений полей типа "Изображение" в хранилище для полей типа "Набор изображений"
	 * @package UmiCms\System\Data\Object\Property\Value\ImgFile
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
				$fieldTable = $this->getTable($schema);
				$defaultTable =$this->getDefaultTable($schema);

				$sourceColumn = $this->getColumnByDataType($sourceDataType);
				$targetColumn = $this->getColumnByDataType($targetDataType);
				$sourceTable = ($sourceColumn === 'text_val') ? $defaultTable : $fieldTable;
				$targetTable = ($sourceTable === $fieldTable) ? $defaultTable : $fieldTable;

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
		 * Возвращает название таблицы для поля
		 * @param iSchema $schema схему таблиц значений свойств объектов
		 * @return string
		 */
		protected function getTable(iSchema $schema) {
			return $schema->getImagesTable();
		}

		/**
		 * Возвращает название таблицы по умолчанию
		 * @param iSchema $schema схему таблиц значений свойств объектов
		 * @return string
		 */
		protected function getDefaultTable(iSchema $schema) {
			return $schema->getDefaultTable();
		}
	}