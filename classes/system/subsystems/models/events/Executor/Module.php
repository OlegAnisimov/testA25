<?php
	namespace UmiCms\System\Events\Executor;

	use \def_module as iModule;
	use \iUmiEventPoint as iEvent;
	use UmiCms\System\Events\Executor;
	use \iCmsController as iModuleLoader;
	use UmiCms\System\Events\Handler\iModule as iModuleHandler;
	use UmiCms\System\Events\Executor\iModule as iModuleExecutor;

	/**
	 * Класс обработчика событий для модуля
	 * @package UmiCms\System\Events\Executor
	 */
	class Module extends Executor implements iModuleExecutor {

		/** @var iModuleLoader $moduleLoader загрузчик модулей */
		private $moduleLoader;

		/** @inheritDoc */
		public function setModuleLoader(iModuleLoader $moduleLoader) : iModuleExecutor {
			$this->moduleLoader = $moduleLoader;
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \ErrorException
		 */
		public function execute(iEvent $event) : void {
			/** @var iModuleHandler $handler */
			$handler = $this->handler;
			$module = $handler->getCallbackModule();
			$moduleInstance = $this->moduleLoader
				->getModule($module);

			if (!$moduleInstance instanceof iModule) {
				throw new \ErrorException("Cannot find module \"{$module}\"");
			}

			$method = $handler->getCallbackMethod();
			$moduleInstance->$method($event);
		}
	}