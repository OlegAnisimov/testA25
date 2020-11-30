<?php

	namespace UmiCms\Classes\System\Utils\Api\Http\Xml;

	use Psr\Http\Message\ResponseInterface;
	use UmiCms\Classes\System\Utils\Api\Http\Client as HttpClient;
	use UmiCms\Classes\System\Utils\Api\Http\Exception\BadResponse;

	/**
	 * Абстрактный клиент некоторого сервиса, взаимодействующий с ним по http(s) с помощью xml
	 * @package UmiCms\Classes\System\Utils\Api\Http
	 */
	abstract class Client extends HttpClient {

		/**
		 * Возвращает заголовки http запроса по умолчанию
		 * @return array
		 */
		protected function getDefaultHeaders() {
			return array_merge(parent::getDefaultHeaders(), [
				'Accept' => 'text/xml',
				'Content-Type' => 'text/xml; charset=utf-8'
			]);
		}

		/**
		 * Возвращает содержимое тела ответа
		 * @param ResponseInterface $response ответ
		 * @return \SimpleXMLElement
		 * @throws BadResponse
		 */
		protected function getResponseBody(ResponseInterface $response) {
			$responseBody = parent::getResponseBody($response);
			return $this->parseXml($responseBody);
		}
	}
