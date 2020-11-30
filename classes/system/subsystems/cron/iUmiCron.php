<?php

	use \UmiCms\System\Events\iEventPointFactory as iEventFactory;

	interface iUmiCron {

		/**
		 * Конструктор
		 * @param iConfiguration|null $config конфигация
		 * @param iEventFactory|null $eventFactory фабрика событий
		 */
		public function __construct(\iConfiguration $config = null, iEventFactory $eventFactory = null);

		/**
		 * Запускает обработчики события
		 * @return int
		 */
		public function run();

		/**
		 * Возвращает буффер
		 * @return Mixed буфер
		 */
		public function getBuffer();

		/**
		 * Устанавливает список модулей, обработчики которых нужно выполнить.
		 * Если пустое значение, то будут выполнены обработчики всех модулей.
		 * @param string[] $modules список модулей
		 * @example [
		 * 		'news',
		 * 		'catalog'
		 * ]
		 * @return iUmiCron
		 */
		public function setModules(array $modules = []) : iUmiCron;

		/**
		 * Устанавливает список обработчиков, которые надо выполнить
		 * @param string[] $methods список обработчиков
		 * @example [
		 * 		'news::feedsImportListener',
		 * 		'catalog::reIndexOnCron'
		 * ]
		 * @return iUmiCron
		 */
		public function setMethods(array $methods = []) : iUmiCron;

		/**
		 * Возвращает логи выполнения обработчиков событий
		 * @return array
		 * @example [
		 * 		'executed' => [
		 * 			iHandler,
		 * 			iHandler
		 * 		],
		 *  	'failed' => [
		 * 			iHandler,
		 * 			iHandler
		 * 		],
		 * 		'suppressed' => [
		 * 			iHandler,
		 * 			iHandler
		 * 		]
		 * ]
		 */
		public function getLogs();

		/**
		 * Возвращает логи выполнения обработчиков событий в человекопонятном виде
		 * @return string
		 */
		public function getParsedLogs();

		/** Деструктор */
		public function __destruct();
	}
