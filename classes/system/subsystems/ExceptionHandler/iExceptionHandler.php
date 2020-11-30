<?php

	namespace UmiCms\Classes\System\Exception\Handler;

	/**
	 * Интерфейс обработчика исключений
	 * @package UmiCms\Classes\System\Exception\Handler
	 */
	interface iExceptionHandler {

		/**
		 * Записывает исключение в лог
		 * @param \Exception|\Error $exception исключение
		 * @return bool
		 */
		public function report($exception);

		/**
		 * Обрабатывает исключение
		 * @param \Exception|\Error $exception исключение
		 * @throws \Exception
		 */
		public function handler($exception);

		/**
		 * Записывает информацию об ошибке в лог
		 * @param string $message Сообщение об ошибке
		 * @param string $trace Trace ошибки
		 * @return bool
		 */
		public function	createCrashReport($message, $trace);

		/**
		 * Устанавливает шаблон вывода для исключения
		 * @param string $file путь до файла шаблона
		 * @throws \Exception если шаблон не существует
		 */
		public function setExceptionTemplate($file);

		/**
		 * Возвращает контент файла ошибки
		 * @param string $error ошибка
		 * @return false|string
		 */
		public function getErrorContent($error);
	}