<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\MiddleWares;
	use UmiCms\System\Streams\iFacade as iStreamsFacade;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Интерфейс контроллера протоколов|потоков
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iStreamsController extends MiddleWares\iAuth, MiddleWares\iUmiManager {

		/**
		 * Устанавливает фасад потоков|протоколов
		 * @param iStreamsFacade $streams фасад потоков|протоколов
		 * @return $this
		 */
		public function setStreams(iStreamsFacade $streams);

		/**
		 * Устанавливает фасад транслятора
		 * @param iTranslator $translator фасад транслятора
		 * @return $this
		 */
		public function setTranslator(iTranslator $translator);
	}