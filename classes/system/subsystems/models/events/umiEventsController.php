<?php

	use UmiCms\Service;
	use UmiCms\System\Events\iHandler;
	use UmiCms\System\Events\Handler\iModule;

	/** Класс регистрации обработчиков событий и управления вызовами событий */
	class umiEventsController implements iUmiEventsController, iSingleton {

		/** @var iHandler[] $handlerList список зарегистрированных обработчиков */
		protected static $handlerList = [];

		/** @var iUmiEventsController|null $oInstance единственный экземпляр текущего класса */
		private static $oInstance;

		/** @var bool $isModulesEventHandlersLoaded определяет были ли загружены обработчики событий модулей */
		private $isModulesEventHandlersLoaded = false;

		/** @inheritDoc */
		public static function getInstance($className = null) {
			if (self::$oInstance == null) {
				self::$oInstance = new umiEventsController();
			}
			return self::$oInstance;
		}

		/** @inheritDoc */
		public function callEvent(iUmiEventPoint $event, array $allowedModuleList = [], array $allowedMethodList = []) {
			$eventId = $event->getEventId();
			$logs = ['executed' => [], 'failed' => [], 'suppressed' => []];
			$controlParams = [
				'modules' => $allowedModuleList,
				'methods' => $allowedMethodList,
			];

			foreach ($this->searchEventListeners($eventId) as $handler) {

				if (!$handler->isAllowed($controlParams)) {
					continue;
				}

				try {
					$this->executeCallback($handler, $event);
					$logs['executed'][] = $handler;
				} catch (baseException $e) {
					$logs['failed'][] = $handler;

					if ($handler->getIsCritical()) {
						throw $e;
					}

					continue;
				} catch (breakException $e) {
					$logs['suppressed'][] = $handler;
					break;
				}
			}

			return $logs;
		}

		/**
		 * Регистрирует обработчик события
		 * @param iHandler $handler обработчик события
		 */
		public static function registerEventListener(iHandler $handler) {
			self::$handlerList[] = $handler;
		}

		/** Заблокированный конструктор */
		protected function __construct() {}

		/** Загружает обработчики событий */
		protected function loadEventListeners() {
			$moduleList = Service::Registry()->getList('//modules');

			foreach ($moduleList as $arr) {
				$module = $arr[0];
				$this->loadModuleEventListeners($module);
			}

			$this->isModulesEventHandlersLoaded = true;
		}

		/**
		 * Загружает обработчики событий модуля
		 * @param string $module имя модуля
		 */
		protected function loadModuleEventListeners($module) {
			$path = SYS_MODULES_PATH . "{$module}/events.php";
			$customPath = SYS_MODULES_PATH . "{$module}/custom_events.php";

			$resourcesDir = cmsController::getInstance()->getResourcesDirectory();
			if ($resourcesDir) {
				$this->tryLoadEvents($resourcesDir . "/classes/modules/{$module}/events.php");
				$this->tryLoadEvents($resourcesDir . "/classes/components/{$module}/events.php");
			}

			$pathExtEvents = SYS_MODULES_PATH . "{$module}/ext/events_*.php";
			$extEvents = glob($pathExtEvents);
			if (is_array($extEvents)) {
				foreach (glob($pathExtEvents) as $filename) {
					if (file_exists($filename)) {
						$this->tryLoadEvents($filename);
					}
				}
			}
			$this->tryLoadEvents($customPath);
			$this->tryLoadEvents($path);
		}

		/**
		 * Загружает файл с обработчиками событий, если он доступен
		 * @param string $path путь до файла
		 * @return bool
		 */
		protected function tryLoadEvents($path) {
			if (file_exists($path)) {
				/** @noinspection PhpIncludeInspection */
				require_once $path;
				return true;
			}

			return false;
		}

		/**
		 * Возвращает список обработчиков, способных обработать заданное событие
		 * @param string $eventId идентификатор события
		 * @return iHandler[]
		 */
		protected function searchEventListeners($eventId) {
			static $cache = [];

			if (isset($cache[$eventId])) {
				return $cache[$eventId];
			}

			$result = [];

			if ($this->isModulesEventHandlersLoaded === false) {
				$this->loadEventListeners();
			}

			foreach (self::$handlerList as $handler) {
				if ($handler->getEventId() == $eventId) {
					$result[] = $handler;
				}
			}

			$temp = [];

			foreach ($result as $handler) {
				$temp[$handler->getPriority()][] = $handler;
			}

			$result = [];
			ksort($temp);

			foreach ($temp as $callbackArray) {
				foreach ($callbackArray as $handler) {
					$result[] = $handler;
				}
			}

			return $cache[$eventId] = $result;
		}

		/**
		 * Вызывает обработчик события
		 * @param iHandler $handler обработчик
		 * @param iUmiEventPoint $event событие
		 * @throws Exception
		 * @throws baseException
		 * @throws breakException
		 * @throws ErrorException
		 */
		protected function executeCallback(iHandler $handler, iUmiEventPoint $event) {
			Service::EventHandlerExecutorFactory()
				->createForHandler($handler)
				->execute($event);
		}
	}

