<?php
	namespace UmiCms\Classes\System\Controllers;

	/**
	 * Класс фабрики контроллеров
	 * @package UmiCms\Classes\System\Controllers
	 */
	class Factory implements iFactory {

		/** @var \iServiceContainer $serviceContainer контейнер сервисов */
		private $serviceContainer;

		/** @inheritDoc */
		public function __construct(\iServiceContainer $serviceContainer) {
			$this->serviceContainer = $serviceContainer;
		}

		/** @inheritDoc */
		public function create(array $parameters) {
			$class = $this->getControllerClass($parameters);

			try {
				$name = $this->getControllerService($parameters);
				$controller = $this->instantiateController($name, $parameters);
			} catch (\ErrorException $exception) {
				\umiExceptionHandler::report($exception);
				$request = $this->serviceContainer->get('Request');
				$response = $this->serviceContainer->get('Response');
				$controller = new $class($request, $response);
			}

			if (is_object($controller) && is_callable([$controller, 'execute'])) {
				return $controller;
			}

			throw new \ErrorException(sprintf('Incorrect _controller given: %s', $class));
		}

		/**
		 * Возвращает имя контроллера по параметрам роутера
		 * @param array $parameters параметры роутера
		 * @return string
		 * @throws \ErrorException
		 */
		private function getControllerClass(array $parameters) : string {
			if (!isset($parameters['_controller'])) {
				throw new \ErrorException('Incorrect router parameters given, _controller expected');
			}

			return $parameters['_controller'];
		}

		/**
		 * Возвращает имя сервиса по параметрам роутера
		 * @param array $parameters параметры роутера
		 * @return string
		 * @throws \ErrorException
		 */
		private function getControllerService(array $parameters) : string {
			if (isset($parameters['_service'])) {
				return $parameters['_service'];
			}

			$class = $this->getControllerClass($parameters);
			return trimNameSpace($class);
		}

		/**
		 * Создает экземпяр контроллера
		 * @param string $name имя контроллера
		 * @param array $parameters параметры роутера
		 * @return iController
		 * @throws \Exception
		 */
		private function instantiateController($name, array $parameters) {
			/** @var iController $controller */
			$controller = $this->serviceContainer->get($name);
			return $controller->setRouterParameters($parameters);
		}
	}