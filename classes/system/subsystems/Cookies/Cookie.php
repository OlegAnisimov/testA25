<?php

	namespace UmiCms\System\Cookies;

	/**
	 * Класс куки
	 * @package UmiCms\System\Cookies
	 */
	class Cookie implements iCookie {

		/** @var string $name название */
		private $name;

		/** @var mixed $value значение */
		private $value = '';

		/** @var int $expirationTime время, когда срок действия истекает */
		private $expirationTime = 0;

		/** @var string $path uri, в рамках которого будет действовать кука */
		private $path = '/';

		/** @var string $domain домен (поддомен), в рамках которого будет действовать кука */
		private $domain = '';

		/** @var bool $secure флаг, что куку можно использовать только по https */
		private $secure = false;

		/** @var string $sameSite режим доступа к куке с других сайтов */
		private $sameSite = self::SAME_SITE_NONE;

		/**
		 * @var bool $forHttpOnly флаг, что кука будет доступна только через протокол HTTP, то есть к ней не будет
		 * доступа из javascript
		 */
		private $forHttpOnly = false;

		/** @inheritDoc */
		public function __construct($name, $value = '', $expirationTime = 0) {
			$this->setName($name)
				->setValue($value)
				->setExpirationTime($expirationTime);
		}

		/** @inheritDoc */
		public function getName() {
			return $this->name;
		}

		/** @inheritDoc */
		public function getValue() {
			return $this->value;
		}

		/** @inheritDoc */
		public function setValue($value) {
			$this->value = $value;
			return $this;
		}

		/** @inheritDoc */
		public function getExpirationTime() {
			return $this->expirationTime;
		}

		/** @inheritDoc */
		public function setExpirationTime($time) {
			if (!is_int($time)) {
				throw new \wrongParamException('Wrong cookie expiration time given');
			}

			$this->expirationTime = $time;
			return $this;
		}

		/** @inheritDoc */
		public function getPath() {
			return $this->path;
		}

		/** @inheritDoc */
		public function setPath($path) {
			if (!is_string($path) || empty($path)) {
				throw new \wrongParamException('Wrong cookie path given');
			}

			$this->path = $path;
			return $this;
		}

		/** @inheritDoc */
		public function getDomain() {
			return $this->domain;
		}

		/** @inheritDoc */
		public function setDomain($domain) {
			if (!is_string($domain) && $domain !== null) {
				throw new \wrongParamException('Wrong cookie domain given');
			}

			$this->domain = $domain;
			return $this;
		}

		/** @inheritDoc */
		public function isSecure() {
			return $this->secure;
		}

		/** @inheritDoc */
		public function setSecureFlag($flag) {
			if (!is_bool($flag)) {
				throw new \wrongParamException('Wrong cookie secure flag given');
			}

			$this->secure = $flag;
			return $this;
		}

		/** @inheritDoc */
		public function isForHttpOnly() {
			return $this->forHttpOnly;
		}

		/** @inheritDoc */
		public function setHttpOnlyFlag($flag) {
			if (!is_bool($flag)) {
				throw new \wrongParamException('Wrong cookie http only flag given');
			}

			$this->forHttpOnly = $flag;
			return $this;
		}

		/** @inheritDoc */
		public function getSameSite() : string {
			return $this->sameSite;
		}

		/** @inheritDoc */
		public function setSameSite(string $value) : iCookie {
			if (!in_array($value, self::SAME_SITE_WHITE_LIST)) {
				throw new \wrongParamException(sprintf('Wrong cookie same site attribute given: "%s"', $value));
			}

			$this->sameSite = $value;
			return $this;
		}

		/** @inheritDoc */
		private function setName($name) {
			if (!is_string($name) || empty($name)) {
				throw new \wrongParamException('Wrong cookie name given');
			}

			$this->name = $name;
			return $this;
		}
	}
