<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\Classes\System\MobileApp\UmiManager\iChecker;

	/**
	 * Интерфейс посредника в запросах к api мобильного приложения "UMI.Manager"
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	interface iUmiManager {

		/**
		 * Устанавливает валидатор системы для запросов от приложения "UMI.Manager"
		 * @param iChecker $checker валидатор системы для запросов от приложения "UMI.Manager"
		 * @return $this
		 */
		public function setChecker(iChecker $checker);

		/**
		 * Валидирует запрос от приложения "UMI.Manager"
		 * @throws \ErrorException
		 * @throws \publicException
		 */
		public function validateUmiManagerRequest();
	}