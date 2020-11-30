<?php
	namespace UmiCms\Classes\System\Controllers;

	/**
	 * Интерфейс фабрики контроллеров
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iFactory {

		/**
		 * Конструктор
		 * @param \iServiceContainer $serviceContainer контейнер сервисов
		 */
		public function __construct(\iServiceContainer $serviceContainer);

		/**
		 * Создает экземпляр контроллера
		 * @param array $parameters параметры роутера
		 * @return iController
		 * @throws \Exception
		 * @throws \ErrorException
		 */
		public function create(array $parameters);
	}