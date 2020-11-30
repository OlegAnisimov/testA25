<?php
	namespace UmiCms\System\Events\Handler;

	/**
	 * Интерфейс фабрики обработчиков событий
	 * @package UmiCms\System\Events\Handler
	 */
	interface iFactory {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'EventHandlerFactory';

		/**
		 * Конструктор
		 * @param \iUmiEventsController $eventsController контроллер событий
		 */
		public function __construct(\iUmiEventsController $eventsController);

		/**
		 * Создает обработчик события для модуля
		 * @param string $eventId идентификатор события
		 * @param string $callbackModule имя модуля-обработчика
		 * @param string $callbackMethod имя метода-обработчика
		 * @return iModule
		 */
		public function createForModule(string $eventId, string $callbackModule, string $callbackMethod) : iModule;

		/**
		 * Создает список обработчиков событий для модуля
		 * @param array $config список конфигов обработчиков
		 * @example
		 * [
		 *		[
		 * 			'event' => (string) идентификатор события,
		 * 			'module' => (string) имя модуля-обработчика,
		 * 			'method' => (string) имя метода-обработчика,
		 * 			'is_critical' => (bool|null) критичность обработчика события,
		 * 			'priority' => (int|null) приоритет обработчика события (от 0 до 9)
		 * 		]
		 * ]
		 * @param array $defaultConfig конфиг со значениями по-умолчанию для конфига обработчика
		 * @example
		 *	[
		 * 		'event' => (string) идентификатор события,
		 * 		'module' => (string) имя модуля-обработчика,
		 * 		'method' => (string) имя метода-обработчика,
		 * 		'is_critical' => (bool|null) критичность обработчика события,
		 * 		'priority' => (int|null) приоритет обработчика события (от 0 до 9)
		 * 	]
		 * @return iModule[]
		 * @throws \coreException
		 * @throws \ErrorException
		 */
		public function createForModuleByConfig(array $config, array $defaultConfig = []) : array;

		/**
		 * Создает обработчик события для произвольного класса
		 * @param string $eventId идентификатор события
		 * @param callable $callback функция обратного вызова
		 * @return iCustom
		 */
		public function createForCustom(string $eventId, callable $callback) : iCustom;

		/**
		 * Создает список обработчиков событий для произвольного класса
		 * @param array $config список конфигов обработчиков
		 * @example
		 * [
		 *		[
		 * 			'event' => (string) идентификатор события,
		 * 			'callback' => (callable) функция обратного вызова
		 * 			'is_critical' => (bool|null) критичность обработчика события,
		 * 			'priority' => (int|null) приоритет обработчика события (от 0 до 9)
		 * 		]
		 * ]
		 * @param array $defaultConfig конфиг со значениями по-умолчанию для конфига обработчика
		 * @example
		 *	[
		 * 		'event' => (string) идентификатор события,
		 * 		'callback' => (callable) функция обратного вызова
		 * 		'is_critical' => (bool|null) критичность обработчика события,
		 * 		'priority' => (int|null) приоритет обработчика события (от 0 до 9)
		 * 	]
		 * @return iCustom[]
		 * @throws \ErrorException
		 * @throws \coreException
		 */
		public function createForCustomByConfig(array $config, array $defaultConfig = []) : array;
	}