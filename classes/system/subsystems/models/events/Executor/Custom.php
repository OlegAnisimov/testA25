<?php
	namespace UmiCms\System\Events\Executor;

	use \iUmiEventPoint as iEvent;
	use UmiCms\System\Events\Executor;
	use UmiCms\System\Events\Handler\iCustom as iCustomHandler;

	/**
	 * Класс исполнителя обработчика событий для произвольного класса
	 * @package UmiCms\System\Events\Executor
	 */
	class Custom extends Executor implements iCustom {

		/** @inheritDoc */
		public function execute(iEvent $event) : void {
			/** @var iCustomHandler $handler */
			$handler = $this->handler;
			call_user_func($handler->getCallback(), $event);
		}
	}