<?php
	namespace UmiCms\Classes\System\Utils\Api\Http\Lite\Client;

	use UmiCms\Classes\System\Utils\Api\Http\Lite\Client;
	use UmiCms\Classes\System\Utils\Api\Http\Lite\iClient;

	/**
	 * Класс фабрики "легкого" http клиента
	 * @package UmiCms\Classes\System\Utils\Api\Http\Lite\Client
	 */
	class Factory implements iFactory {

		/** @inheritDoc */
		public function create(array $config = []) : iClient {
			$config = $config ?: [
				'http_errors' => false,
				'decode_content' => false,
				'verify' => false,
				'allow_redirects' => false,
			];
			return new Client($config);
		}
	}