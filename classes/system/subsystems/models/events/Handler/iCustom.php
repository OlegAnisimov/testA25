<?php
	namespace UmiCms\System\Events\Handler;

	use UmiCms\System\Events\iHandler;

	/**
	 * Интерфейс обработчика события для произвольного класса
	 * @package UmiCms\System\Events\Handler
	 */
	interface iCustom extends iHandler {

		/**
		 * Конструктор
		 * @param string $eventId идентификатор события
		 * @param callable $callback функция обратного вызова
		 */
		public function __construct(string $eventId, callable $callback);

		/**
		 * Возвращает функцию обратного вызова
		 * @return callable
		 */
		public function getCallback() : callable;

		/**
		 * Устанавливает функцию обратного вызова
		 * @param callable $callback
		 * @return $this|iCustom
		 */
		public function setCallback(callable $callback) : iCustom;
	}