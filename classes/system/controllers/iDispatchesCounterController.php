<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\Dispatches\iCounter;

	/**
	 * Интерфейс контроллера счетчика открытия рассылок
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iDispatchesCounterController extends iController {

		/**
		 * Устанавливает счетчик открытия рассылок
		 * @param iCounter $counter счетчик открытия рассылок
		 * @return $this
		 */
		public function setCounter(iCounter $counter);
	}