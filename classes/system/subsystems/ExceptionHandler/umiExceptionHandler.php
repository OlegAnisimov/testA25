<?php

	use UmiCms\Service;
	use UmiCms\Classes\System\Exception\Handler\iExceptionHandler;

	/** Обработчик исключений  */
	class umiExceptionHandler {

		/** @var string DEFAULT_TEMPLATE шаблон исключения по умолчанию */
		const DEFAULT_TEMPLATE = 'default.html';

		/** @var null|iExceptionHandler $handler обработчик исключений */
		private static $handler;

		/** @var mainConfiguration $config конфигурация */
		private static $config;

		/**
		 * Устанавливает обработчик исключений и шаблон вывода.
		 * Обработчик должен быть статическим методом этого класса
		 * @param string $exceptionHandler Имя обработчика
		 * @param string $template Шаблон вывода
		 * @return callable Прошлый обработчик
		 * @throws Exception если обработчика не существует
		 */
		public static function set($exceptionHandler = 'base', $template = '') {
			$exceptionHandler = $exceptionHandler . 'Handler';

			self::$config = mainConfiguration::getInstance();
			self::$handler = Service::get('ExceptionHandlerFactory')->create();

			if (isEmptyString($template)) {
				$template = self::$config->includeParam('system.error') . self::DEFAULT_TEMPLATE;
			}

			try {
				self::$handler->setExceptionTemplate($template);
			} catch (\Exception $exception) {
				trigger_error($exception->getMessage(), E_USER_WARNING);
			}

			return set_exception_handler([
				__CLASS__,
				$exceptionHandler
			]);
		}

		/**
		 * Устанавливает прошлый обработчик исключений
		 * @link http://php.net/manual/en/function.restore-exception-handler.php
		 * @return bool Всегда возвращает TRUE
		 */
		public static function restore() {
			return restore_exception_handler();
		}

		/**
		 * Записывает информацию об ошибке в лог
		 * @param string $message Сообщение об ошибке
		 * @param string $trace Trace ошибки
		 * @return bool
		 */
		public static function createCrashReport($message, $trace) {
			return self::$handler->createCrashReport($message, $trace);
		}

		/**
		 * Записывает исключение в лог
		 * @param Exception $exception исключение
		 * @return bool
		 * @throws Exception
		 */
		public static function report(Exception $exception) {
			if (self::$handler !== null) {
				return self::$handler->report($exception);
			}

			if (!defined('INSTALLER_DEBUG')) {
				self::set();
				return self::$handler->report($exception);
			}

			if (INSTALLER_DEBUG === false) {
				return false;
			}

			throw $exception;
		}

		/**
		 * Стандартный обработчик исключений
		 * @param Exception $e Брошенное исключение
		 * @throws Exception
		 */
		public static function baseHandler($e) {
			self::$handler->handler($e);
		}

		/**
		 * Обрабатывает аварийное завершение запроса
		 * @return bool
		 */
		public static function handleShutdown() {
			if (!self::$config->get('debug', 'handle-shutdown')) {
				return false;
			}

			register_shutdown_function(function() {
				$logsPath = self::$config->includeParam('errors-logs-path');
				$logsDirectory = $logsPath . '/shutdown/';

				if (!is_dir($logsDirectory) && !defined('INSTALLER_DEBUG')) {
					mkdir($logsDirectory, 0777, true);
				}

				$logFile = $logsDirectory . date('Y-m-d H:i:s');

				$libxmlLastError = (libxml_get_last_error()) ?: null;
				$lastError = (error_get_last()) ?: null;

				if (isset($libxmlLastError)) {
					$result = file_put_contents($logFile, print_r($libxmlLastError, true) . "\n", FILE_APPEND | LOCK_EX);

					if (!$result) {
						var_export($libxmlLastError);
						echo '<br />';
					}
				}

				if (isset($lastError)) {
					$result = file_put_contents($logFile, print_r($lastError, true), FILE_APPEND | LOCK_EX);

					if (!$result) {
						var_export($lastError);
					}
				}
			});

			return true;
		}
    }