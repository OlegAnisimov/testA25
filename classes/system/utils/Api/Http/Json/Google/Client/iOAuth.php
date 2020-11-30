<?php
	namespace UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client;

	use \iConfiguration as iConfig;
	use UmiCms\Utils\Logger\iFactory as iLoggerFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Exception;
	use UmiCms\System\Protection\Jwt\Token\iFactory as iJvtFactory;

	/**
	 * Интерфейс клиента API Google.OAuth, работает с помощью jwt и требует сервисны аккаунт в google.
	 * @link https://jwt.io/introduction/
	 * @link https://developers.google.com/identity/protocols/oauth2/service-account
	 * @package UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client
	 */
	interface iOAuth {

		/** @var string SERVICE_NAME имя сервиса в UMI */
		const SERVICE_NAME = 'GoogleOAuthClient';

		/** @var string SERVICE_NAME имя приватного ключа */
		const PRIVATE_KEY_NAME = 'GoogleOAuth';

		/**
		 * Конструктор
		 * @param iLoggerFactory $loggerFactory экземпляр фабрики логгеров
		 * @param iConfig $configuration конфигурация
		 * @param iJvtFactory $jwtFactory фабрика jwt токенов
		 */
		public function __construct(
			iLoggerFactory $loggerFactory, iConfig $configuration, iJvtFactory $jwtFactory
		);

		/**
		 * Устанавливает сервисный аккаунт в google
		 * @param string $serviceAccount сервисный аккаунт в google
		 * @return $this|iOAuth
		 */
		public function setServiceAccount(string $serviceAccount) : iOAuth;

		/**
		 * Устанавливает область действия авторизации
		 * @param string $scope область действия авторизации
		 * @return $this|iOAuth
		 */
		public function setScope(string $scope) : iOAuth;

		/**
		 * Возвращает авторизационный токен.
		 * Токен действует 1 час, @see OAuth::TOKEN_LIFE_TIME
		 * @return string
		 * @throws Exception\BadRequest
		 * @throws Exception\BadResponse
		 * @throws \ErrorException
		 */
		public function getToken() : string;

		/**
		 * Устанавливает флаг ведения журнала
		 * @param bool $flag значение флага
		 * @return $this
		 */
		public function setKeepLog($flag = true);
	}