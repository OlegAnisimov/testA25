<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iUmiHierarchy as iPageFacade;

	/**
	 * Интерфейс контроллера сокращателя адресов страниц
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iTinyUrlController extends iController {

		/**
		 * Устанавливает фасад страниц
		 * @param iPageFacade $pageFacade фасад страниц
		 * @return $this
		 */
		public function setPageFacade(iPageFacade $pageFacade);
	}