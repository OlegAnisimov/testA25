<?php

	namespace UmiCms\System\Data\Object\Property\Value\File;

	use UmiCms\System\Data\Object\Property\Value\iMigration;
	use UmiCms\System\Data\Object\Property\Value\Table\iSchema;
	use UmiCms\System\Data\Object\Property\Value\ImgFile\Migration as ImgFileMigration;

	/**
	 * Класс миграции значений полей типа "Фaйл" в хранилище для полей типа "Набор файлов"
	 * @package UmiCms\System\Data\Object\Property\Value\File
	 */
	class Migration extends ImgFileMigration implements iMigration {

		/** @inheritDoc */
		protected function getTable(iSchema $schema) {
			return $schema->getFilesTable();
		}
	}