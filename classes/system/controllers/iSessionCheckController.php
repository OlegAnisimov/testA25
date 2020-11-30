<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\System\Session\iSession;
	use UmiCms\System\Auth\iAuth as iFacade;

	/**
	 * Интерфейс контроллера проверки сессии
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iSessionCheckController extends iController {

		/**
		 * Устанавливает сессию
		 * @param iSession $session сессия
		 * @return $this
		 */
		public function setSession(iSession $session);

		/**
		 * Устанавливает фасад аутентификации и авторизации
		 * @param iFacade $auth фасад аутентификации и авторизации
		 * @return $this
		 */
		public function setAuth(iFacade $auth);
	}