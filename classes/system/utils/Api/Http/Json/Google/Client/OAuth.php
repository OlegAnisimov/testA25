<?php
	namespace UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client;

	use \iConfiguration as iConfig;
	use Psr\Http\Message\RequestInterface;
	use Psr\Http\Message\ResponseInterface;
	use UmiCms\Classes\System\Utils\Api\Http\Client;
	use UmiCms\Utils\Logger\iFactory as iLoggerFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Exception;
	use UmiCms\System\Protection\Jwt\Token\iFactory as iJvtFactory;

	/**
	 * Класс клиента API Google.OAuth, работает с помощью jwt и требует сервисны аккаунт в google.
	 * @link https://jwt.io/introduction/
	 * @link https://developers.google.com/identity/protocols/oauth2/service-account
	 * @package UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client
	 */
	class OAuth extends Client implements iOAuth {

		/** @const string SERVICE_HOST адрес сервиса */
		const SERVICE_HOST = 'https://www.googleapis.com/oauth2/v4/';

		/** @var int TOKEN_LIFE_TIME время жизни токена в секундах (максимально возможное значение) */
		const TOKEN_LIFE_TIME = 3600;

		/** @var string $privateKey приватный ключ для подписи запроса */
		private $privateKey;

		/** @var string $serviceAccount сервисный аккаунт в google */
		private $serviceAccount;

		/** @var string $scope область действия авторизации */
		private $scope;

		/** @var iJvtFactory $jvtFactory фабрика jvt токенов */
		private $jvtFactory;

		/** @inheritDoc */
		public function __construct(
			iLoggerFactory $loggerFactory, iConfig $configuration, iJvtFactory $jwtFactory
		) {
			$this->initLogger($loggerFactory, $configuration);
			$keepLog = $configuration->get('debug', 'enabled');
			$this->setKeepLog($keepLog);
			$this->initHttpClient();
			$this->jvtFactory = $jwtFactory;
		}

		/** @inheritDoc */
		public function setServiceAccount(string $serviceAccount) : iOAuth {
			$this->serviceAccount = $serviceAccount;
			return $this;
		}

		/** @inheritDoc */
		public function setScope(string $scope) : iOAuth {
			$this->scope = $scope;
			return $this;
		}

		/** @inheritDoc */
		public function getToken() : string {
			$request = $this->createPostRequest([
				'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
				'assertion' => $this->getJwtToken()
			], [
				'token'
			]);

			$response = $this->getResponse($request);

			if (!isset($response['access_token'])) {
				throw new Exception\BadResponse('Google.OAuth client error', 1);
			}

			return $response['access_token'];
		}

		/** @inheritDoc */
		protected function getServiceUrl() {
			return self::SERVICE_HOST;
		}

		/** @inheritDoc */
		protected function getLogDirectory() {
			return 'GoogleOAuth';
		}

		/** @inheritDoc */
		protected function getDefaultHeaders() {
			return array_merge([
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Accept' => 'application/json',
			]);
		}

		/** @inheritDoc */
		protected function encodePostData($data) {
			return http_build_query($data, '', '&');
		}

		/**
		 * @inheritDoc
		 * @throws Exception\BadResponse
		 */
		protected function getResponseBody(ResponseInterface $response) {
			$responseBody = parent::getResponseBody($response);
			return empty($responseBody) ? [] : $this->parseJson($responseBody);
		}


		/**
		 * @inheritDoc
		 * @throws Exception\BadRequest
		 * @throws Exception\BadResponse
		 */
		protected function getResponse(RequestInterface $request) {
			$response = $this->send($request);
			$this->log($request, $response);
			$body = $this->getResponseBody($response);

			if (isset($body['error'])) {
				throw new Exception\BadRequest($body['error'], 2);
			}

			return $body;
		}

		/**
		 * Возвращает jwt токен
		 * @return string
		 * @throws \ErrorException
		 */
		private function getJwtToken() : string {
			$issueTime = time();
			$attributes = [
				'iss' => $this->getServiceAccount(),
				'scope' => $this->getScope(),
				'aud' => 'https://www.googleapis.com/oauth2/v4/token',
				'exp' => $issueTime + self::TOKEN_LIFE_TIME,
				'iat' => $issueTime,
			];
			return $this->jvtFactory->createByPrivateKey(self::PRIVATE_KEY_NAME, $attributes)
				->getValue();
		}

		/**
		 * Возвращает сервисный аккаунт в google
		 * @link https://developers.google.com/identity/protocols/oauth2/service-account
		 * @return string
		 * @throws \ErrorException
		 */
		private function getServiceAccount() : string {
			if (!$this->serviceAccount) {
				throw new \ErrorException('Google service account expected');
			}

			return $this->serviceAccount;
		}

		/**
		 * Возвращает область действия авторизации в google
		 * @link https://developers.google.com/identity/protocols/oauth2/service-account
		 * @return string
		 * @throws \ErrorException
		 */
		private function getScope() : string {
			if (!$this->scope) {
				throw new \ErrorException('Google auth scope expected');
			}

			return $this->scope;
		}
	}