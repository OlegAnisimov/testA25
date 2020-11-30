<?php

	namespace UmiCms\System\Cookies;

	/**
	 * Класс фабрики кук
	 * @package UmiCms\System\Cookies
	 */
	class Factory implements iFactory {

		/** @var string $path значение uri по умолчанию, в рамках которого будет действовать кука */
		private $path = '/';

		/** @var string $domain значение домена по умолчанию, в рамках которого будут действовать создаваемые куки */
		private $domain = '';

		/** @var bool $secure значение флага по умолчанию, что куку можно использовать только по https для создаваемых кук */
		private $secure = false;

		/** @var string $sameSite значение флага доступа к куками с других сайтов */
		private $sameSite = iCookie::SAME_SITE_NONE;

		/**
		 * @var bool $forHttpOnly значение флага по умолчанию, что кука будет доступна только через протокол HTTP,
		 * то есть к ней не будет доступа из javascript, для создаваемых кук
		 */
		private $forHttpOnly = false;

		/** @inheritDoc */
		public function create($name, $value = '', $expirationTime = 0) {
			$cookie = new Cookie($name, $value, $expirationTime);
			$cookie = $cookie->setPath($this->path)
				->setDomain($this->domain)
				->setSecureFlag($this->secure)
				->setHttpOnlyFlag($this->forHttpOnly);

			if ($cookie->isSecure()) {
				$cookie->setSameSite($this->sameSite);
			}

			return $cookie;
		}

		/**
		 * @inheritDoc
		 * Реализация основана на:
		 * https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpFoundation/Cookie.php
		 */
		public function createFromHeader($header) {
			if (!is_string($header) || !is_int(mb_strpos($header, 'Set-Cookie:'))) {
				throw new \wrongParamException('Wrong header given');
			}

			$header = str_replace('Set-Cookie:', '', $header);

			$data = [
				'expires' => 0,
				'path' => '/',
				'domain' => '',
				'secure' => false,
				'httponly' => false,
				'samesite' => iCookie::SAME_SITE_NONE,
			];

			foreach (explode(';', $header) as $part) {
				if (contains($part, '=')) {
					list($key, $value) = explode('=', trim($part), 2);
					$key = trim($key);
					$value = trim($value);
				} else {
					$key = trim($part);
					$value = '';
				}

				if (!isset($data['name'])) {
					$data['name'] = $key;
					$data['value'] = $value;
					continue;
				}

				switch ($key = mb_strtolower($key)) {
					case 'name':
					case 'value':
						break;
					case 'max-age':
						$data['expires'] = time() + (int) $value;
						break;
					case 'secure': {
						$data['secure'] = true;
						break;
					}
					case 'httponly': {
						$data['httponly'] = true;
						break;
					}
					case 'samesite' : {
						$data['samesite'] = (string) $value;
						break;
					}
					default:
						$data[$key] = $value;
						break;
				}
			}

			$cookie = $this->create((string) $data['name'], $data['value'], (int) $data['expires']);
			$cookie = $cookie->setPath((string) $data['path'])
				->setDomain((string) $data['domain'])
				->setSecureFlag((bool) $data['secure'])
				->setHttpOnlyFlag((bool) $data['httponly']);

			if ($cookie->isSecure()) {
				$cookie->setSameSite((string) $data['samesite']);
			}

			return $cookie;
		}

		/** @inheritDoc */
		public function setPath($path) {
			if (!is_string($path) || empty($path)) {
				throw new \wrongParamException('Wrong default cookie path given');
			}

			$this->path = $path;
			return $this;
		}

		/** @inheritDoc */
		public function setDomain($domain) {
			if (!is_string($domain) && $domain !== null) {
				throw new \wrongParamException('Wrong default cookie domain given');
			}

			$this->domain = $domain;
			return $this;
		}

		/** @inheritDoc */
		public function setSecureFlag($flag) {
			if (!is_bool($flag)) {
				throw new \wrongParamException('Wrong default cookie secure flag given');
			}

			$this->secure = $flag;
			return $this;
		}

		/** @inheritDoc */
		public function setHttpOnlyFlag($flag) {
			if (!is_bool($flag)) {
				throw new \wrongParamException('Wrong default cookie http only flag given');
			}

			$this->forHttpOnly = $flag;
			return $this;
		}

		/** @inheritDoc */
		public function setSameSite(string $value) : iFactory {
			if (!in_array($value, iCookie::SAME_SITE_WHITE_LIST)) {
				throw new \wrongParamException(sprintf('Wrong cookie same site attribute given: "%s"', $value));
			}

			$this->sameSite = $value;
			return $this;
		}
	}
