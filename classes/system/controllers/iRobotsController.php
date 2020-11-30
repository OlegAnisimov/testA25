<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\Utils\Robots\iGenerator as iRobotsGenerator;

	/**
	 * Интерфейс контроллера robots.txt
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iRobotsController extends iController {

		/**
		 * Устанавливает генератор robots.txt
		 * @param iRobotsGenerator $generator генератор robots.txt
		 * @return $this
		 */
		public function setGenerator(iRobotsGenerator $generator);
	}