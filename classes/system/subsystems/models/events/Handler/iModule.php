<?php
	namespace UmiCms\System\Events\Handler;

	use UmiCms\System\Events\iHandler;

	/**
	 * Интерфейс обработчика события для модуля
	 * @package UmiCms\System\Events\Handler
	 */
	interface iModule extends iHandler {

		/**
		 * Конструктор
		 * @param string $eventId идентификатор события
		 * @param string $callbackModule имя модуля-обработчика
		 * @param string $callbackMethod имя метода-обработчика
		 */
		public function __construct($eventId, $callbackModule, $callbackMethod);

		/**
		 * Устанавливает имя модуля-обработчика
		 * @param string $module имя модуля-обработчика
		 * @return $this|iModule
		 */
		public function setCallbackModule(string $module) : iModule;

		/**
		 * Возвращает имя модуля-обработчика
		 * @return string|null
		 */
		public function getCallbackModule() : ?string;

		/**
		 * Устанавливает имя метода-обработчика
		 * @param string $method имя метода-обработчика
		 * @return $this|iModule
		 */
		public function setCallbackMethod(string $method) : iModule;

		/**
		 * Возвращает имя метода-обработчика
		 * @return string|null
		 */
		public function getCallbackMethod() : ?string;
	}