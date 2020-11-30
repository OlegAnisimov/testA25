<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\System\Orm\Entity\Mapper as AbstractMapper;

	/**
	 * Класс маппера адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	class Mapper extends AbstractMapper implements iMapper {

		/** @inheritDoc */
		public function getAttributeSchemaList() {
			return parent::getAttributeSchemaList() + [
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
				self::SORT => [
					'getSort',
					'setSort',
					'int'
				],
				self::PRIORITY => [
					'getPriority',
					'setPriority',
					'float'
				],
				self::DATE_TIME => [
					'getDateTime',
					'setDateTime',
					'string'
				],
				self::LEVEL => [
					'getLevel',
					'setLevel',
					'int'
				],
				self::LANGUAGE_ID => [
					'getLanguageId',
					'setLanguageId',
					'int'
				],
				self::CHANGE_FREQUENCY => [
					'getChangeFrequency',
					'setChangeFrequency',
					'string'
				]
			];
		}

		/** @inheritDoc */
		public function getRelationSchemaList() {
			return parent::getRelationSchemaList() + [
				self::DOMAIN => [
					self::DOMAIN_ID,
					'DomainCollection',
					self::ONE_ID_TO_ONE,
					'getDomain',
					'setDomain'
				],
				self::LANGUAGE => [
					self::LANGUAGE_ID,
					'LanguageCollection',
					self::ONE_ID_TO_ONE,
					'getLanguage',
					'setLanguage'
				],
				self::IMAGE_COLLECTION => [
					self::ID,
					'SiteMapImageFacade',
					self::ONE_ID_TO_COLLECTION,
					'getImageCollection',
					'setImageCollection'
				]
			];
		}
	}