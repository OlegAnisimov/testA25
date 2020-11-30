<?php

	namespace UmiCms\System\Auth;

	use UmiCms\System\Auth\AuthenticationRules;
	use UmiCms\System\Session\iSession;
	use UmiCms\System\Cookies;

	/**
	 * Класс аутентификации пользователей
	 * @package UmiCms\System\Auth
	 */
	class Authentication implements iAuthentication {

		/** @var AuthenticationRules\iFactory $authenticationRulesFactory фабрика правил аутентификации */
		private $authenticationRulesFactory;

		/** @var iSession $session контейнер сессии */
		private $session;

		/** @var Cookies\iCookieJar $cookieJar класс для работы с куками */
		private $cookieJar;

		/** @inheritDoc */
		public function __construct(AuthenticationRules\iFactory $authenticationRulesFactory, iSession $session, Cookies\iCookieJar $cookieJar) {
			$this->authenticationRulesFactory = $authenticationRulesFactory;
			$this->session = $session;
			$this->cookieJar = $cookieJar;
		}

		/** @inheritDoc */
		public function authenticate($login, $password) {
			$this->validateLogin($login);
			$this->validatePassword($password);

			$userId = $this->getAuthenticationRulesFactory()
				->createByLoginAndPassword($login, $password)
				->validate();

			if ($userId === false) {
				throw new AuthenticationException("Cannot authenticate user by login = {$login} and password = {$password}");
			}

			return $userId;
		}

		/** @inheritDoc */
		public function authenticateByLoginAndHash($login, $hash) {
			$this->validateLogin($login);
			$this->validatePassword($hash);
			$userId = $this->getAuthenticationRulesFactory()
				->createByLoginAndHash($login, $hash)
				->validate();

			if ($userId === false) {
				throw new AuthenticationException("Cannot authenticate user by login = {$login} and hash = {$hash}");
			}

			return $userId;
		}

		/** @inheritDoc */
		public function authenticateByCode($code) {
			$this->validateCode($code);

			$userId = $this->getAuthenticationRulesFactory()
				->createByActivationCode($code)
				->validate();

			if ($userId === false) {
				throw new AuthenticationException("Cannot authenticate user by activation code = {$code}");
			}

			return $userId;
		}

		/** @inheritDoc */
		public function authenticateBySocials($uid, $provider, $service = null) {
			$this->validateProvider($provider);
			$this->validateUid($uid);

			if ($service == 'ulogin') {
				$userId = $this->getAuthenticationRulesFactory()
					->createByUidAndProvider($uid, $provider)
					->validate();
			} elseif ($service == 'loginza') {
				$userId = $this->getAuthenticationRulesFactory()
					->createByLoginAndProvider($uid, $provider)
					->validate();
			}

			if ($userId === false) {
				throw new AuthenticationException("Cannot authenticate user by login or uid = {$uid}, provider = {$provider} and service = {$service}");
			}

			return $userId;
		}

		/** @inheritDoc */
		public function authenticateByUserId($userId) {
			$this->validateUserId($userId);

			$userId = $this->getAuthenticationRulesFactory()
				->createByUserId($userId)
				->validate();

			if ($userId === false) {
				throw new AuthenticationException("Cannot authenticate user by id = {$userId}");
			}

			return $userId;
		}

		/** @inheritDoc */
		public function authenticateByRequestParams() {
			$login = getRequest('u-login');
			$password = getRequest('u-password');

			try {
				return $this->authenticate($login, $password);
			} catch (AuthenticationException $exception) {
				$hash = getRequest('u-password-md5') ?: getRequest('u-password-hash');
				$hash = $this->isUmiManagerHash($hash) ? '' : $hash;
	
				try {
					return $this->authenticateByLoginAndHash($login, $hash);
				} catch (AuthenticationException $exception) {
					throw new WrongCredentialsException($exception->getMessage());
				}
			}
		}

		/** @inheritDoc */
		public function authenticateByHeaders() {
			$login = getServer('u-login');
			$password = getServer('u-password');

			return $this->authenticate($login, $password);
		}

		/** @inheritDoc */
		public function authenticateByHttpBasic() {
			$login = getServer('PHP_AUTH_USER');
			$password = getServer('PHP_AUTH_PW');

			try {
				return $this->authenticate($login, $password);
			} catch (WrongCredentialsException $exception) {
				throw new WrongCredentialsException($exception->getMessage());
			} catch (AuthenticationException $exception) {
				throw new HttpAuthenticationException($exception->getMessage());
			}
		}

		/** @inheritDoc */
		public function authenticateByUmiHttpBasic() {
			$rawAuthenticationParams = getRequest('umi_authorization');
			$authenticationParams = explode(':', base64_decode(mb_substr($rawAuthenticationParams, 6)));

			if (umiCount($authenticationParams) != 2) {
				throw new WrongCredentialsException('Cannot parse umi_authorization param');
			}

			list($login, $password) = $authenticationParams;

			return $this->authenticate($login, $password);
		}

		/** @inheritDoc */
		public function authenticateBySession() {
			$userId = $this->getSession()
				->get('user_id');
			return $this->authenticateByUserId($userId);
		}

		/** @inheritDoc */
		public function authenticateFakeUser($userId) {
			$this->validateUserId($userId);
			$validId = $this->getAuthenticationRulesFactory()
				->createByFakeUser($userId)
				->validate();

			if ($validId === false) {
				throw new AuthenticationException("Cannot authenticate fake user by id = {$userId}");
			}

			return $validId;
		}

		/** @inheritDoc */
		public function authenticateByPreviousUserId() {
			$session = $this->getSession();

			if (!$session->get('fake-user')) {
				throw new WrongCredentialsException('fake-user flag expected for authenticate by previous user id');
			}

			$userId = $session->get('old_user_id');

			return $this->authenticateByUserId($userId);
		}

		/** @inheritDoc */
		public function authenticateByLoginAndToken() {
			$cookieJar = $this->getCookieJar();
			$loginAndToken = $cookieJar->getDecrypted('umi-auth-token');

			$authenticationParams = explode(':', $loginAndToken);

			if (umiCount($authenticationParams) != 2) {
				throw new WrongCredentialsException('Cannot parse login and token param');
			}

			list($login, $token) = $authenticationParams;

			$userId = $this->getAuthenticationRulesFactory()
				->createByLoginAndToken($login, $token)
				->validate();

			return $userId;
		}

		/**
		 * Валидирует идентификатор пользователя
		 * @param int $userId идентификатор пользователя
		 * @throws WrongCredentialsException
		 */
		private function validateUserId($userId) {
			if (!is_int($userId) || $userId <= 0) {
				throw new WrongCredentialsException('Wrong user id given, integer > 0 expected');
			}
		}

		/**
		 * Валидирует логин пользователя
		 * @param string $login логин
		 * @throws WrongCredentialsException
		 */
		private function validateLogin($login) {
			$this->validateNotEmptyString($login, 'login');
		}

		/**
		 * Валидирует пароль пользователя
		 * @param string $password пароль
		 * @throws WrongCredentialsException
		 */
		private function validatePassword($password) {
			$this->validateNotEmptyString($password, 'password');
		}

		/**
		 * Валидирует название провайдера данных пользователя (социальной сети)
		 * @param string $provider название провайдера данных пользователя (социальной сети)
		 * @throws WrongCredentialsException
		 */
		private function validateProvider($provider) {
			$this->validateNotEmptyString($provider, 'provider');
		}

		/**
		 * Валидирует код активации пользователя
		 * @param string $code код активации пользователя
		 * @throws WrongCredentialsException
		 */
		private function validateCode($code) {
			$this->validateNotEmptyString($code, 'activation code');
		}

		/**
		 * Валидирует идентификатор пользователя в социальной сети
		 * @param string $uid идентификатор пользователя в социальной сети
		 * @throws WrongCredentialsException
		 */
		private function validateUid($uid) {
			$this->validateNotEmptyString($uid, 'social uid');
		}

		/**
		 * Валидирует значение параметра, оно должно содежать непустую строку
		 * @param string $string значение параметра
		 * @param string $name валидируемые название параметра
		 * @throws WrongCredentialsException
		 */
		private function validateNotEmptyString($string, $name) {
			if (!is_string($string) || $string === '') {
				throw new WrongCredentialsException("Wrong user {$name} given, not empty string expected");
			}
		}

		/**
		 * Возвращает фабрику правил аутентификации
		 * @return AuthenticationRules\iFactory
		 */
		private function getAuthenticationRulesFactory() {
			return $this->authenticationRulesFactory;
		}

		/**
		 * Возвращает контейнер сессии
		 * @return iSession
		 */
		private function getSession() {
			return $this->session;
		}

		/**
		 * Проверяет является ли хэш хэшем из
		 * мобильного приложения UMI.Manager
		 * @param string $hash
		 * @return bool
		 */
		private function isUmiManagerHash($hash) {
			return $hash === 'null';
		}

		/**
		 * Возвращает класс для работы с куками
		 * @return Cookies\iCookieJar
		 */
		private function getCookieJar() {
			return $this->cookieJar;
		}
	}
