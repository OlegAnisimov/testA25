<?php
	namespace UmiCms\System\Events\Handler;

	use UmiCms\System\Events\Handler;

	/**
	 * Класс обработчика события для произвольного класса
	 * @package UmiCms\System\Events\Handler
	 */
	class Custom extends Handler implements iCustom {

		/** @var callable $callback функция обратного вызова */
		private $callback;

		/** @inheritDoc */
		public function __construct(string $eventId, callable $callback) {
			$this->eventId = $eventId;
			$this->callback = $callback;
		}

		/** @inheritDoc */
		public function getCallback() : callable {
			return $this->callback;
		}

		/** @inheritDoc */
		public function setCallback(callable $callback) : iCustom {
			$this->callback = $callback;
			return $this;
		}
	}