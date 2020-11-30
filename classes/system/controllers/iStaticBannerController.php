<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iCmsController as iModuleLoader;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Интерфейс контроллера загрузчика баннеров
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iStaticBannerController extends iController {

		/**
		 * Устанавливает загрузчик модулей
		 * @param iModuleLoader $moduleLoader загрузчик модулей
		 * @return $this
		 */
		public function setModuleLoader(iModuleLoader $moduleLoader);

		/**
		 * Устанавливает php шаблонизатор
		 * @param iTemplateEngineFactory $factory фабрика шаблонизаторов
		 * @return $this
		 */
		public function setPhpTemplateEngine(iTemplateEngineFactory $factory);
	}