<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use \iConfiguration as iConfig;
	use \UmiCms\Classes\System\PageNum\iAgent;
	use \umiExceptionHandler as iExceptionHandler;
	use \UmiCms\System\Request\iFacade as iRequest;

	/**
	 * Класс фасада агентов пагинации
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	class Facade implements iFacade {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iFactory $factory фабрика агентов */
		private $factory;

		/** @inheritDoc */
		public function __construct(iConfig $config, iFactory $factory) {
			$this->config = $config;
			$this->factory = $factory;
		}

		/** @inheritDoc */
		public function getAgent(iRequest $request) : iAgent {
			switch (true) {
				case $request->isAdmin() : {
					return $this->factory->createAdmin();
				}
				case $request->isStream() : {
					return $this->factory->createStream();
				}
				case $request->isSite() : {
					return $this->createSite();
				}
				default : {
					return $this->factory->createCommon();
				}
			}
		}

		/**
		 * Создает агент пагинации сайта
		 * @return iAgent
		 * @throws \Exception
		 */
		private function createSite() : iAgent {
			$customClass = (string) $this->config->get('page-navigation', 'site-agent-class');

			if ($customClass) {
				try {
					return $this->factory->createCustom($customClass);
				} catch (\Exception $exception) {
					iExceptionHandler::report($exception);
				}
			}

			return $this->factory->createSite();
		}
	}