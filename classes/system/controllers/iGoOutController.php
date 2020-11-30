<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\Browser\iDetector;

	/**
	 * Интерфейс контроллера перехода по внешним ссылкам
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iGoOutController extends iController {

		/**
		 * Устанавливает определитель браузера
		 * @param iDetector $browserDetector определитель браузера
		 * @return $this
		 */
		public function setBrowserDetector(iDetector $browserDetector);
	}