<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\AutoThumb\iGenerator;

	/**
	 * Interface iAutoThumbController
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iAutoThumbController extends iController {

		/**
		 * Устанавливает генератор миниатюр
		 * @param iGenerator $generator генератор миниатюр
		 * @return $this
		 */
		public function setGenerator(iGenerator $generator);
	}