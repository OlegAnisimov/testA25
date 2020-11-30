<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\System\Auth\iAuth;
	use UmiCms\System\Auth\AuthenticationException;
	use UmiCms\Classes\System\Controllers\AbstractController;

	/**
	 * Трейт посредника в авторизации
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tAuth {

		/** @var iAuth $auth фасад аутентификации и авторизации */
		protected $auth;

		/** @inheritDoc */
		public function setAuth(iAuth $auth) {
			$this->auth = $auth;
			return $this;
		}

		/** @inheritDoc */
		public function loginByEnvironment() {
			try {
				$this->auth->loginByEnvironment();
			} catch (HttpAuthenticationException $exception) {
				/** @var AbstractController $this */
				$buffer = $this->getBuffer();
				$buffer->setHeader('WWW-Authenticate', 'Basic realm="UMI.CMS"');
				$buffer->crash('authenticate_failed', 401);
			}
		}
	}