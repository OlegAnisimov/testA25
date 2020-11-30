<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\Classes\System\Utils\Stub\iFacade as iStubFacade;

	/**
	 * Интерфейс посредника в показе заглушки
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	interface iStub {

		/**
		 * Устанавливает фасад заглушки
		 * @param iStubFacade $stubFacade фасад заглушки
		 * @return $this
		 */
		public function setStubFacade(iStubFacade $stubFacade);

		/**
		 * Показывает страницу-заглушку, если это необходимо
		 * @throws \coreException
		 * @throws \selectorException
		 */
		public function showStubPageIfRequired();
	}