<?php

	namespace UmiCms\System\Cookies;

	use UmiCms\System\Request\Http\iCookies;
	use UmiCms\System\Protection\iEncrypter;
	use UmiCms\System\Session\iSession;

	/**
	 * Класс фасада для работ с куками
	 * @examples:
	 *
	 * 1) Получить значение куки от клиента: CookieJar->get('foo');
	 * 2) Установить куку для ответа клиенту: CookieJar->set('foo', 'bar', 1488);
	 * 3) Пришла ли кука от клиента: CookieJar->isExists('baz);
	 * 4) Удалить куку: CookieJar->remove('baz);
	 *
	 * @package UmiCms\System\Cookies
	 */
	class CookieJar implements iCookieJar {

		/** @var iFactory $factory экземпляр класса фабрики кук */
		private $factory;

		/** @var iResponsePool $response экземпляр класса списка кук, которые требуется отправить клиенту */
		private $response;

		/** @var iCookies $request экземпляр класса контейнера кук запроса */
		private $request;

		/** @var iEncrypter $cryptographer шифровальщик */
		private $cryptographer;

		/** @var iOptions $options класс опций инициализации кук */
		private $options;

		/** @inheritDoc */
		public function __construct(
			iFactory $factory,
			iResponsePool $responsePool,
			iCookies $requestCookies,
			iEncrypter $cryptographer,
			iOptions $options
		) {
			$this->factory = $factory;
			$this->response = $responsePool;
			$this->request = $requestCookies;
			$this->cryptographer = $cryptographer;
			$this->options = $options;
		}

		/** @inheritDoc */
		public function get($name) {
			return $this->getRequest()
				->get($name);
		}

		/** @inheritDoc */
		public function getDecrypted($name) {
			$value = (string) $this->getRequest()
				->get($name);

			if (!isEmptyString($value)) {
				return $this->getEncrypter()
					->decrypt($value);
			}

			return $value ?: null;
		}

		/** @inheritDoc */
		public function set($name, $value = '', $expireTime = 0) {
			$cookie = $this->getFactory()
				->create($name, $value, $expireTime);

			$this->getResponsePool()
				->push($cookie);

			$this->getRequest()
				->set($name, $value);

			return $cookie;
		}

		/** @inheritDoc */
		public function setEncrypted($name, $value = '', $expireTime = 0) {
			$value = (string) $value;

			if (!isEmptyString($value)) {
				$value = $this->getEncrypter()
					->encrypt($value);
			}

			return $this->set($name, $value, $expireTime);
		}

		/** @inheritDoc */
		public function setFromHeader($header) {
			$cookie = $this->getFactory()
				->createFromHeader($header);

			$this->getResponsePool()
				->push($cookie);

			$this->getRequest()
				->set($cookie->getName(), $cookie->getValue());

			return $cookie;
		}

		/** @inheritDoc */
		public function isExists($name) {
			return $this->getRequest()
				->isExist($name);
		}

		/** @inheritDoc */
		public function remove($name) {
			$this->getRequest()
				->del($name);

			$cookie = $this->getResponsePool()
				->pull($name);

			$expiredCookieTime = time() - 3600;

			if ($cookie instanceof iCookie) {
				$cookie->setExpirationTime($expiredCookieTime);
			} else {
				$cookie = $this->getFactory()
					->create($name, '', $expiredCookieTime);
			}

			$this->getResponsePool()
				->push($cookie);

			return $this;
		}

		/** @inheritDoc */
		public function getSessionOptions(iSession $session) : array {
			$options = $this->options->getDefault($session);

			try {
				$this->initDefaultOptions($options);
			} catch (\wrongParamException $exception) {
				\umiExceptionHandler::report($exception);
			}

			return $options;
		}

		/** @inheritDoc */
		public function getCookieOptions(iCookie $cookie) : array {
			return $this->options->getCustom($cookie);
		}

		/** @inheritDoc */
		public function getResponsePool() {
			return $this->response;
		}

		/**
		 * Устанавливает опции генерации кук по умолчанию
		 * @param array $options опции генерации кук
		 * @return iCookieJar
		 * @throws \wrongParamException
		 */
		private function initDefaultOptions(array $options) : iCookieJar {
			if (array_key_exists('path', $options)) {
				$this->factory->setPath($options['path']);
			}

			if (array_key_exists('domain', $options)) {
				$this->factory->setDomain($options['domain']);
			}

			if (array_key_exists('secure', $options)) {
				$this->factory->setSecureFlag($options['secure']);

				if ($options['secure'] && array_key_exists('samesite', $options)) {
					$this->factory->setSameSite($options['samesite']);
				}
			}

			if (array_key_exists('httponly', $options)) {
				$this->factory->setHttpOnlyFlag($options['httponly']);
			}

			return $this;
		}

		/**
		 * Возвращает экземпляр класса фабрики кук
		 * @return iFactory
		 */
		private function getFactory() {
			return $this->factory;
		}

		/**
		 * Возвращает экземпляр класса контейнера кук запроса
		 * @return iCookies
		 */
		private function getRequest() {
			return $this->request;
		}

		/**
		 * Возвращает экземпляр шифровальщика
		 * @return iEncrypter
		 */
		private function getEncrypter() {
			return $this->cryptographer;
		}
	}
