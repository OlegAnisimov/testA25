<?php
	use UmiCms\Service;
	use UmiCms\System\Events\iHandler;
	use UmiCms\System\Events\Handler\iModule;

	/** @deprecated  */
	class umiEventListener implements iUmiEventListener {

		/** @var iModule $handler обработчик события для модуля */
		private $handler;

		/** @inheritDoc */
		public function __construct($eventId, $callbackModule, $callbackMethod) {
			$this->handler = Service::EventHandlerFactory()
				->createForModule($eventId, $callbackModule, $callbackMethod);
		}

		/** @inheritDoc */
		public function setPriority($priority = 5) : iHandler {
			return $this->handler->setPriority($priority);
		}

		/** @inheritDoc */
		public function getPriority() : int {
			return $this->handler->getPriority();
		}

		/** @inheritDoc */
		public function setIsCritical($isCritical = false) : iHandler {
			return $this->handler->setIsCritical($isCritical);
		}

		/** @inheritDoc */
		public function getIsCritical() : bool {
			return $this->handler->getIsCritical();
		}

		/** @inheritDoc */
		public function setEventId(string $id) : iHandler {
			return $this->handler->setEventId($id);
		}

		/** @inheritDoc */
		public function getEventId() : ?string {
			return $this->handler->getEventId();
		}

		/** @inheritDoc */
		public function setCallbackModule(string $module) : iModule {
			return $this->handler->setCallbackModule($module);
		}

		/** @inheritDoc */
		public function getCallbackModule() : ?string {
			return $this->handler->getCallbackModule();
		}

		/** @inheritDoc */
		public function setCallbackMethod(string $method) : iModule {
			return $this->handler->setCallbackMethod($method);
		}

		/** @inheritDoc */
		public function getCallbackMethod() : ?string {
			return $this->handler->getCallbackMethod();
		}

		/** @inheritDoc */
		public function isAllowed(array $control) : bool {
			return $this->handler->isAllowed($control);
		}
	}

