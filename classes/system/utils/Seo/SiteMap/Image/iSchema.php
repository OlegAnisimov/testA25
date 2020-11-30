<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\System\Orm\Entity\iSchema as iAbstractSchema;

	/**
	 * Интерфейс схемы хранения изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iSchema extends iAbstractSchema {

		/** @var string TABLE_CONTAINER_NAME имя таблицы для хранения изображений карты сайта */
		const TABLE_CONTAINER_NAME = 'cms_sitemap_images';
	}