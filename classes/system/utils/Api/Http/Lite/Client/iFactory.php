<?php
    namespace UmiCms\Classes\System\Utils\Api\Http\Lite\Client;

	use UmiCms\Classes\System\Utils\Api\Http\Lite\iClient;

	/**
	 * Интерфейс фабрики "легкого" http клиента
	 * @package UmiCms\Classes\System\Utils\Api\Http\Lite\Client
	 */
	interface iFactory {

		/**
		 * Создает экземпляр "легкого" http клиента
		 * @param array $config конфигурация клиента
		 * @return iClient
		 */
		public function create(array $config = []) : iClient;
	}