<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use UmiCms\Classes\System\PageNum\iAgent;
	use \iServiceContainer as iServiceContainer;

	/**
	 * Интерфейс фабрики агентов пагинации
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	interface iFactory {

		/**
		 * Конструктор
		 * @param iServiceContainer $serviceContainer контейнер сервисов
		 */
		public function __construct(iServiceContainer $serviceContainer);

		/**
		 * Создает агент пагинации административной панели
		 * @return iAgent
		 * @throws \Exception
		 */
		public function createAdmin() : iAgent;

		/**
		 * Создает агент пагинации сайта
		 * @return iAgent
		 * @throws \Exception
		 */
		public function createSite() : iAgent;

		/**
		 * Создает агент пагинации протоколов
		 * @return iAgent
		 * @throws \Exception
		 */
		public function createStream() : iAgent;

		/**
		 * Создает агент пагинации по-умолчанию
		 * @return iAgent
		 * @throws \Exception
		 */
		public function createCommon() : iAgent;

		/**
		 * Создает кастомный агент пагинации
		 * @param string $class класс кастомного агента
		 * @return iAgent
		 * @throws \Exception
		 */
		public function createCustom(string $class) : iAgent;
	}