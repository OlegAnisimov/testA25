<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Orm\Entity\Mapper as AbstractMapper;

	/**
	 * Класс маппера записей об ответе с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	class Mapper extends AbstractMapper implements iMapper {

		/** @inheritDoc */
		public function getAttributeSchemaList() {
			return parent::getAttributeSchemaList() + [
				self::URL => [
					'getUrl',
					'setUrl',
					'string'
				],
				self::CODE => [
					'getCode',
					'setCode',
					'int'
				],
				self::HITS_COUNT => [
					'getHitsCount',
					'setHitsCount',
					'int'
				],
				self::DOMAIN_ID => [
					'getDomainId',
					'setDomainId',
					'int'
				],
				self::UPDATE_TIME => [
					'getUpdateTime',
					'setUpdateTime',
					'int'
				]
			];
		}
	}