<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\System\Orm\Entity\iFactory as iAbstractFactory;

	/**
	 * Интерфейс фабрики изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iFactory extends iAbstractFactory {

		/**
		 * Создает изображение карты сайта
		 * @return iImage
		 */
		public function create();
	}