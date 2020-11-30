<?php

	namespace UmiCms\System\Cookies;

	/**
	 * Класс списка кук, которые требуется отправить клиенту
	 * @package UmiCms\System\Cookies
	 */
	class ResponsePool implements iResponsePool {

		/** @var array $cookieList список кук, которые требуется отправить клиенту */
		private $cookieList = [];

		/** @inheritDoc */
		public function push(iCookie $cookie) {
			$this->cookieList[$cookie->getName()] = $cookie;
			return $this;
		}

		/** @inheritDoc */
		public function pull($name) {
			$cookie = $this->get($name);

			if ($cookie === null) {
				return $cookie;
			}

			unset($this->cookieList[$name]);
			return $cookie;
		}

		/** @inheritDoc */
		public function isExists($name) {
			return array_key_exists($name, $this->cookieList);
		}

		/** @inheritDoc */
		public function get($name) {
			if (!$this->isExists($name)) {
				return null;
			}

			return $this->cookieList[$name];
		}

		/** @inheritDoc */
		public function getList() {
			return $this->cookieList;
		}

		/** @inheritDoc */
		public function remove($name) {
			unset($this->cookieList[$name]);
			return $this;
		}

		/** @inheritDoc */
		public function clear() {
			$this->cookieList = [];
			return $this;
		}
	}
