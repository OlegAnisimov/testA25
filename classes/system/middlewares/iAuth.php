<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\System\Auth\iAuth as iFacade;

	/** Интерфейс посредника в авторизации */
	interface iAuth {

		/**
		 * Устанавливает фасад аутентификации и авторизации
		 * @param iFacade $auth фасад аутентификации и авторизации
		 * @return $this
		 */
		public function setAuth(iFacade $auth);

		/** Авторизует пользователя */
		public function loginByEnvironment();
	}