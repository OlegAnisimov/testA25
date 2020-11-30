<?php

	namespace UmiCms\Classes\System\Utils\Api\Http;

	use GuzzleHttp\Psr7\Request;
	use GuzzleHttp\Client as HttpClient;
	use Psr\Http\Message\MessageInterface;
	use Psr\Http\Message\RequestInterface;
	use Psr\Http\Message\ResponseInterface;
	use UmiCms\Utils\Logger\iFactory as iLoggerFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Exception\BadResponse;

	/**
	 * Абстрактный клиент некоторого сервиса, взаимодействующий с ним по http(s)
	 * @package UmiCms\Classes\System\Utils\Api\Http
	 */
	abstract class Client {

		/** @var HttpClient|null $httpClient http клиент */
		private $httpClient;

		/** @var array $lastRequestData данные последнего POST или PUT запроса */
		private $lastRequestData = [];

		/** @var @var bool $keepLog флаг ведения журнала */
		private $keepLog = true;

		/** @var \iUmiLogger $logger логгер */
		private $logger;

		/**
		 * Устанавливает флаг ведения журнала
		 * @param bool $flag значение флага
		 * @return $this
		 */
		public function setKeepLog($flag = true) {
			$this->keepLog = (bool) $flag;
			return $this;
		}

		/**
		 * Возвращает адрес сервиса
		 * @return string
		 */
		abstract protected function getServiceUrl();

		/**
		 * Возвращает http клиент
		 * @return HttpClient
		 */
		protected function getHttpClient() {
			return $this->httpClient;
		}

		/**
		 * Инициализирует http клиент
		 * @return $this
		 */
		protected function initHttpClient() {
			$this->httpClient = new HttpClient([
				'base_uri' => $this->getServiceUrl(),
				'http_errors' => false,
				'decode_content' => false,
				'verify' => false
			]);
			return $this;
		}

		/**
		 * Возвращает заголовки http запроса по умолчанию
		 * @return array
		 */
		protected function getDefaultHeaders() {
			return [];
		}

		/**
		 * Возвращает опции отправки запроса
		 * @return array
		 */
		protected function getOptions() {
			return [];
		}

		/**
		 * Формирует url из частей
		 * @param array $pathParts части адреса url, @see Client::buildPath()
		 * @param array $queryParts части url query, @see Client::buildQuery()
		 * @return string
		 */
		protected function buildUrl(array $pathParts = [], array $queryParts = []) {
			$queryParts = array_merge($queryParts, $this->getDefaultQuery());

			if ($this->getPrefix()) {
				array_unshift($pathParts, $this->getPrefix());
			}

			return $this->buildPath($pathParts) . $this->buildQuery($queryParts);
		}

		/**
		 * Возвращает префикс адреса запросов
		 * @return string
		 */
		protected function getPrefix() {
			return '';
		}

		/**
		 * Формирует путь (адрес) из частей:
		 *
		 * ['foo', 'bar', 'baz] => 'foo/bar/baz'
		 *
		 * @param array $pathParts
		 * @return string
		 */
		protected function buildPath(array $pathParts = []) {
			$pathParts = array_map(function($path) {
				return trim($path, '/');
			}, $pathParts);

			$pathParts[] = '';
			return implode('/', $pathParts);
		}

		/**
		 * Формирует query из частей:
		 *
		 * ['foo' => 'bar'] => '?foo=bar'
		 *
		 * @param array $queryParts
		 * @return string
		 */
		protected function buildQuery(array $queryParts = []) {
			return empty($queryParts) ? '' : '?' . http_build_query($queryParts);
		}

		/**
		 * Отправляет запрос
		 * @param RequestInterface $request запрос
		 * @return ResponseInterface
		 */
		protected function send(RequestInterface $request) {
			return $this->getHttpClient()->send($request, $this->getOptions());
		}

		/**
		 * Возвращает ответ на запрос
		 * @param RequestInterface $request запрос
		 * @return string|array
		 */
		protected function getResponse(RequestInterface $request) {
			$response = $this->send($request);
			return $this->getResponseBody($response);
		}

		/**
		 * Возвращает содержимое тела ответа
		 * @param ResponseInterface $response ответ
		 * @return string
		 */
		protected function getResponseBody(ResponseInterface $response) {
			return $response->getBody()->__toString();
		}

		/**
		 * Кодирует данные, которые требуется передать POST
		 * @param \stdClass|array $data данные
		 * @return \stdClass|array
		 */
		protected function encodePostData($data) {
			return $data;
		}

		/**
		 * Создает GET запрос
		 * @param array $pathParts части адреса url, @see Client::buildPath()
		 * @param array $queryParts части url query, @see Client::buildQuery()
		 * @param array|null $headers заголовки
		 * @return RequestInterface
		 */
		protected function createGetRequest(array $pathParts = [], array $queryParts = [], array $headers = null) {
			return new Request(
				'GET',
				$this->buildUrl($pathParts, $queryParts),
				$headers ?: $this->getDefaultHeaders()
			);
		}

		/**
		 * Создает POST запрос
		 * @param \stdClass|array $postData содержимое запроса
		 * @param array $pathParts части адреса url, @see Client::buildPath()
		 * @param array $queryParts части url query, @see Client::buildQuery()
		 * @param array|null $headers заголовки
		 * @return RequestInterface
		 */
		protected function createPostRequest($postData = [], array $pathParts = [], array $queryParts = [], array $headers = null) {
			$this->setLastRequestData($postData);
			return new Request(
				'POST',
				$this->buildUrl($pathParts, $queryParts),
				$headers ?: $this->getDefaultHeaders(),
				$this->encodePostData($postData)
			);
		}

		/**
		 * Создает PUT запрос
		 * @param \stdClass|array $postData содержимое запроса
		 * @param array $pathParts части адреса url, @see Client::buildPath()
		 * @param array $queryParts части url query, @see Client::buildQuery()
		 * @param array|null $headers заголовки
		 * @return RequestInterface
		 */
		protected function createPutRequest($postData = [], array $pathParts = [], array $queryParts = [], array $headers = null) {
			$this->setLastRequestData($postData);
			return new Request(
				'PUT',
				$this->buildUrl($pathParts, $queryParts),
				$headers ?: $this->getDefaultHeaders(),
				$this->encodePostData($postData)
			);
		}

		/**
		 * Создает DELETE запрос
		 * @param array $pathParts части адреса url, @see Client::buildPath()
		 * @param array $queryParts части url query, @see Client::buildQuery()
		 * @param array|null $headers заголовки
		 * @return RequestInterface
		 */
		protected function createDeleteRequest(array $pathParts = [], array $queryParts = [], array $headers = null) {
			return new Request(
				'DELETE',
				$this->buildUrl($pathParts, $queryParts),
				$headers ?: $this->getDefaultHeaders()
			);
		}

		/**
		 * Возвращает url query по-умолчанию
		 * @return array
		 */
		protected function getDefaultQuery() {
			return [];
		}

		/**
		 * Возвращает данные последнего POST или PUT запроса
		 * @return array
		 */
		protected function getLastRequestData() {
			return $this->lastRequestData;
		}

		/**
		 * Устанавливает данные последнего POST или PUT запроса
		 * @param \stdClass|array $requestData
		 * @return $this
		 */
		protected function setLastRequestData($requestData = []) {
			$this->lastRequestData = $requestData;
			return $this;
		}

		/**
		 * Возвращает значение флага ведения журнала
		 * @return bool
		 */
		protected function getKeepLog() {
			return $this->keepLog;
		}

		/**
		 * Инициализирует ведение журнала
		 * @param iLoggerFactory $factory фабрика логгеров
		 * @param \iConfiguration $configuration конфигурация
		 * @return $this
		 */
		protected function initLogger(iLoggerFactory $factory, \iConfiguration $configuration) {
			$sysLogPath = $configuration->includeParam('sys-log-path');
			$logPath = sprintf('%s/%s/', rtrim($sysLogPath, '/'), $this->getLogDirectory());
			$logger = $factory->create($logPath);
			$logger->setFileName(date('Y-m-d'));
			return $this->setLogger($logger);
		}

		/**
		 * Возвращает логгер
		 * @return \iUmiLogger
		 */
		protected function getLogger() {
			return $this->logger;
		}

		/**
		 * Устанавливает логгер
		 * @param \iUmiLogger $logger логгер
		 * @return $this
		 */
		protected function setLogger(\iUmiLogger $logger) {
			$this->logger = $logger;
			return $this;
		}

		/**
		 * Возвращает имя директории с логом
		 * @return string
		 */
		protected function getLogDirectory() {
			return 'HttpClient';
		}

		/**
		 * Записывает информацию о запросе в лог-файл
		 * @param RequestInterface $request http-запрос
		 * @param ResponseInterface $response ответ на http запрос
		 * @return $this
		 */
		protected function log(RequestInterface $request, ResponseInterface $response) {
			if (!$this->getKeepLog()) {
				return $this;
			}

			$message = $this->prepareLogMessage($request, $response);

			$logger = $this->getLogger();
			$logger->push($message);
			$logger->save();

			return $this;
		}

		/**
		 * Формирует сообщение для записи в журнал запросов
		 * @param RequestInterface $request http-запрос
		 * @param ResponseInterface $response ответ на http запрос
		 * @return string
		 */
		protected function prepareLogMessage(RequestInterface $request, ResponseInterface $response) {
			$time = strftime('%d/%b/%Y %H:%M:%S');
			$method = $request->getMethod();
			$url = $this->getServiceUrl() . $request->getUri();
			$statusCode = $response->getStatusCode();

			$requestHeaders = $this->prepareHeaders($request);
			$requestData = '';

			if (($method === 'POST' || $method === 'PUT') && umiCount($this->getLastRequestData()) > 0) {
				$requestData = 'Request Data: ' . print_r($this->getLastRequestData(), true);
				$this->setLastRequestData();
			}

			$responseHeaders = $this->prepareHeaders($response);
			$responseBody = self::getResponseBody($response);
			$responseBody = 'Response Data: ' . print_r($responseBody, true);

			$separator = str_repeat('-', 80);
			return <<<MESSAGE
[$time] $method $url $statusCode

$requestHeaders
$requestData

$responseHeaders
$responseBody
$separator


MESSAGE;
		}

		/**
		 * Возвращает заголовки http сообщения
		 * @param MessageInterface $message http сообщение
		 * @return string
		 */
		protected function prepareHeaders(MessageInterface $message) {
			$headerList = [];

			foreach ($message->getHeaders() as $name => $values) {
				$headerList[] = $name . ": " . implode(", ", $values);
			}

			try {
				$className =  trimNameSpace(get_class($message)) . ' ';
			} catch (\ErrorException $exception) {
				$className = '';
			}

			return $className . 'Headers: ' . print_r($headerList, true);
		}

		/**
		 * Разбирает ответ в формате json и декодирует его
		 * @param string $responseBody тело ответа
		 * @return array|string|int|bool|float
		 * @throws BadResponse
		 */
		protected function parseJson($responseBody) {
			$data = json_decode((string) $responseBody, true);

			if (JSON_ERROR_NONE !== json_last_error()) {
				throw new BadResponse('Unable to parse response body into JSON: ' . json_last_error());
			}

			return $data === null ? [] : $data;
		}

		/**
		 * Разбирает ответ в формате xml и преобразует его в SimpleXMLElement
		 * @param string $responseBody тело ответа
		 * @return \SimpleXMLElement
		 * @throws BadResponse
		 */
		protected function parseXml($responseBody) {
			$disableEntities = libxml_disable_entity_loader(true);

			try {
				$xml = new \SimpleXMLElement((string) $responseBody ?: '<root />');
				libxml_disable_entity_loader($disableEntities);
			} catch (\Exception $e) {
				libxml_disable_entity_loader($disableEntities);
				throw new BadResponse('Unable to parse response body into XML: ' . $e->getMessage());
			}

			return $xml;
		}
	}
