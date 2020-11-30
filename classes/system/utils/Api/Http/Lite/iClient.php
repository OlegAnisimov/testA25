<?php
	namespace UmiCms\Classes\System\Utils\Api\Http\Lite;

	use GuzzleHttp\ClientInterface;
	use GuzzleHttp\Exception\GuzzleException;

	/**
	 * Интерфейс "легкого" http клиента
	 * @package UmiCms\Classes\System\Utils\Api\Http\Lite
	 */
	interface iClient extends ClientInterface {

		/**
		 * Выполняет GET запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function get(string $url, array $options = []) : string;

		/**
		 * Выполняет POST запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function post(string $url, array $options = []) : string;

		/**
		 * Выполняет HEAD запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function head(string $url, array $options = []) : string;

		/**
		 * Выполняет PUT запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function put(string $url, array $options = []) : string;

		/**
		 * Выполняет PATCH запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function patch(string $url, array $options = []) : string;

		/**
		 * Выполняет DELETE запрос
		 * @param string $url адрес
		 * @param array $options опции
		 * @return string
		 * @throws GuzzleException
		 */
		public function delete(string $url, array $options = []) : string;
	}