<?php

	namespace UmiCms\System\Auth;

	use UmiCms\System\Permissions\iSystemUsersPermissions;

	/**
	 * Фасад для работы с аутентификацией и авторизацией пользователя
	 * @package UmiCms\System\Auth
	 */
	class Auth implements iAuth {

		/** @var iAuthentication $authentication аутентификация */
		private $authentication;

		/** @var iAuthorization $authorization авторизация */
		private $authorization;

		/** @var iSystemUsersPermissions $systemUserPermissions класс прав системных пользователей */
		private $systemUserPermissions;

		/** @var \iUmiObjectsCollection коллекция объектов */
		private $umiObjects;

		/** @inheritDoc */
		public function __construct(
			iAuthentication $authentication,
			iAuthorization $authorization,
			iSystemUsersPermissions $systemUsersPermissions,
			\iUmiObjectsCollection $umiObjectsCollection
		) {
			$this->authentication = $authentication;
			$this->authorization = $authorization;
			$this->systemUserPermissions = $systemUsersPermissions;
			$this->umiObjects = $umiObjectsCollection;
		}

		/** @inheritDoc */
		public function checkLogin($login, $password) {
			try {
				$userId = $this->getAuthentication()
					->authenticate($login, $password);
			} catch (AuthenticationException $e) {
				return false;
			}

			return $userId;
		}

		/** @inheritDoc */
		public function checkCode($code) {
			try {
				$userId = $this->getAuthentication()
					->authenticateByCode($code);
			} catch (AuthenticationException $e) {
				return false;
			}

			return $userId;
		}

		/** @inheritDoc */
		public function login($login, $password) {
			try {
				$userId = $this->getAuthentication()
					->authenticate($login, $password);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginUsingId($userId) {
			try {
				$userId = $this->getAuthentication()
					->authenticateByUserId($userId);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginOnce($userId) {
			try {
				$userId = $this->getAuthentication()
					->authenticateByUserId($userId);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorizeStateless($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginUsingCode($code) {
			try {
				$userId = $this->getAuthentication()
					->authenticateByCode($code);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginBySocials($uid, $provider, $service = null) {
			try {
				$userId = $this->getAuthentication()
					->authenticateBySocials($uid, $provider, $service);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/**
		 *
		 * @param int $userId ИД пользователя или гостя-покупателя
		 * @return bool
		 */
		public function loginAsFakeUser($userId) {
			try {
				$userId = $this->getAuthentication()
					->authenticateFakeUser($userId);
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorizeFakeUser($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginUsingPreviousUserId() {
			try {
				$userId = $this->getAuthentication()
					->authenticateByPreviousUserId();
			} catch (AuthenticationException $e) {
				return false;
			}

			try {
				$this->getAuthorization()
					->authorizeUsingPreviousUserId($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function isLoginAsGuest() {
			return $this->getUserId() == $this->getSystemUsersPermissions()
					->getGuestUserId();
		}

		/** @inheritDoc */
		public function isLoginAsSv() {
			$systemUserPermissions = $this->getSystemUsersPermissions();
			$userId = $this->getUserId();

			if ($userId == $systemUserPermissions->getSvUserId()) {
				return true;
			}

			$user = $this->umiObjects->getObject($userId);

			if (!$user instanceof \iUmiObject || $user->getTypeGUID() !== 'users-user') {
				return false;
			}
			
			return in_array($systemUserPermissions->getSvGroupId(), (array) $user->getValue('groups'));
		}

		/** @inheritDoc */
		public function isAuthorized() {
			return !$this->isLoginAsGuest();
		}

		/** @inheritDoc */
		public function loginAsGuest() {
			$userId = $this->getSystemUsersPermissions()
				->getGuestUserId();

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function loginAsSv() {
			$userId = $this->getSystemUsersPermissions()
				->getSvUserId();

			try {
				$this->getAuthorization()
					->authorize($userId);
			} catch (AuthorizationException $e) {
				return false;
			}

			return true;
		}

		/** @inheritDoc */
		public function getUserId() {
			$authorizedUserId = $this->getAuthorization()
				->getAuthorizedUserId();

			if ($authorizedUserId !== null) {
				return (int) $authorizedUserId;
			}

			return $this->getSystemUsersPermissions()
				->getGuestUserId();
		}

		/** @inheritDoc */
		public function logout() {
			$this->getAuthorization()
				->deAuthorize();
			return $this->loginAsGuestOnce();
		}

		/** @inheritDoc */
		public function logoutOnce() {
			$this->getAuthorization()
				->deAuthorizeStateless();
			return $this->loginAsGuestOnce();
		}

		/** @inheritDoc */
		public function loginByEnvironment() {
			$authorization = $this->getAuthorization();
			$userId = $this->authenticateByHttpAuth();

			if ($userId !== false) {
				$authorization->authorizeUsingFixedSessionId($userId);
				return;
			}

			if (defined('PRE_AUTH_ENABLED') && PRE_AUTH_ENABLED) {
				$userId = $this->authenticateByRequest();

				if ($userId !== false) {
					$authorization->authorize($userId);
					return;
				}
			}

			$userId = $this->authenticateBySession();

			if ($userId !== false) {
				$authorization->authorizeStateless($userId);
				return;
			}

			$userId = $this->authenticateByLoginAndToken();

			if ($userId !== false) {
				$authorization->authorize($userId);
				return;
			}

			$guestId = $this->getSystemUsersPermissions()
				->getGuestUserId();
			$authorization->authorizeStateless($guestId);
		}

		/**
		 * Авторизует пользователя с гостевыми правами без сохранения состояния в сессию и куки
		 * @return bool
		 */
		private function loginAsGuestOnce() {
			$guestId = $this->getSystemUsersPermissions()
				->getGuestUserId();
			return $this->loginOnce($guestId);
		}

		/**
		 * Пытается аутентифицировать пользователя на основе данных http авторизации
		 * @return bool|int идентификатор пользователя или false, если его не удалось определить
		 * @throws AuthenticationException
		 */
		private function authenticateByHttpAuth() {
			try {
				$userId = $this->getAuthentication()
					->authenticateByHttpBasic();
			} catch (WrongCredentialsException $e) {
				$userId = false;
			}

			if ($userId !== false) {
				return $userId;
			}

			try {
				$userId = $this->getAuthentication()
					->authenticateByUmiHttpBasic();
			} catch (WrongCredentialsException $e) {
				$userId = false;
			}

			return $userId;
		}

		/**
		 * Пытается аутентифицировать пользователя на основе данных запроса
		 * @return bool|int идентификатор пользователя или false, если его не удалось определить
		 * @throws AuthenticationException
		 */
		private function authenticateByRequest() {
			try {
				$userId = $this->getAuthentication()
					->authenticateByRequestParams();
			} catch (WrongCredentialsException $e) {
				$userId = false;
			}

			if ($userId !== false) {
				return $userId;
			}

			try {
				$userId = $this->getAuthentication()
					->authenticateByHeaders();
			} catch (WrongCredentialsException $e) {
				$userId = false;
			}

			return $userId;
		}

		/**
		 * Пытается аутентифицировать пользователя на основе данных текущей сессии
		 * @return bool|int идентификатор пользователя или false, если его не удалось определить
		 */
		private function authenticateBySession() {
			try {
				$userId = $this->getAuthentication()
					->authenticateBySession();
			} catch (AuthenticationException $e) {
				$userId = false;
			}

			return $userId;
		}

		/**
		 * Пытается аутентифицировать пользователя на основе данных токена
		 * @return bool|int идентификатор пользователя или false, если его не удалось определить
		 */
		private function authenticateByLoginAndToken() {
			try {
				$userId = $this->getAuthentication()
					->authenticateByLoginAndToken();
			} catch (WrongCredentialsException $e) {
				$userId = false;
			}

			return $userId;
		}

		/**
		 * Возвращает класс аутентификации
		 * @return iAuthentication
		 */
		private function getAuthentication() {
			return $this->authentication;
		}

		/**
		 * Возвращает класс авторизации
		 * @return iAuthorization
		 */
		private function getAuthorization() {
			return $this->authorization;
		}

		/**
		 * Возвращает класс прав системных пользователей
		 * @return iSystemUsersPermissions
		 */
		private function getSystemUsersPermissions() {
			return $this->systemUserPermissions;
		}
	}
