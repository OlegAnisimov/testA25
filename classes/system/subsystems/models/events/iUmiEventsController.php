<?php

	use UmiCms\System\Events\iHandler;

	/** Интерфейс регистрации и управления вызовами событий */
	interface iUmiEventsController {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'EventController';

		/**
		 * Возвращает единственный экземпляр текущего класса
		 * @param string|null $className имя класса
		 * @return iUmiEventsController
		 */
		public static function getInstance($className = null);

		/**
		 * Вызывает выполняет обработчики для события
		 * @param iUmiEventPoint $event событие
		 * @param string[] $allowedModuleList список модулей, обработчики которых необходимо выполнить
		 * @param string[] $allowedMethodList список обработчиков, которые необходимо выполнить
		 * @return array лог запущенных обработчиков
		 * @throws Exception
		 * @throws baseException
		 */
		public function callEvent(iUmiEventPoint $event, array $allowedModuleList = [], array $allowedMethodList = []);

		/**
		 * Регистрирует обработчик события
		 * @param iHandler $handler обработчик события
		 */
		public static function registerEventListener(iHandler $handler);
	}
