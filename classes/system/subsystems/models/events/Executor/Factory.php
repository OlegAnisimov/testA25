<?php
	namespace UmiCms\System\Events\Executor;

	use \iServiceContainer;
	use UmiCms\System\Events\iHandler;
	use UmiCms\System\Events\iExecutor;
	use UmiCms\System\Events\Handler\iModule as iModuleHandler;
	use UmiCms\System\Events\Handler\iCustom as iCustomHandler;
	use UmiCms\System\Events\Executor\Module as ModuleExecutor;
	use UmiCms\System\Events\Executor\iModule as iModuleExecutor;
	use UmiCms\System\Events\Executor\Custom as CustomExecutor;
	use UmiCms\System\Events\Executor\iCustom as iCustomExecutor;

	/**
	 * Класс фабрики исполнителей обработчиков событий
	 * @package UmiCms\System\Events\Executor
	 */
	class Factory implements iFactory {

		/** @var iServiceContainer $serviceContainer контейнер сервисов */
		private $serviceContainer;

		/** @inheritDoc */
		public function __construct(iServiceContainer $serviceContainer) {
			$this->serviceContainer = $serviceContainer;
		}

		/** @inheritDoc */
		public function createForHandler(iHandler $handler) : iExecutor {
			if ($handler instanceof iModuleHandler) {
				return $this->createForModuleHandler($handler);
			}

			if ($handler instanceof iCustomHandler) {
				return $this->createForCustomHandler($handler);
			}

			throw new \ErrorException(sprintf('Unknown handler type given: "%s"', get_class($handler)));
		}

		/**
		 * Создает исполнителя обработчика событий для модулей
		 * @param iModuleHandler $handler обработчик событий для модулей
		 * @return iModule
		 * @throws \Exception
		 */
		private function createForModuleHandler(iModuleHandler $handler) : iModuleExecutor {
			$executor = new ModuleExecutor($handler);
			$executor = $this->serviceContainer
				->initService(iModuleExecutor::SERVICE_NAME, $executor);
			/** @var iModuleExecutor $executor */
			return $executor;
		}

		/**
		 * Создает исполнителя обработчика событий для произвольного класса
		 * @param iCustomHandler $handler обработчик событий для произвольного класса
		 * @return iCustom
		 */
		private function createForCustomHandler(iCustomHandler $handler) : iCustomExecutor {
			return new CustomExecutor($handler);
		}
	}