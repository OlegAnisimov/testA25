<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\System\Orm\Entity\Schema as AbstractSchema;

	/**
	 * Класс схемы хранения адреса карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
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

		/** @inheritDoc */
		protected function getRelatedContainerCustomNameList() {
			return parent::getRelatedContainerCustomNameList() + [
				iMapper::DOMAIN_ID => 'cms3_domains',
				iMapper::LANGUAGE_ID => 'cms3_langs'
			];
		}
	}