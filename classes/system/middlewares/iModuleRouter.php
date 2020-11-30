<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use \iCmsController as iRouter;
	use UmiCms\System\Events\iEventPointFactory;

	/**
	 * Интерфейс посредника в маршрутизации запросов к модулям
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	interface iModuleRouter {

		/**
		 * Устанавливает маршрутизатор модулей
		 * @param iRouter $moduleRouter маршрутизатор модулей
		 * @return $this
		 */
		public function setModuleRouter(iRouter $moduleRouter);

		/**
		 * Устанавливает фабрику событий
		 * @param iEventPointFactory $eventPointFactory фабрика событий
		 * @return $this
		 */
		public function setEventPointFactory(iEventPointFactory $eventPointFactory);

		/** Анализирует запрос к модулям */
		public function analyzeModuleRequest();

		/**
		 * Выполняет запрос к модулями
		 * @param bool $handleCallStack поддерживается ли получение стека вызовов
		 * @return string
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \publicException
		 */
		public function executeModuleRequest($handleCallStack = true);
	}