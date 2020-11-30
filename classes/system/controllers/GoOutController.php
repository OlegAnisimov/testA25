<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\Browser\iDetector;

	/**
	 * Класс контроллера перехода по внешним ссылкам
	 * @package UmiCms\Classes\System\Controllers
	 */
	class GoOutController extends AbstractController implements iGoOutController {

		/** @var iDetector $browserDetector определитель браузера */
		private $browserDetector;

		/** @inheritDoc */
		public function setBrowserDetector(iDetector $browserDetector) {
			$this->browserDetector = $browserDetector;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!array_key_exists('url', $this->parameters)) {
				throw new \ErrorException('Incorrect router parameters given, url expected');
			}

			$host = $this->request->host();
			$host = $host ? str_replace('www.', '', $host) : false;
			$referer = $this->request->referrer() ? parse_url($this->request->referrer()) : false;
			$refererHost = false;

			if ($referer && isset($referer['host'])) {
				$refererHost = $referer['host'];
			}

			$url = $this->parameters['url'];

			if (!$url || !$refererHost || !$host || !contains($refererHost, $host)) {
				$this->buffer->crash('robots_denied', 404);
			}

			if ($this->browserDetector->isRobot()) {
				$this->buffer->crash('robots_denied', 403);
			}

			if (startsWith($url, '//')) {
				$protocol = $this->request->isHttps() ? 'https' : 'http';
				$url = sprintf('%s:%s', $protocol, $url);
			}

			$this->buffer->redirect($url);
		}
	}