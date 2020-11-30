<?php

	namespace UmiCms\System\Session;

	use UmiCms\System\Cookies\iCookie;
	use UmiCms\System\Cookies\iOptions;
	use UmiCms\System\Cookies\iCookieJar;

	/**
	 * Класс сессии
	 * @package UmiCms\System\Session
	 */
	class Session implements iSession {

		/** @var \iConfiguration|null $config конфигурация */
		private $config;

		/** @var iCookieJar|null $cookieJar фасад для работы с куками */
		private $cookieJar;

		/** @inheritDoc */
		public function __construct(\iConfiguration $config, iCookieJar $cookieJar) {
			$this->config = $config;
			$this->cookieJar = $cookieJar;
			$this->initSettings();
		}

		/** @inheritDoc */
		public function get($key) {
			if (!$this->isValidKey($key)) {
				return null;
			}

			$this->start();
			$session = isset($_SESSION) ? $_SESSION: [];
			$value = getArrayKey($session, $key);
			$this->commit();

			return $value;
		}

		/** @inheritDoc */
		public function isExist($key) {
			if (!$this->isValidKey($key)) {
				return false;
			}

			$this->start();
			$session = isset($_SESSION) ? $_SESSION: [];
			$isExists = array_key_exists($key, $session);
			$this->commit();

			return $isExists;
		}

		/** @inheritDoc */
		public function set($key, $value) {
			if (!$this->isValidKey($key)) {
				return $this;
			}

			$this->start();

			if (isset($_SESSION)) {
				$_SESSION[$key] = $value;
			}

			$this->commit();

			return $this;
		}

		/** @inheritDoc */
		public function del($keyList) {
			$keyList = is_array($keyList) ? $keyList : [$keyList];
			$this->start();

			foreach ($keyList as $key) {
				if (!$this->isValidKey($key)) {
					continue;
				}

				if (isset($_SESSION)) {
					unset($_SESSION[$key]);
				}
			}

			$this->commit();
			return $this;
		}

		/** @inheritDoc */
		public function getArrayCopy() {
			$this->start();
			$session = isset($_SESSION) ? $_SESSION: [];
			$this->commit();

			return $session;
		}

		/** @inheritDoc */
		public function clear() {
			$this->start();
			$_SESSION = [];
			$this->commit();
			return $this;
		}

		/** @inheritDoc */
		public function __get($key) {
			return $this->get($key);
		}

		/** @inheritDoc */
		public function __isset($key) {
			return $this->isExist($key);
		}

		/** @inheritDoc */
		public function __set($key, $value) {
			return $this->set($key, $value);
		}

		/** @inheritDoc */
		public function __unset($keyList) {
			return $this->del($keyList);
		}

		/** @inheritDoc */
		public function changeId($id = null) {
			$this->start();

			if ($id !== null && is_string($id) && !empty($id)) {
				session_id($id);
			} else {
				session_regenerate_id();
			}

			return $this->commit()
				->bufferCookieHeaders()
				->deleteCookieHeaders();
		}

		/** @inheritDoc */
		public function getId() {
			$this->start();
			$id = session_id();
			$this->commit();
			return $id;
		}

		/** @inheritDoc */
		public function getName() {
			$this->start();
			$name = session_name();
			$this->commit();
			return $name;
		}

		/** @inheritDoc */
		public function startActiveTime() {
			$this->set('start_time', time());
			return $this;
		}

		/** @inheritDoc */
		public function endActiveTime() {
			$expiredTime = time() - ($this->getMaxActiveTime() + 1) * self::SECONDS_IN_ONE_MINUTE;
			$this->set('start_time', $expiredTime);
			return $this;
		}

		/** @inheritDoc */
		public function isActiveTimeExpired() {
			$startActiveTime = (int) $this->get('start_time');

			if ($startActiveTime === 0) {
				return false;
			}

			$maxSessionLifeTime = $this->getMaxActiveTime() * self::SECONDS_IN_ONE_MINUTE;

			return $startActiveTime + $maxSessionLifeTime < time();
		}

		/** @inheritDoc */
		public function getActiveTime() {
			$startActiveTime = (int) $this->get('start_time');
			$maxSessionLifeTime = $this->getMaxActiveTime() * self::SECONDS_IN_ONE_MINUTE;

			if ($startActiveTime === 0) {
				return $maxSessionLifeTime;
			}

			return $startActiveTime + $maxSessionLifeTime - time();
		}

		/** @inheritDoc */
		public function getMaxActiveTime() {
			$activeLifeTime = $this->getConfig()
				->get('session', 'active-lifetime');
			return is_numeric($activeLifeTime) ? (int) $activeLifeTime : self::TWO_WEEKS_IN_SECONDS;
		}

		/**
		 * Запускает сессию
		 * @return Session
		 */
		private function start() {
			if (!$this->isStarted()) {
				@session_start();
				$this->bufferCookieHeaders()
					->deleteCookieHeaders();
			}

			return $this;
		}

		/**
		 * Фиксирует изменения сессии
		 * @return Session
		 */
		private function commit() {
			if ($this->isStarted()) {
				session_commit();
			}

			return $this;
		}

		/**
		 * Запущена ли сессия
		 * @return bool
		 */
		private function isStarted() {
			return session_status() === PHP_SESSION_ACTIVE;
		}

		/**
		 * Возвращает менеджер кук
		 * @return null|iCookieJar
		 */
		private function getCookieJar() {
			return $this->cookieJar;
		}

		/**
		 * Возвращает конфиг
		 * @return \iConfiguration|null
		 */
		private function getConfig() {
			return $this->config;
		}

		/**
		 * Инициализирует настройки сессии
		 * @return Session
		 */
		private function initSettings() {
			if (headers_sent()) {
				return $this;
			}

			$options = $this->cookieJar->getSessionOptions($this);

			if (PHP_VERSION_ID < 70300) {
				session_set_cookie_params(
					$options['lifetime'],
					$options['path'],
					$options['domain'],
					$options['secure'],
					$options['httponly']
				);
			} else {
				session_set_cookie_params($options);
			}

			session_name($this->getDefaultSessionName());

			return $this;
		}

		/** @inheritDoc */
		public function getCookieLifeTime() {
			$lifeTime = $this->getConfig()->get('session', 'cookie-lifetime');
			return is_numeric($lifeTime) ? (int) $lifeTime : self::TWO_WEEKS_IN_SECONDS;
		}

		/** @inheritDoc */
		public function getCookiePath() : string {
			$path = $this->config->get('session', 'cookie-path');
			return (is_string($path) && !empty($path)) ? $path : self::DEFAULT_COOKIE_PATH;
		}

		/** @inheritDoc */
		public function getCookieDomain() : ?string {
			$domain = $this->config->get('session', 'cookie-domain');
			return (is_string($domain) && !empty($domain)) ? $domain : self::DEFAULT_COOKIE_DOMAIN;
		}

		/** @inheritDoc */
		public function getCookieSecureFlag() : bool {
			$secureFlag = $this->config->get('session', 'cookie-secure-flag');
			return is_numeric($secureFlag) ? (bool) $secureFlag : self::DEFAULT_COOKIE_SECURE_FLAG;
		}

		/** @inheritDoc */
		public function getCookieHttpOnly() : bool {
			$httpOnlyFlag = $this->config->get('session', 'cookie-http-flag');
			return is_numeric($httpOnlyFlag) ? (bool) $httpOnlyFlag : self::DEFAULT_COOKIE_HTTP_ONLY_FLAG;
		}

		/** @inheritDoc */
		public function getCookieSameSite() : string {
			$sameSite = (string) $this->config->get('session', 'cookie-same-site');
			return in_array($sameSite, iCookie::SAME_SITE_WHITE_LIST) ? $sameSite : iCookie::SAME_SITE_NONE;
		}

		/**
		 * Возвращает имя сессии по умолчанию
		 * @return string
		 */
		private function getDefaultSessionName() : string {
			$name = $this->config->get('session', 'name');
			return (is_string($name) && !empty($name)) ? $name : self::DEFAULT_NAME;
		}

		/**
		 * Буфферизует заголовки кук
		 * @return Session
		 */
		private function bufferCookieHeaders() {
			$cookieJar = $this->getCookieJar();

			foreach (headers_list() as $header) {
				$isCookieHeader = is_int(mb_strpos($header, 'Set-Cookie'));

				if (!$isCookieHeader) {
					continue;
				}

				try {
					$cookieJar->setFromHeader($header);
				} catch (\wrongParamException $e) {
					continue;
				}
			}

			return $this;
		}

		/**
		 * Удаляет все куки из заголовков
		 * @return Session
		 */
		private function deleteCookieHeaders() {
			@header_remove('Set-Cookie');
			return $this;
		}

		/**
		 * Определяет валиден ли ключ для значения сессии
		 * @param mixed $key
		 * @return bool
		 */
		private function isValidKey($key) {
			return (is_string($key) || is_int($key));
		}
	}
