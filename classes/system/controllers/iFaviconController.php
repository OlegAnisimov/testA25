<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Интерфейс контролера фавикона
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iFaviconController extends iController {

		/**
		 * Устанавливает фабрику событий
		 * @param iEventFactory $eventFactory фабрика событий
		 * @return $this
		 */
		public function setEventFactory(iEventFactory $eventFactory);

		/**
		 * Устанавливает определителя доменов
		 * @param iDomainDetector $detector определитель доменов
		 * @return $this
		 */
		public function setDomainDetector(iDomainDetector $detector);
	}