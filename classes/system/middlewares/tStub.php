<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\Classes\System\Controllers\AbstractController;
	use UmiCms\Classes\System\Utils\Stub\iFacade as iStubFacade;

	/**
	 * Трейт посредника в показе заглушки
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tStub {

		/** @var iStubFacade $stubFacade фасад заглушки */
		protected $stubFacade;

		/** @inheritDoc */
		public function setStubFacade(iStubFacade $stubFacade) {
			$this->stubFacade = $stubFacade;
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \coreException
		 * @throws \selectorException
		 */
		public function showStubPageIfRequired() {
			/** @var AbstractController $this */
			$ip = $this->getRequest()->remoteAddress();

			if (!$this->stubFacade->isEnabled($ip)) {
				return;
			}

			$stub = $this->stubFacade->getContent();

			/** @var AbstractController $this */
			$buffer = $this->getBuffer();
			$buffer->contentType('text/html');
			$buffer->charset('utf-8');
			$buffer->push($stub);
			$buffer->end();
		}
	}