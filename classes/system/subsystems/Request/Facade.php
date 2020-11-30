<?php

	namespace UmiCms\System\Request;

	use UmiCms\System\Request\Http\iRequest;
	use UmiCms\Utils\Browser\iDetector as BrowserDetector;
	use UmiCms\System\Request\Mode\iDetector as ModeDetector;
	use UmiCms\System\Request\Path\iResolver as PathResolver;
	use UmiCms\Classes\System\PageNum\Agent\iFacade as iPageNumAgentFacade;

	/**
	 * Класс фасада запроса
	 * @package UmiCms\System\Request
	 */
	class Facade implements iFacade {

		/** @var iRequest $request http запрос */
		private $request;

		/** @var BrowserDetector $browserDetector определитель параметров браузера */
		private $browserDetector;

		/** @var ModeDetector $modeDetector определитель режима работы системы */
		private $modeDetector;

		/** @var PathResolver $pathResolver распознаватель обрабатываемого пути */
		private $pathResolver;

		/** @var string|null $queryHash хеш от query */
		private $queryHash;

		/** @var iPageNumAgentFacade $pageNumAgentFacade */
		private $pageNumAgentFacade;

		/** @inheritDoc */
		public function __construct(
			iRequest $request,
			BrowserDetector $browserDetector,
			ModeDetector $modeDetector,
			PathResolver $pathResolver,
			iPageNumAgentFacade $pageNumAgentFacade
		) {
			$this->request = $request;
			$this->browserDetector = $browserDetector;
			$this->modeDetector = $modeDetector;
			$this->pathResolver = $pathResolver;
			$this->pageNumAgentFacade = $pageNumAgentFacade;
		}

		/** @inheritDoc */
		public function Cookies() {
			return $this->getRequest()
				->Cookies();
		}

		/** @inheritDoc */
		public function Server() {
			return $this->getRequest()
				->Server();
		}

		/** @inheritDoc */
		public function Post() {
			return $this->getRequest()
				->Post();
		}

		/** @inheritDoc */
		public function Get() {
			return $this->getRequest()
				->Get();
		}

		/** @inheritDoc */
		public function Files() {
			return $this->getRequest()
				->Files();
		}

		/** @inheritDoc */
		public function getPath() {
			return $this->getPathResolver()
				->get();
		}

		/** @inheritDoc */
		public function pageNumber() : int {
			try {
				return $this->pageNumAgentFacade->getAgent($this)
					->resolve($this->uri());
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return 0;
			}
		}

		/** @inheritDoc */
		public function issetPageNumber() : bool {
			try {
				return $this->pageNumAgentFacade->getAgent($this)
					->issetPageNumber($this->uri());
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return false;
			}
		}

		/** @inheritDoc */
		public function removePageNumber(string $uri) : string {
			try {
				return $this->pageNumAgentFacade->getAgent($this)
					->cleanUrl($uri);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return $uri;
			}
		}

		/** @inheritDoc */
		public function appendPageNumber(string $uri, int $number) : string {
			try {
				return $this->pageNumAgentFacade->getAgent($this)
					->generateUri($uri, $number);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return $uri;
			}
		}

		/** @inheritDoc */
		public function uriWithoutPageNumber() : string {
			return $this->removePageNumber($this->uri());
		}

		/** @inheritDoc */
		public function getCleanPath() {
			$path = $this->getPath();
			$path = $this->removePageNumber($path);
			return $this->deleteFormat($path);
		}

		/** @inheritDoc */
		public function getPathParts() {
			return $this->getPathResolver()
				->getParts();
		}

		/** @inheritDoc */
		public function getCleanPathParts() {
			$pathParts = $this->getPathResolver()
				->getParts();
			$pathParts = array_filter($pathParts, function ($part) {
				return !in_array($part, ['.xml', '.json']);
			});
			$pathParts = array_map(function ($part) {
				return $this->removePageNumber($part);
			}, $pathParts);
			return array_map([$this, 'deleteFormat'], $pathParts);
		}

		/** @inheritDoc */
		public function getFirstPart() : string {
			return (string) getFirstValue($this->getCleanPathParts());
		}

		/** @inheritDoc */
		public function isStream() {
			return $this->Get()->isExist('scheme');
		}

		/** @inheritDoc */
		public function getStreamScheme() {
			return $this->Get()->get('scheme');
		}

		/** @inheritDoc */
		public function isJson() {
			return contains($this->getPath(), '.json') ||  $this->Get()->get('jsonMode') === 'force';
		}

		/** @inheritDoc */
		public function isXml() {
			if ($this->isStream()) {
				return !$this->isJson();
			}

			return contains($this->getPath(), '.xml') || $this->Get()->get('xmlMode') === 'force';
		}

		/** @inheritDoc */
		public function isHtml() {
			return (!$this->isJson() && !$this->isXml());
		}

		/** @inheritDoc */
		public function isMobile() {
			$cookies = $this->Cookies();

			if ($cookies->isExist('is_mobile')) {
				return (bool) $cookies->get('is_mobile');
			}

			$detector = $this->getBrowserDetector();

			try {
				return ($detector->isMobile() || $detector->isTablet());
			} catch (\Exception $e) {
				return false;
			}
		}

		/** @inheritDoc */
		public function isLocal() {
			$saas = $this->Server()->get('SAAS');		
			$isSaas = isset($saas) ? contains($saas, 'umi') : false;

			return ($this->isLocalIp() && $this->isLocalHost()) || $isSaas;
		}

		/** @inheritDoc */
		public function getBrowser() {
			return $this->getBrowserDetector()
				->getBrowser();
		}

		/** @inheritDoc */
		public function getPlatform() {
			return $this->getBrowserDetector()
				->getPlatform();
		}

		/** @inheritDoc */
		public function isRobot() {
			return $this->getBrowserDetector()
				->isRobot();
		}

		/** @inheritDoc */
		public function isStreamCallStack() {
			return (bool) $this->Get()->get('showStreamsCalls');
		}

		/** @inheritDoc */
		public function method() {
			return $this->getRequest()
				->method();
		}

		/** @inheritDoc */
		public function isPost() {
			return $this->getRequest()
				->isPost();
		}

		/** @inheritDoc */
		public function isGet() {
			return $this->getRequest()
				->isGet();
		}

		/** @inheritDoc */
		public function isAdmin() {
			return $this->getModeDetector()
				->isAdmin();
		}

		/** @inheritDoc */
		public function isNotAdmin() {
			return !$this->isAdmin();
		}

		/** @inheritDoc */
		public function isSite() {
			return $this->getModeDetector()
				->isSite();
		}

		/** @inheritDoc */
		public function isCli() {
			return $this->getModeDetector()
				->isCli();
		}

		/** @inheritDoc */
		public function mode() {
			return $this->getModeDetector()
				->detect();
		}

		/** @inheritDoc */
		public function host() {
			return $this->getRequest()
				->host();
		}

		/** @inheritDoc */
		public function isHttps() : bool {
			$server = $this->Server();
			return $server->get('REDIRECT_HTTPS') || $server->get('HTTPS') || $server->get('HTTP_HTTPS') || $server->get('HTTP_X_SSL');
		}

		/** @inheritDoc */
		public function userAgent() {
			return $this->getRequest()
				->userAgent();
		}

		/** @inheritDoc */
		public function remoteAddress() {
			return $this->getRequest()
				->remoteAddress();
		}

		/** @inheritDoc */
		public function referrer() {
			return $this->getRequest()
				->referrer();
		}

		/** @inheritDoc */
		public function origin() : ?string {
			return $this->getRequest()
				->origin();
		}

		/** @inheritDoc */
		public function serverAddress() {
			return $this->getRequest()
				->serverAddress();
		}

		/** @inheritDoc */
		public function uri() {
			return (string) $this->getRequest()
				->uri();
		}

		/** @inheritDoc */
		public function query() {
			return $this->getRequest()
				->query();
		}

		/** @inheritDoc */
		public function time() : int {
			return (int) $this->Server()->get('REQUEST_TIME');
		}

		/** @inheritDoc */
		public function documentRoot() {
			return $this->Server()->get('DOCUMENT_ROOT');
		}

		/** @inheritDoc */
		public function queryHash() {
			if ($this->queryHash !== null) {
				return $this->queryHash;
			}

			$query = '';
			$matches = [];
			$success = preg_match('/([\?|\&][^\/#]*)/', $this->query(), $matches);

			if ($success && isset($matches[0])) {
				$query = $matches[0];
			}

			return $this->queryHash = md5($query);
		}

		/** @inheritDoc */
		public function getRawBody() {
			return $this->getRequest()
				->getRawBody();
		}

		/** @inheritDoc */
		public function isAjax() {
			$ajaxParam = $this->Server()->get('HTTP_X_REQUESTED_WITH');
			return isset($ajaxParam) && strtolower($ajaxParam) === 'xmlhttprequest';
		}

		/** @inheritDoc */
		public function isUmiManager() {
			$mobileRequest = $this->Get()->get('mobile_application') ?: $this->Post()->get('mobile_application');
			return $mobileRequest == 'true';
		}

		/**
		 * Удаляет формат данных из строки
		 * @param string $string строка
		 * @return string
		 */
		private function deleteFormat($string) {
			$string = str_replace('.xml', '', $string);
			$string = str_replace('.json', '', $string);
			return $string;
		}

		/**
		 * Возвращает класс http запроса
		 * @return iRequest
		 */
		private function getRequest() {
			return $this->request;
		}

		/**
		 * Возвращает определитель параметров браузера
		 * @return BrowserDetector
		 */
		private function getBrowserDetector() {
			if (!$this->browserDetector->getUserAgent()) {
				$this->browserDetector->setUserAgent($this->userAgent());
			}

			return $this->browserDetector;
		}

		/**
		 * Возвращает определитель режима работы системы
		 * @return ModeDetector
		 */
		private function getModeDetector() {
			return $this->modeDetector;
		}

		/**
		 * Возвращает распознаватель обрабатываемого пути
		 * @return PathResolver
		 */
		private function getPathResolver() {
			return $this->pathResolver;
		}

		/**
		 * Определяет является ли ip адрес сервера адресом локального сервера
		 * @return bool
		 */
		private function isLocalIp() {
			return contains($this->serverAddress(), '127.0.0.') || contains($this->serverAddress(), '::1');
		}

		/**
		 * Определяет является ли хост локальным
		 * @return bool
		 */
		private function isLocalHost() {
			$isFirstLevelDomain = preg_match('|^[^\.]*$|', $this->host());
			return $isFirstLevelDomain || $this->isLocalDomainZone();
		}

		/**
		 * Определяет является ли доменная зона хоста локальной
		 * @return bool
		 */
		private function isLocalDomainZone() {
			return (bool) preg_match('/\.(loc|local|localhost)$/', $this->host());
		}

		/** @deprecated  */
		public function getPathWithoutFormat() {
			return $this->getCleanPath();
		}

		/** @deprecated  */
		public function getPathPartsWithoutFormat() {
			return $this->getCleanPathParts();
		}
	}
