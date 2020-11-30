<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\Classes\System\MobileApp\UmiManager\iChecker;
	use UmiCms\Classes\System\Controllers\AbstractController;

	/**
	 * Трейт посредника в запросах к api мобильного приложения "UMI.Manager"
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tUmiManager {

		/** @var iChecker $checker валидатор системы для запросов от приложения "UMI.Manager" */
		protected $checker;

		/** @inheritDoc */
		public function setChecker(iChecker $checker) {
			$this->checker = $checker;
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \ErrorException
		 * @throws \publicException
		 */
		public function validateUmiManagerRequest() {
			/** @var AbstractController $this */
			if ($this->getRequest()->isUmiManager()) {
				$this->checker->checkRequiredModules();
			}
		}
	}