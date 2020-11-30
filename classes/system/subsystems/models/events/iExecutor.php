<?php
	namespace UmiCms\System\Events;

	use \baseException;
	use \breakException;
	use \iUmiEventPoint as iEvent;

	/**
	 * Интерфейс исполнителя обработчиков событий
	 * @package UmiCms\System\Events
	 */
	interface iExecutor {

		/**
		 * Конструктор
		 * @param iHandler $handler обработчик события
		 */
		public function __construct(iHandler $handler);

		/**
		 * Исполняет обработчик событий
		 * @param iEvent $event событие
		 * @throws baseException
		 * @throws breakException
		 */
		public function execute(iEvent $event) : void;
	}