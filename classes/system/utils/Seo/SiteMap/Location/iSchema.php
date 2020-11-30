<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\System\Orm\Entity\iSchema as iAbstractSchema;

	/**
	 * Интерфейс схемы хранения адреса карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	interface iSchema extends iAbstractSchema {

		/** @var string TABLE_CONTAINER_NAME имя таблицы для хранения адресов карты сайта */
		const TABLE_CONTAINER_NAME = 'cms_sitemap';
	}