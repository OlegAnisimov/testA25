<?php
	namespace UmiCms\System\Events\Executor;

	use \iCmsController as iModuleLoader;
	use UmiCms\System\Events\iExecutor;

	/**
	 * Интерфейс исполнителя обработчика событий для модуля
	 * @package UmiCms\System\Events\Executor
	 */
	interface iModule extends iExecutor {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'ModuleEventHandlerExecutor';

		/**
		 * Устанавливает загрузчик модулей
		 * @param iModuleLoader $moduleLoader загрузчик модулей
		 * @return iModule
		 */
		public function setModuleLoader(iModuleLoader $moduleLoader) : iModule;
	}