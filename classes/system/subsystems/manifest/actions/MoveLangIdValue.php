<?php

	namespace UmiCms\Manifest\Migrate\Field;

	use UmiCms\Service;
	use UmiCms\System\Data\Object\Property\iRepository;
	use UmiCms\System\Data\Object\Property\Value\iMigration;

	/**
	 * Класс команды переноса значений полей с идентификаторами языков в хранилище для поля типа "Ссылка на язык"
	 * @package UmiCms\Manifest\Migrate\Field
	 */
	class MoveLangIdValueAction extends MoveValueAction {

		/**
		 * Возвращает миграциию типов полей
		 * @return iMigration
		 */
		protected function getMigration() {
			return Service::get('ObjectPropertyValueLangIdMigration');
		}
	}