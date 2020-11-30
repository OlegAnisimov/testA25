<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\MiddleWares;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Класс контроллера запроса xml данных
	 * @package UmiCms\Classes\System\Controllers
	 */
	class XmlForceController extends AbstractController implements iXmlForceController {

		use MiddleWares\tAuth;
		use MiddleWares\tUmiManager;
		use MiddleWares\tStub;
		use MiddleWares\tUmapRouter;
		use MiddleWares\tMirrorHandler;
		use MiddleWares\tModuleRouter;

		/** @var iConfig $config конфигурация */
		protected $config;

		/** @var iTranslator $translator транслятор */
		protected $translator;

		/** @inheritDoc */
		public function setConfig(iConfig $config) {
			$this->config = $config;
			return $this;
		}

		/** @inheritDoc */
		public function setTranslator(iTranslator $translator) {
			$this->translator = $translator;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!$this->isEnabled()) {
				$this->moduleRouter->setNotFoundState();
				$handleCallStack = !$this->config->get('debug', 'callstack.disabled');
				$result = $this->executeModuleRequest($handleCallStack);

				if ($this->request->isStreamCallStack()) {
					$this->response->printXmlAsString($result);
				}

				$this->response->printHtml($result, '404 Not Found');
			}

			$this->loginByEnvironment();
			$this->validateUmiManagerRequest();
			$this->showStubPageIfRequired();
			$this->executeUmap();
			$this->analyzeModuleRequest();
			$this->handleRequestFromMirror();
			$this->handleRequest();
		}

		/**
		 * Определяет доступно ли получение пользовательских данных
		 * @return bool
		 */
		protected function isEnabled() {
			return $this->request->isAdmin() || (bool) $this->config->get('router', 'xmlForce.enabled');
		}

		/**
		 * Перехватывает запрос с зеркала
		 * @throws \coreException
		 */
		protected function handleRequestFromMirror() {
			$mode = (int) $this->config->get('seo', 'primary-domain-redirect');
			$this->checkMirror($mode);
		}

		/**
		 * Обрабатывает xml-запрос
		 * @throws \ErrorException
		 * @throws \coreException
		 */
		protected function handleRequest() {
			\def_module::isXSLTResultMode(true);
			$globalVariables = $this->moduleRouter->getGlobalVariables();
			$xml = $this->translator->translateToXml($globalVariables);

			$this->buffer->contentType('text/xml');
			$this->buffer->push($xml);
			$this->buffer->option('generation-time', true);
			$this->buffer->end();
		}
	}