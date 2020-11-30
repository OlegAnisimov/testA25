<?php
	namespace UmiCms\System\Events\Handler;

	use UmiCms\System\Events\Handler;

	/**
	 * Класс обработчика события для модуля
	 * @package UmiCms\System\Events\Handler
	 */
	class Module extends Handler implements iModule, \iUmiEventListener {

		/** @var string|null имя модуля-обработчика */
		private $callbackModule;

		/** @var string|null имя метода-обработчика */
		private $callbackMethod;

		/** @inheritDoc */
		public function __construct($eventId, $callbackModule, $callbackMethod) {
			$this->eventId = (string) $eventId;
			$this->callbackModule = (string) $callbackModule;
			$this->callbackMethod = (string) $callbackMethod;
		}

		/** @inheritDoc */
		public function setCallbackModule(string $module) : iModule {
			$this->callbackModule = $module;
			return $this;
		}

		/** @inheritDoc */
		public function getCallbackModule() : ?string {
			return $this->callbackModule;
		}

		/** @inheritDoc */
		public function setCallbackMethod(string $method) : iModule {
			$this->callbackMethod = $method;
			return $this;
		}

		/** @inheritDoc */
		public function getCallbackMethod() : ?string {
			return $this->callbackMethod;
		}

		/** @inheritDoc */
		public function isAllowed(array $control) : bool {
			$moduleList = isset($control['modules']) && is_array($control['modules']) ? $control['modules'] : [];

			if (count($moduleList) > 0) {
				return in_array($this->getCallbackModule(), $moduleList);
			}

			$methodList = isset($control['methods']) && is_array($control['methods']) ? $control['methods'] : [];

			if (count($methodList) > 0) {
				$method = sprintf('%s::%s', $this->getCallbackModule(), $this->getCallbackMethod());
				return in_array($method, $methodList);
			}

			return true;
		}
	}