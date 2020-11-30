<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Orm\Entity\Schema as AbstractSchema;

	/**
	 * Класс схемы хранения записей об ответе с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	class Schema extends AbstractSchema implements iSchema {

		/** @inheritDoc */
		protected function getNameSpaceRoot() {
			return 'UmiCms\System\\';
		}
	}