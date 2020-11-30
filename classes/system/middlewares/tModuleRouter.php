<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use \iCmsController as iModuleRouter;
	use UmiCms\Classes\System\Controllers\AbstractController;
	use UmiCms\System\Events\iEventPointFactory;

	/**
	 * Трейт посредника в маршрутизации запросов к модулям
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tModuleRouter {

		/** @var iModuleRouter $moduleRouter фасад роутера модулей */
		protected $moduleRouter;

		/** @var iEventPointFactory $eventPointFactory фабрика событий */
		protected $eventPointFactory;

		/** @inheritDoc */
		public function setModuleRouter(iModuleRouter $moduleRouter) {
			$this->moduleRouter = $moduleRouter;
			return $this;
		}

		/** @inheritDoc */
		public function setEventPointFactory(iEventPointFactory $eventPointFactory) {
			$this->eventPointFactory = $eventPointFactory;
			return $this;
		}

		/** @inheritDoc */
		public function analyzeModuleRequest() {
			$this->moduleRouter::doSomething();
			$this->moduleRouter->calculateRefererUri();

			$eventPoint = $this->eventPointFactory->create('routing', 'before');
			$eventPoint->setParam('router', $this->moduleRouter);
			$eventPoint->call();

			/** @var iModuleRouter $moduleRouter */
			$moduleRouter = $eventPoint->getParam('router');

			if (!is_object($moduleRouter) || !is_callable([$moduleRouter, 'analyzePath'])) {
				trigger_error('Custom router must have analyzePath method, system running with default router.', E_USER_WARNING);
				$this->moduleRouter->analyzePath();
			} else {
				$moduleRouter->analyzePath();
			}

			$eventPoint->setMode('after');
			$eventPoint->call();
		}

		/** @inheritDoc */
		public function executeModuleRequest($handleCallStack = true) {
			$globalVariables = $this->moduleRouter->getGlobalVariables();
			$templateEngine = $this->moduleRouter->getCurrentTemplater();
			/** @var AbstractController $this */
			$request = $this->getRequest();

			if ($request->isStreamCallStack()) {
				$templateEngine::setEnabledCallStack($handleCallStack);
			}

			$templatesSource = $templateEngine->getTemplatesSource();
			/** @noinspection PhpMethodParametersCountMismatchInspection (параметры берутся через func_get_args()) */
			list($commonTemplate) = $templateEngine::getTemplates($templatesSource, 'common');

			if ($this->moduleRouter->getCurrentElementId()) {
				$templateEngine->setScope($this->moduleRouter->getCurrentElementId());
			}

			$result = $templateEngine->parse($globalVariables, $commonTemplate);

			/** @var AbstractController $this */
			if ($request->isNotAdmin()) {
				$result = $templateEngine->cleanup($result);
			}

			return $request->isStreamCallStack() ? $templateEngine->getCallStackXML() : $result;
		}
	}