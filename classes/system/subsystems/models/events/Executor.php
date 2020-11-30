<?php
	namespace UmiCms\System\Events;

	use \iUmiEventPoint as iEvent;

	/**
	 * Класс исполнителя обработчиков событий
	 * @package UmiCms\System\Events
	 */
	abstract class Executor implements iExecutor {

		/** @var iHandler $handler обработчик события */
		protected $handler;

		/** @inheritDoc */
		public function __construct(iHandler $handler) {
			$this->handler = $handler;
		}

		/** @inheritDoc */
		abstract public function execute(iEvent $event) : void;
	}