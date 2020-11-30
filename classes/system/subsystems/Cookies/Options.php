<?php
	namespace UmiCms\System\Cookies;

	use UmiCms\System\Session\iSession;
	use UmiCms\System\Hierarchy\Domain\iDetector;

	/**
	 * Класс опций инициализации кук
	 * @package UmiCms\System\Cookies
	 */
	class Options implements iOptions {

		/** @var iDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @inheritDoc */
		public function __construct(iDetector $domainDetector) {
			$this->domainDetector = $domainDetector;
		}

		/** @inheritDoc */
		public function getCustom(iCookie $cookie) : array {
			return $this->fixSameSite([
				'expires' => $cookie->getExpirationTime(),
				'path' => $cookie->getPath(),
				'domain' => $cookie->getDomain(),
				'secure' => $cookie->isSecure(),
				'httponly' => $cookie->isForHttpOnly(),
				'samesite' => $cookie->getSameSite()
			]);
		}

		/** @inheritDoc */
		public function getDefault(iSession $session) : array {
			return $this->fixSameSite([
				'lifetime' => $session->getCookieLifeTime(),
				'path' => $session->getCookiePath(),
				'domain' => $session->getCookieDomain(),
				'secure' => $session->getCookieSecureFlag(),
				'httponly' => $session->getCookieHttpOnly(),
				'samesite' => $session->getCookieSameSite()
			]);
		}

		/**
		 * Исправляет значение атрибута "SameSite"
		 * @link https://stackoverflow.com/questions/39750906/php-setcookie-samesite-strict/46971326#46971326
		 * @param array $options опции инициализации
		 * @return array
		 * @throws \coreException
		 */
		private function fixSameSite(array $options) : array {
			if ($options['secure'] === false || $this->domainDetector->detect()->isUsingSsl() === false) {
				unset($options['samesite']);
			} elseif (PHP_VERSION_ID < 70300) {
				$options['path'] = $options['path'] . '; samesite=' . $options['samesite'];
				unset($options['samesite']);
			}

			return $options;
		}
	}