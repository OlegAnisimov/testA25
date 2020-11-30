<?php

	namespace UmiCms\System\Auth;

	use UmiCms\Service;
	use UmiCms\System\Cookies;
	use UmiCms\System\Protection;
	use UmiCms\System\Session\iSession;
	use UmiCms\System\Auth\PasswordHash\iAlgorithm;
	use UmiCms\System\Auth\PasswordHash\WrongAlgorithmException;

	/**
	 * Класс авторизации
	 * @package UmiCms\System\Auth
	 */
	class Authorization implements iAuthorization {

		/**
		 * @const string UMI_CMS_SESSION_COOKIE_NAME имя куки сессии UMI.CMS.
		 * Кука используется для настройки статического кеширования через nginx.
		 */
		const UMI_CMS_SESSION_COOKIE_NAME = 'umicms_session';

		/** @var int|null $authorizedUserId идентификатор авторизованного пользователя */
		private $authorizedUserId;

		/** @var iSession $session контейнер сессии */
		private $session;

		/** @var Protection\CsrfProtection $tokenGenerator генератор csrf токенов */
		private $tokenGenerator;

		/** @var \iPermissionsCollection $permissionsCollection коллекция прав доступа */
		private $permissionsCollection;

		/** @var Cookies\iCookieJar $cookieJar класс для работы с куками */
		private $cookieJar;

		/** @var \iUmiObjectsCollection коллекция объектов */
		private $umiObjects;

		/** @var \iConfiguration конфиг */
		private $configuration;

		/** @var iAlgorithm $hashAlgorithm алгоритм хеширования */
		private $hashAlgorithm;

		/** @inheritDoc */
		public function __construct(
			iSession $session,
			Protection\CsrfProtection $tokenGenerator,
			\iPermissionsCollection $permissionsCollection,
			Cookies\iCookieJar $cookieJar,
			\iUmiObjectsCollection $umiObjectsCollection,
			\iConfiguration $configuration,
			iAlgorithm $algorithm
		) {
			$this->session = $session;
			$this->tokenGenerator = $tokenGenerator;
			$this->permissionsCollection = $permissionsCollection;
			$this->cookieJar = $cookieJar;
			$this->umiObjects = $umiObjectsCollection;
			$this->configuration = $configuration;
			$this->hashAlgorithm = $algorithm;
		}

		/** @inheritDoc */
		public function authorize($userId) {
			$this->authorizeStateless($userId);
			$this->startSession($userId);
			$this->setCookieToken($userId);
			return $this;
		}

		/** @inheritDoc */
		public function authorizeStateless($userId) {
			$this->validateUserId($userId);
			$this->setAuthorizedUserId($userId);
			return $this;
		}

		/** @inheritDoc */
		public function getAuthorizedUserId() {
			return $this->authorizedUserId;
		}

		/** @inheritDoc */
		public function deAuthorize() {
			$this->deAuthorizeStateless();
			$this->stopSession();
			$this->removeCookieToken();
			return $this;
		}

		/** @inheritDoc */
		public function deAuthorizeStateless() {
			$this->setAuthorizedUserId(null);
			return $this;
		}

		/** @inheritDoc */
		public function authorizeUsingFixedSessionId($userId) {
			$this->authorizeStateless($userId);
			$this->startSession($userId, false);
			return $this;
		}

		/** @inheritDoc */
		public function authorizeFakeUser($userId) {
			$this->validateUserId($userId);

			$previousUserId = $this->getAuthorizedUserId();
			$this->deAuthorize();

			$cookieJar = $this->getCookieJar();
			$user = $this->getObjectCollection()
				->getObject($userId);

			switch ($user->getTypeGUID()) {
				case 'users-user': {
					$this->authorize($userId);
					$cookieJar->remove('customer-id');
					break;
				}
				case 'emarket-customer': {
					$expiration = (int) $this->getConfiguration()
						->get('modules', 'emarket.customer-expiration-time');
					$cookieJar->setEncrypted('customer-id', $userId, time() + $expiration);
					break;
				}
			}

			$this->savePreviousUserId($previousUserId);
			return $this;
		}

		/**
		 * Сохраняет идентификатор предыдущего пользователя
		 * @param int $userId идентификатор предыдущего пользователя
		 */
		private function savePreviousUserId($userId) {
			$session = $this->getSession();
			$session->set('fake-user', true);
			$session->set('old_user_id', $userId);
		}

		/** @inheritDoc */
		public function authorizeUsingPreviousUserId($previousUserId) {
			$this->validateUserId($previousUserId);
			$this->deAuthorize();
			$this->authorize($previousUserId);
			$this->getCookieJar()->remove('customer-id');
			return $this;
		}

		/**
		 * Начинает сессию пользователя, неявно отправляет авторизационные куки.
		 * @param int $userId идентификатор пользователя
		 * @param bool $makeNewId необходимо ли назначить сессии новый идентификатор
		 * @throws \wrongParamException
		 */
		private function startSession($userId, $makeNewId = true) {
			$session = $this->getSession();

			if ($makeNewId) {
				$session->changeId();
			}

			$token = $this->getTokenGenerator()
				->generateToken();
			$userIsSv = $this->getPermissionsCollection()
				->isSv($userId);

			$session->set('user_id', $userId);
			$session->set('csrf_token', $token);
			$session->set('user_is_sv', $userIsSv);
			$session->startActiveTime();

			$this->getCookieJar()->set(self::UMI_CMS_SESSION_COOKIE_NAME, 1,
				time() + $session->getCookieLifeTime());
		}

		/** Останавливает сессию пользователя */
		private function stopSession() {
			$session = $this->getSession();
			$cookieJar = $this->getCookieJar();

			$sessionName = $session->getName();
			$cookieJar->remove($sessionName);

			if ($session->get('fake-user')) {
				$cookieJar->remove('customer-id');
			}

			$session->clear();

			$cookieJar->remove(self::UMI_CMS_SESSION_COOKIE_NAME);
		}

		/**
		 * Устанавливает куку c токеном авторизации если это необходимо
		 * @param int $userId идентификатор пользователя
		 */
		private function setCookieToken($userId) {
			$request = Service::Request();
			$saveAuthToken = $request->Post()->get('save-auth-token');

			if (!$saveAuthToken) {
				return;
			}

			$session = $this->getSession();
			$user = $this->getObjectCollection()
				->getObject($userId);
			$login = $user->getValue('login');

			$hash = uniqid();
			$cookieJar = $this->getCookieJar();
			$cookieJar->setEncrypted('umi-auth-token', $login . ':' . $hash, time() + $session->getCookieLifeTime());

			$algorithm = $this->getHashAlgorithm();
			$remoteAddress = $request->remoteAddress();
			$userAgent = $request->userAgent();
			$token = $algorithm::hash($hash . $remoteAddress . $userAgent);

			$user->setValue('auth_token', $token);
			$user->commit();
		}

		/** Удаляет куки с токеном авторизации */
		private function removeCookieToken() {
			$cookieJar = $this->getCookieJar();
			$cookieJar->remove('umi-auth-token');
		}

		/**
		 * Валидирует идентификатор пользователя
		 * @param int $userId идентификатор пользователя
		 * @throws AuthorizationException
		 */
		private function validateUserId($userId) {
			if (!is_int($userId) || $userId <= 0) {
				throw new AuthorizationException('Wrong user id given, integer > 0 expected');
			}
		}

		/**
		 * Устанавливает идентификатор авторизованного пользователя
		 * @param int $userId идентификатор пользователя
		 * @return $this
		 */
		private function setAuthorizedUserId($userId) {
			$this->authorizedUserId = $userId;
			return $this;
		}

		/**
		 * Возвращает контейнер сессии
		 * @return iSession
		 */
		private function getSession() {
			return $this->session;
		}

		/**
		 * Возвращает генератор csrf токенов
		 * @return Protection\CsrfProtection
		 */
		private function getTokenGenerator() {
			return $this->tokenGenerator;
		}

		/**
		 * Возвращает коллекцию прав доступа
		 * @return \iPermissionsCollection
		 */
		private function getPermissionsCollection() {
			return $this->permissionsCollection;
		}

		/**
		 * Возвращает класс для работы с куками
		 * @return Cookies\iCookieJar
		 */
		private function getCookieJar() {
			return $this->cookieJar;
		}

		/**
		 * Возвращает коллекцию объектов
		 * @return \iUmiObjectsCollection
		 */
		private function getObjectCollection() {
			return $this->umiObjects;
		}

		/**
		 * Возвращает конфигурацию системы
		 * @return \iConfiguration
		 */
		private function getConfiguration() {
			return $this->configuration;
		}

		/**
		 * Возвращает алгоритм хеширования
		 * @return iAlgorithm
		 */
		private function getHashAlgorithm() {
			return $this->hashAlgorithm;
		}
	}
