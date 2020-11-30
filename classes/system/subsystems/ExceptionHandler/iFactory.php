<?php

	namespace UmiCms\Classes\System\Exception\Handler;

	/**
	 * Интерфейс фабрики обработчика исключений
	 * @package UmiCms\Classes\System\Exception\Handler
	 */
	interface iFactory {

		/**
		 * Создает обработчик исключений
		 * @return iExceptionHandler
		 */
		public function create();

		/**
		 * Создает кастомный обработчик ошибок
		 * @return mixed|false
		 */
		public function createCustomHandler();

		/**
		 * Создает системный обработчик ошибок
		 * @return System
		 */
		public function createSystemHandler();

	}