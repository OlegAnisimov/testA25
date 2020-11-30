<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iUmiCaptcha as iCaptchaFacade;
	use \UmiCms\System\Session\iSession;

	/**
	 * Интерфейс контроллера каптчи
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iCaptchaController extends iController {

		/**
		 * Устанавливает сессию
		 * @param iSession $session сессия
		 * @return $this
		 */
		public function setSession(iSession $session);

		/**
		 * Устанавливает генератор каптчи
		 * @param iCaptchaFacade $captchaFacade фасад каптчи
		 * @return $this
		 * @throws \coreException
		 */
		public function setGenerator(iCaptchaFacade $captchaFacade);
	}