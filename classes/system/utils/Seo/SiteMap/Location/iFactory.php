<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\iFactory as iAbstractFactory;

	/**
	 * Интерфейс фабрики адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	interface iFactory extends iAbstractFactory {

		/**
		 * Создает адрес карты сайта
		 * @return iLocation
		 */
		public function create();
	}