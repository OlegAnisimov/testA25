<?php

	use UmiCms\Service;

	/**
	 * Абстрактный класс буфера вывода
	 * @todo сделать нормальные геттеры и сеттеры
	 */
	abstract class outputBuffer implements iOutputBuffer {

		/** @var string $buffer буферизованная информация конкретного буфера */
		protected $buffer = '';

		/** @var null|int $invokeTime время создания конкретного буфера в микросекундах */
		protected $invokeTime;

		/** @var array $options опции буфера */
		protected $options = [];

		/** @var bool $eventsEnabled включена ли отправка событий */
		private $eventsEnabled = true;

		/** @var string $charset кодировка ответа */
		private $charset = 'utf-8';

		/** @var string $contentType тип контента ответа */
		private $contentType = 'text/html';

		/** @var string $status статус ответа */
		private $status = '200 Ok';

		/** @var array $headerList очередь заголовков для отправки имя => значение */
		private $headerList = [];

		/** Конструктор */
		public function __construct() {
			$this->invokeTime = microtime(true);
		}

		/** @inheritDoc */
		abstract public function send();

		/** @inheritDoc */
		public function clear() {
			$this->buffer = '';
			return $this;
		}

		/** @inheritDoc */
		public function reset() {
			while (ob_get_level()) {
				ob_end_clean();
			}

			return $this;
		}

		/** @inheritDoc */
		public function length() {
			return mb_strlen($this->buffer);
		}

		/** @inheritDoc */
		public function content() {
			return $this->buffer;
		}

		/** @inheritDoc */
		public function push($data) {
			$this->buffer .= $data;
			return $this;
		}

		/** @inheritDoc */
		public function end() {
			$this->send();
			$this->stop();
		}

		/** @inheritDoc */
		public function stop() {
			exit('');
		}

		/** @inheritDoc */
		public function calltime() {
			return round(microtime(true) - $this->invokeTime, 6);
		}

		/** @inheritDoc */
		public function redirect($url, $status = '301 Moved Permanently', $numStatus = 301) {
			$this->push(PHP_EOL . 'Redirected to address: ' . $url . PHP_EOL);
			$this->end();
		}

		/** @inheritDoc */
		public function status($status = false) {
			if ($status) {
				$this->status = $status;
			}
			return $this->status;
		}

		/** @inheritDoc */
		public function getStatusCode() {
			return (int) $this->status;
		}

		/** @inheritDoc */
		public function charset($charset = false) {
			if ($charset) {
				$this->charset = $charset;
			}

			return $this->charset;
		}

		/** @inheritDoc */
		public function contentType($contentType = false) {
			if ($contentType) {
				$this->contentType = $contentType;
			}

			return $this->contentType;
		}

		/** @inheritDoc */
		public function isHtml() {
			return $this->contentType() === 'text/html';
		}

		/** @inheritDoc */
		public function option($key, $value = null) {
			if ($value === null) {
				return isset($this->options[$key]) ? $this->options[$key] : null;
			}

			return $this->options[$key] = $value;
		}

		/** @inheritDoc */
		public function issetHeader($name) {
			if (!is_string($name) || $name === '') {
				throw new wrongParamException('Header name must be not empty string');
			}

			return isset($this->headerList[$name]);
		}

		/** @inheritDoc */
		public function setHeader($name, $value) {
			if (!is_string($name) || $name === '') {
				throw new wrongParamException('Header name must be not empty string');
			}

			if (!is_string($value) || $value === '') {
				throw new wrongParamException('Header value must be not empty string');
			}

			$this->headerList[$name] = $value;
			return $this;
		}

		/** @inheritDoc */
		public function unsetHeader($name) {
			if (!is_string($name) || $name === '') {
				throw new wrongParamException('Header name must be not empty string');
			}

			unset($this->headerList[$name]);
			return $this;
		}

		/** @inheritDoc */
		public function getHeaderList() {
			return $this->headerList;
		}

		/**
		 * Возвращает сформированную строку с информацией из буффера
		 * @return string
		 *
		 * Response:
		 * Status => 200 Ok
		 * Content Type => text/html
		 * Charset => utf-8
		 * Headers:
		 * Location => http://example.com/emarket/purchase/result/successful/
		 * Content:
		 * SOME_CONTENT
		 */
		public function getDump() : string {
			$status = "Status => {$this->status(false)}\n";
			$contentType = "Content Type => {$this->contentType(false)}\n";
			$charset = "Charset => {$this->charset(false)}\n";
			$dump = $status . $contentType . $charset;

			if (!empty($this->getHeaderList())) {
				$headers = "Headers:\n";

				foreach ($this->getHeaderList() as $key => $value) {
					$headers .= $key . " => " . $value . "\n";
				}

				$dump .= $headers;
			}

			if (!empty($this->content())) {
				$content = "Content:\n{$this->content()}";
 				$dump .= $content;
			}

			return $dump;
		}

		/** @inheritDoc */
		public function isEventsEnabled() {
			if ($this->isFatalOccurred()) {
				return false;
			}

			return $this->eventsEnabled;
		}

		/** @inheritDoc */
		public function enableEvents() {
			$this->eventsEnabled = true;
			return $this;
		}

		/** @inheritDoc */
		public function disableEvents() {
			$this->eventsEnabled = false;
			return $this;
		}

		/** @inheritDoc */
		public function crash($error, $status = 500) {
			$this->contentType('text/html');
			$this->charset('utf-8');
			$this->setHeader('X-Robots-Tag', 'none');
			$this->status($status);
			$this->push(Service::get('ExceptionHandlerFactory')->create()->getErrorContent($error));
			$this->end();
		}

		/**
		 * @internal
		 * @inheritDoc
		 * @todo: вынести этот метод отсюда
		 * @param string|null $generatorType
		 * @return string|null
		 */
		public static function contentGenerator($generatorType = null) {
			static $contentGenerator = null;

			if ($generatorType === null) {
				return $contentGenerator;
			}

			return $contentGenerator = $generatorType;
		}

		/**
		 * Определяет, что произошла фатальная ошибка
		 * @return bool
		 */
		protected function isFatalOccurred() {
			return $this->getStatusCode() == 500;
		}

		/**
		 * @deprecated
		 * @param $name
		 * @param $arguments
		 * @return null
		 */
		public function __call($name, $arguments) {
			return null;
		}

		/**
		 * @deprecated
		 * @return iOutputBuffer
		 * @throws coreException
		 */
		final public static function current($class = false) {
			$response = Service::Response();

			if ($class) {
				return $response->getBufferByClass($class);
			}

			return $response->getCurrentBuffer();
		}
	}
