<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\System\Orm\Entity\Mapper as AbstractMapper;

	/**
	 * Класс маппера изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Mapper extends AbstractMapper implements iMapper {

		/** @inheritDoc */
		public function getAttributeSchemaList() {
			return parent::getAttributeSchemaList() + [
				self::LOCATION_ID => [
					'getLocationId',
					'setLocationId',
					'int'
				],
				self::DOMAIN_ID => [
					'getDomainId',
					'setDomainId',
					'int'
				],
				self::LINK => [
					'getLink',
					'setLink',
					'string'
				],
				self::ALT => [
					'getAlt',
					'setAlt',
					'string'
				],
				self::TITLE => [
					'getTitle',
					'setTitle',
					'string'
				]
			];
		}
	}