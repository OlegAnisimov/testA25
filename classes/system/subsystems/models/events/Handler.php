<?php
	namespace UmiCms\System\Events;

	/**
	 * Абстрактный класс обработчика события
	 * @package UmiCms\System\Events
	 */
	abstract class Handler implements iHandler {

		/** @var string $eventId идентификатор события */
		protected $eventId;

		/** @var bool $isCritical является ли событие критичным */
		protected $isCritical = false;

		/** @var int $priority порядок выполнения */
		protected $priority = 5;

		/** @inheritDoc */
		public function setEventId(string $id) : iHandler {
			$this->eventId = $id;
			return $this;
		}

		/** @inheritDoc */
		public function getEventId() : ?string {
			return $this->eventId;
		}

		/** @inheritDoc */
		public function setIsCritical($isCritical = false) : iHandler {
			$this->isCritical = (bool) $isCritical;
			return $this;
		}

		/** @inheritDoc */
		public function getIsCritical() : bool {
			return $this->isCritical;
		}

		/** @inheritDoc */
		public function setPriority($priority = 5) : iHandler {
			$priority = (int) $priority;

			if ($priority < 0 || $priority > 9) {
				throw new \coreException('EventListener priority can only be between 0 ... 9');
			}

			$this->priority = $priority;
			return $this;
		}

		/** @inheritDoc */
		public function getPriority() : int {
			return $this->priority;
		}

		/** @inheritDoc */
		public function isAllowed(array $control) : bool {
			return true;
		}
	}