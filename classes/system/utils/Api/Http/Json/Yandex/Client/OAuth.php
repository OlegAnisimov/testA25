<?php

	namespace UmiCms\Classes\System\Utils\Api\Http\Json\Yandex\Client;

	use Psr\Http\Message\RequestInterface;
	use Psr\Http\Message\ResponseInterface;
	use UmiCms\Classes\System\Utils\Api\Http\Client;
	use UmiCms\Classes\System\Utils\Api\Http\Exception;
	use UmiCms\Utils\Logger\iFactory as iLoggerFactory;

	/**
	 * Класс клиента API Яндекс.OAuth
	 * @see iOAuth, в нем документация.
	 * @package UmiCms\Classes\System\Utils\Api\Http\Json\Yandex\Client;
	 */
	class OAuth extends Client implements iOAuth {

		/** @const string SERVICE_HOST адрес сервиса */
		const SERVICE_HOST = 'https://oauth.yandex.ru/';

		/** @var string $login логин (идентификатор) приложения */
		private $login;

		/** @var string $clientId пароль приложения */
		private $password;

		/** @inheritDoc */
		public function __construct(iLoggerFactory $loggerFactory, \iConfiguration $configuration) {
			$this->initLogger($loggerFactory, $configuration);
			$keepLog = $configuration->get('debug', 'enabled');
			$this->setKeepLog($keepLog);
			$this->initHttpClient();
		}

		/** @inheritDoc */
		public function setAuth($login, $password) {
			$this->login = $login;
			$this->password = $password;
			return $this;
		}

		/** @inheritDoc */
		public function getTokenByUserCode($code) {
			$request = $this->createPostRequest([
				'grant_type' => 'authorization_code',
				'code' => $code,
			], [
				'token'
			]);

			$response = $this->getResponse($request);

			if (!isset($response['access_token'])) {
				throw new Exception\BadResponse('Yandex.OAuth client error', 1);
			}

			return $response['access_token'];
		}

		/** @inheritDoc */
		protected function getLogDirectory() {
			return 'YandexOAuth';
		}

		/** @inheritDoc */
		protected function buildPath(array $pathParts = []) {
			$path = parent::buildPath($pathParts);
			return rtrim($path, '/');
		}

		/** @inheritDoc */
		protected function getDefaultHeaders() {
			return array_merge([
				'Authorization' => 'Basic ' . base64_encode($this->getLogin() . ':' . $this->getPassword()),
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

		/** @inheritDoc */
		protected function getServiceUrl() {
			return self::SERVICE_HOST;
		}

		/**
		 * Возвращает идентификатор приложения
		 * @return string
		 */
		private function getLogin() {
			return $this->login;
		}

		/**
		 * Возвращает пароль приложения
		 * @return string
		 */
		private function getPassword() {
			return $this->password;
		}
	}
