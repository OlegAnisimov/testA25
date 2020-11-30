<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use UmiCms\System\Session\iSession;

	/**
	 * Интерфейс агента пагинации административной панели
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	interface iAdmin extends iCommon {

		/**
		 * Устанавливает контейнер сессии
		 * @param iSession $sessionContainer контейнер сессии
		 * @return iAdmin
		 */
		public function setSessionContainer(iSession $sessionContainer) : iAdmin;
	}