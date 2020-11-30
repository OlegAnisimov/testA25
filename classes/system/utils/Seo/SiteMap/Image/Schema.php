<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\System\Orm\Entity\Schema as AbstractSchema;

	/**
	 * Класс схемы хранения изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Schema extends AbstractSchema implements iSchema {

		/** @inheritDoc */
		public function getContainerName() {
			return self::TABLE_CONTAINER_NAME;
		}

		/** @inheritDoc */
		protected function getNameSpaceRoot() {
			return 'UmiCms\Classes\System\Utils\SiteMap\\';
		}
	}