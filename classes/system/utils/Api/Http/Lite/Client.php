<?php
	namespace UmiCms\Classes\System\Utils\Api\Http\Lite;

	use GuzzleHttp\Client as HttpClient;

	/**
	 * Класс "легкого" http клиента
	 * @package UmiCms\Classes\System\Utils\Api\Http\Lite
	 */
	class Client extends HttpClient implements iClient {

		/** @inheritDoc */
		public function get(string $url, array $options = []) : string {
			return $this->request('GET', $url, $options)
				->getBody()
				->__toString();
		}

		/** @inheritDoc */
		public function post(string $url, array $options = []) : string {
			return $this->request('POST', $url, $options)
				->getBody()
				->__toString();
		}

		/** @inheritDoc */
		public function head(string $url, array $options = []) : string {
			return $this->request('HEAD', $url, $options)
				->getBody()
				->__toString();
		}

		/** @inheritDoc */
		public function put(string $url, array $options = []) : string {
			return $this->request('PUT', $url, $options)
				->getBody()
				->__toString();
		}

		/** @inheritDoc */
		public function patch(string $url, array $options = []) : string {
			return $this->request('PATCH', $url, $options)
				->getBody()
				->__toString();
		}

		/** @inheritDoc */
		public function delete(string $url, array $options = []) : string {
			return $this->request('DELETE', $url, $options)
				->getBody()
				->__toString();
		}
	}