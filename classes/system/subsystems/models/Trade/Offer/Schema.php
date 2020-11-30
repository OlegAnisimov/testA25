<?php
	namespace UmiCms\System\Trade\Offer;

	use UmiCms\System\Orm\Entity\Schema as AbstractSchema;

	/**
	 * Класс схемы хранения торговых предложений
	 * @package UmiCms\System\Trade\Offer
	 */
	class Schema extends AbstractSchema implements iSchema {

		/** @inheritDoc */
		protected function getRelatedContainerCustomNameList() {
			return parent::getRelatedContainerCustomNameList() + [
				iMapper::TYPE_ID => 'cms3_object_types',
				iMapper::DATA_OBJECT_ID => 'cms3_objects'
			];
		}

		/** @inheritDoc */
		protected function getRelatedExchangeCustomNameList() {
			return parent::getRelatedExchangeCustomNameList() + [
				iMapper::TYPE_ID => 'cms3_import_types',
				iMapper::DATA_OBJECT_ID => 'cms3_import_objects'
			];
		}

		/** @inheritDoc */
		protected function getNameSpaceRoot() {
			return 'UmiCms\System\Trade\\';
		}
	}