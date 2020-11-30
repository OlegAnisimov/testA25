<?php
	namespace UmiCms\System\Events\Handler;

	use UmiCms\System\Events\iHandler;

	/**
	 * Класс фабрики обработчиков событий
	 * @package UmiCms\System\Events\Handler
	 */
	class Factory implements iFactory {

		/** @var \iUmiEventsController $eventsController */
		private $eventsController;

		/** @inheritDoc */
		public function __construct(\iUmiEventsController $eventsController) {
			$this->eventsController = $eventsController;
		}

		/** @inheritDoc */
		public function createForModule(string $eventId, string $callbackModule, string $callbackMethod) : iModule {
			$handler = new Module($eventId, $callbackModule, $callbackMethod);
			$this->eventsController::registerEventListener($handler);
			return $handler;
		}

		/** @inheritDoc */
		public function createForModuleByConfig(array $config, array $defaultConfig = []) : array {
			$handlerList = [];

			foreach ($config as $handlerConfig) {
				$handlerConfig = array_merge($defaultConfig, $handlerConfig);

				if (!isset($handlerConfig['event'], $handlerConfig['module'], $handlerConfig['method'])) {
					$message = sprintf('Incorrect module handler config given: %s', var_export($handlerConfig , true));
					throw new \ErrorException($message);
				}

				$handler = $this->createForModule(
					$handlerConfig['event'], $handlerConfig['module'], $handlerConfig['method']
				);

				$handlerList[] = $this->updateHandlerWithConfig($handler, $handlerConfig);
			}

			return $handlerList;
		}

		/** @inheritDoc */
		public function createForCustom(string $eventId, callable $callback) : iCustom {
			$handler = new Custom($eventId, $callback);
			$this->eventsController::registerEventListener($handler);
			return $handler;
		}

		/** @inheritDoc */
		public function createForCustomByConfig(array $config, array $defaultConfig = []) : array {
			$handlerList = [];

			foreach ($config as $handlerConfig) {
				$handlerConfig = array_merge($defaultConfig, $handlerConfig);

				if (!isset($handlerConfig['event'], $handlerConfig['callback'])) {
					$message = sprintf('Incorrect custom handler config given: %s', var_export($handlerConfig , true));
					throw new \ErrorException($message);
				}

				$handler = $this->createForCustom(
					$handlerConfig['event'], $handlerConfig['callback']
				);

				$handlerList[] = $this->updateHandlerWithConfig($handler, $handlerConfig);
			}

			return $handlerList;
		}

		/**
		 * Обновляет обработчик события значениями из конфига
		 * @param iHandler $handler обработчик события
		 * @param array $config конфиг
		 * @example
		 *	[
		 * 		'is_critical' => (bool|null) критичность обработчика события,
		 * 		'priority' => (int|null) приоритет обработчика события (от 0 до 9)
		 * 	]
		 * @return iHandler
		 * @throws \coreException
		 */
		private function updateHandlerWithConfig(iHandler $handler, array $config) : iHandler {
			if (array_key_exists('is_critical', $config)) {
				$handler->setIsCritical((bool) $config['is_critical']);
			}

			if (array_key_exists('priority', $config)) {
				$handler->setPriority((int) $config['priority']);
			}

			return $handler;
		}
	}