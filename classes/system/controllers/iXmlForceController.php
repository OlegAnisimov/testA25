<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\MiddleWares;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Интерфейс контроллера запроса xml данных
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iXmlForceController extends iController, MiddleWares\iAuth, MiddleWares\iUmiManager, MiddleWares\iStub,
		MiddleWares\iUmapRouter, MiddleWares\iMirrorHandler, MiddleWares\iModuleRouter {

		/**
		 * Уставливает конфигурацию
		 * @param iConfig $config
		 * @return $this
		 */
		public function setConfig(iConfig $config);

		/**
		 * Устанавливает фасад транслятора
		 * @param iTranslator $translator фасад транслятора
		 * @return $this
		 */
		public function setTranslator(iTranslator $translator);
	}