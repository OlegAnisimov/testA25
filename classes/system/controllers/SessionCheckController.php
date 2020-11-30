<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\System\Auth\iAuth;
	use UmiCms\System\Session\iSession;

	/**
	 * Класс контроллера проверки сессии
	 * @package UmiCms\Classes\System\Controllers
	 */
	class SessionCheckController extends AbstractController implements iSessionCheckController {

		/** @var iSession $session сессия */
		private $session;

		/** @var iAuth $auth фасад аутентификации и авторизации */
		private $auth;

		/** @inheritDoc */
		public function setSession(iSession $session) {
			$this->session = $session;
			return $this;
		}

		/** @inheritDoc */
		public function setAuth(iAuth $auth) {
			$this->auth = $auth;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			$isPingAction = $this->request->Get()->get('a') === 'ping';
			$successfulAuth = false;

			if ($isPingAction) {
				$successfulAuth = $this->ping();
			}

			$sessionRemainingTime = $this->getRemainingTime($successfulAuth);
			$this->buffer->push($sessionRemainingTime);
			$this->buffer->end();
		}

		/**
		 * Продлевает время жизни сессии
		 * @return bool была ли произведена успешная авторизация
		 */
		private function ping() {
			$this->session->startActiveTime();
			$get = $this->request->Get();

			if ($get->get('u-login') && $get->get('u-password')) {
				try {
					$this->auth->loginByEnvironment();
					return $this->auth->isAuthorized();
				} catch (\Exception $exception) {
					//nothing
				}
			}

			return false;
		}

		/**
		 * Возвращает время жизни сессии
		 * @param bool $successfulAuth была ли произведена успешная авторизация
		 * @return float|int|string
		 */
		private function getRemainingTime($successfulAuth) {
			$expiredSessionLifeTime = '-1';

			switch (true) {
				case ($this->session->isActiveTimeExpired() === false) : {
					$sessionRemainingTime = $this->session->getActiveTime();
					break;
				}
				case ($successfulAuth) : {
					$sessionRemainingTime = $this->session->getMaxActiveTime() * $this->session::SECONDS_IN_ONE_MINUTE;
					break;
				}
				default : {
					$this->session->clear();
					$sessionRemainingTime = $expiredSessionLifeTime;
				}
			}

			return $sessionRemainingTime;
		}
	}