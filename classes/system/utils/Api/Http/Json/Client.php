<?php

	namespace UmiCms\Classes\System\Utils\Api\Http\Json;

	use Psr\Http\Message\ResponseInterface;
	use UmiCms\Classes\System\Utils\Api\Http\Client as HttpClient;
	use UmiCms\Classes\System\Utils\Api\Http\Exception\BadResponse;

	/**
	 * Абстрактный клиент некоторого сервиса, взаимодействующий с ним по http(s) с помощью json
	 * @package UmiCms\Classes\System\Utils\Api\Http
	 */
	abstract class Client extends HttpClient {

		/**
		 * Возвращает заголовки http запроса по умолчанию
		 * @return array
		 */
		protected function getDefaultHeaders() {
			return array_merge(parent::getDefaultHeaders(), [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json;charset=UTF-8'
			]);
		}

		/**
		 * Возвращает содержимое тела ответа
		 * @param ResponseInterface $response ответ
		 * @return array
		 * @throws BadResponse
		 */
		protected function getResponseBody(ResponseInterface $response) {
			$responseBody = parent::getResponseBody($response);
			return empty($responseBody) ? [] : $this->parseJson($responseBody);
		}

		/**
		 * Кодирует данные, которые требуется передать POST
		 * @param \stdClass|array $data данные
		 * @return string
		 */
		protected function encodePostData($data) {
			$data = parent::encodePostData($data);
			return json_encode($data);
		}
	}
