<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iRegedit as iRegistry;
	use \iUmiHierarchy as iPages;
	use \iConfiguration as iConfig;
	use UmiCms\System\Session\iSession;
	use UmiCms\Classes\System\MiddleWares;
	use \umiPropertiesHelper as iPropertyHelper;
	use UmiCms\System\Cache\Statical\iFacade as iStaticCache;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as iLanguageDetector;

	/**
	 * Класс корневого контроллера.
	 * Обрабатывает запросы к модулям.
	 * @package UmiCms\Classes\System\Controllers
	 */
	class IndexController extends AbstractController implements iIndexController {

		/** @var iPages $pages фасад страниц */
		private $pages;

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iSession $session сессия */
		private $session;

		/** @var iStaticCache $staticCache фасад статического кеша */
		private $staticCache;

		/** @var iPropertyHelper $propertyHelper класс полей объектов */
		private $propertyHelper;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @var iLanguageDetector $languageDetector определитель языка */
		private $languageDetector;

		use MiddleWares\tAuth;
		use MiddleWares\tUmiManager;
		use MiddleWares\tStub;
		use MiddleWares\tUmapRouter;
		use MiddleWares\tModuleRouter;
		use MiddleWares\tMirrorHandler;

		/** @inheritDoc */
		public function setPages(iPages $pages) {
			$this->pages = $pages;
			return $this;
		}

		/** @inheritDoc */
		public function setConfig(iConfig $config) {
			$this->config = $config;
			return $this;
		}

		/** @inheritDoc */
		public function setRegistry(iRegistry $registry) {
			$this->registry = $registry;
			return $this;
		}

		/** @inheritDoc */
		public function setSession(iSession $session) {
			$this->session = $session;
			return $this;
		}

		/** @inheritDoc */
		public function setStaticCache(iStaticCache $staticCache) {
			$this->staticCache = $staticCache;
			return $this;
		}

		/** @inheritDoc */
		public function setPropertyHelper(iPropertyHelper $propertyHelper) {
			$this->propertyHelper = $propertyHelper;
			return $this;
		}

		/** @inheritDoc */
		public function setDomainDetector(iDomainDetector $domainDetector) {
			$this->domainDetector = $domainDetector;
			return $this;
		}

		/** @inheritDoc */
		public function setLanguageDetector(iLanguageDetector $languageDetector) {
			$this->languageDetector = $languageDetector;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$this->redirectIfRequired();
			$this->loginByEnvironment();
			$this->validateUmiManagerRequest();
			$this->initializeSession();
			$this->showStubPageIfRequired();
			$this->executeUmap();
			$this->analyzeModuleRequest();
			$this->deleteRepeatedSlashesInUrl();
			$this->redirectToUrlWithSuffix();
			$this->handleRequestFromMirror();
			$this->handleRequestFromHttp();
			$this->handleRequest();
			$this->saveProperties();
			$this->disableBanners();
			$this->updateStatistics();
			$this->cleanStaticCache();
			$this->endRequest();
		}

		/**
		 * Делает перенаправление на новый адрес, если это необходимо
		 * @throws \Exception
		 */
		private function redirectIfRequired() {
			$isIndexRequest = startsWith(trim($this->request->uri(), ' /'), 'index.php');

			if ($this->config->get('seo', 'index-redirect') && $isIndexRequest) {
				$this->buffer->redirect('/');
			}

			$address = null;
			$prefix = $this->languageDetector->detectPrefix();

			if ($this->isZeroPageNumber() && $this->isDefaultLanguagePrefix()) {
				$existAddress = $this->request->uriWithoutPageNumber();
				$address = $this->getAddressWithoutPrefix($existAddress, $prefix);
			} elseif ($this->isDefaultLanguagePrefix()) {
				$existAddress = $this->request->uri();
				$address = $this->getAddressWithoutPrefix($existAddress, $prefix);
			} elseif ($this->isZeroPageNumber()) {
				$address = $this->request->uriWithoutPageNumber();
			}

			if ($address !== null) {
				$this->buffer->redirect($address);
			}
		}

		/**
		 * Определяет передано ли нулевое значение номера постраничной навигации
		 * @return bool
		 * @throws \Exception
		 */
		private function isZeroPageNumber() : bool {
			$request = $this->request;
			return ($request->issetPageNumber() && $request->pageNumber() === 0 && $request->isHtml());
		}

		/**
		 * Определяет передан ли префикс языка по-умолчанию
		 * @return bool
		 * @throws \coreException
		 */
		private function isDefaultLanguagePrefix() : bool {
			$defaultLanguageId = $this->domainDetector->detect()
				->getDefaultLangId();
			$currentLanguage = $this->languageDetector->detect();
			$isCurrentLanguageDefault = ($currentLanguage->getId() === $defaultLanguageId);
			return $isCurrentLanguageDefault && ($currentLanguage->getPrefix() === $this->request->getFirstPart());
		}

		/**
		 * Возвращает адрес страницы без префикса
		 * @param string $address адрес страницы
		 * @param string $prefix префикс
		 * @return string
		 */
		private function getAddressWithoutPrefix(string $address, string $prefix) : string {
			$newAddress = ltrim($address, '/');

			if (!startsWith($newAddress, $prefix)) {
				return $address;
			}

			return substr_replace($newAddress, '', 0, strlen($prefix));
		}

		/** Инициализирует сессию */
		private function initializeSession() {
			$referer = preg_replace('/^(http(s)?:\/\/)?(www\.)?/', '', $this->request->referrer());
			$host = preg_replace('/^(http(s)?:\/\/)?(www\.)?/', '', $this->request->host());

			if (mb_strpos($referer, $host) !== 0) {
				$this->session->set('http_referer', $this->request->referrer());
				$this->session->set('http_target', $this->request->uri());
			}

			if (!$this->session->get('http_target')) {
				$this->session->set('http_target', $this->request->uri());
			}
		}

		/**
		 * Удаляет повторяющиеся слэши при необходимости
		 * @throws \coreException
		 */
		private function deleteRepeatedSlashesInUrl() {
			if ($this->request->isAdmin()) {
				return;
			}

			$languageId = $this->languageDetector->detectId();
			$domainId = $this->domainDetector->detectId();

			$uri = $this->request->uri();
			$pattern = '|([\/]{2,})|';
			$isRepeatedSlashes = preg_match($pattern, $uri);

			if (!$this->registry->get("//settings/seo/$domainId/$languageId/process-slashes") || !$isRepeatedSlashes) {
				return;
			}

			$pageStatus = $this->registry->get("//settings/seo/$domainId/$languageId/process-slashes-status");

			switch ($pageStatus) {
				case 'redirect': {
					$uri = preg_replace($pattern, '/', $uri);
					$this->buffer->redirect($uri);
					break;
				}

				case 'not-found': {
					$this->moduleRouter->setNotFoundState();
					$this->buffer->status('404 Not Found');
					break;
				}
			}
		}

		/**
		 * Перенаправляет на текущий url с суффиксом, если это необходимо
		 * @throws \coreException
		 */
		private function redirectToUrlWithSuffix() {
			$isUrlSuffixEnable = $this->config->get('seo', 'url-suffix.add');
			$currentElementId = $this->moduleRouter->getCurrentElementId();
			$isCurrentModuleContent = $this->moduleRouter->getCurrentModule() == 'content';
			$isCurrentMethodContent = $this->moduleRouter->getCurrentMethod() == 'content';

			$isNotFoundPage = ($currentElementId === false) && ($isCurrentModuleContent && $isCurrentMethodContent);

			if (!$isNotFoundPage && $isUrlSuffixEnable) {
				\def_module::requireSlashEnding();
			}
		}

		/**
		 * Перехватывает запрос с зеркала
		 * @throws \coreException
		 */
		private function handleRequestFromMirror() {
			$mode = (int) $this->config->get('seo', 'primary-domain-redirect');
			$this->checkMirror($mode);
		}

		/**
		 * Перехватывает запрос с протокола http
		 * @throws \coreException
		 */
		private function handleRequestFromHttp() {
			if ($this->request->isAdmin() || $this->request->isHttps()) {
				return;
			}

			$domain = $this->domainDetector->detect();

			if (!$domain->isUsingSsl() || !$this->config->get('seo', 'https-redirect')) {
				return;
			}

			$address = $domain->getCurrentUrl() . $this->request->uri();
			$this->buffer->redirect($address);
		}

		/**
		 * Определяет тип запроса и подготавливает данные для ответа в буфере
		 * @throws \coreException
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \publicException
		 */
		private function handleRequest() {
			$cachedContent = $this->getCachedContent();

			if (is_string($cachedContent)) {
				$this->handleCachedRequest($cachedContent);
			} else {
				$this->handleHtmlRequest();
			}
		}

		/**
		 * Возвращает закэшированный статический контент страницы
		 * @return bool|string
		 */
		private function getCachedContent() {
			$eventPoint = $this->eventPointFactory->create('systemPrepare', 'before');
			$eventPoint->call();

			$cachedContent = $this->staticCache->load();

			$eventPoint->setMode('after');
			$eventPoint->call();

			return $cachedContent;
		}

		/**
		 * Обрабатывает закэшированный запрос
		 * @param string $cachedContent Закэшированный ответ для запроса
		 */
		private function handleCachedRequest($cachedContent) {
			$this->buffer->contentType('text/html');
			$this->buffer->charset('utf-8');
			$this->buffer->push($cachedContent);
		}

		/**
		 * Обрабатывает HTML-запрос
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \publicException
		 */
		private function handleHtmlRequest() {
			$handleCallStack = !$this->config->get('debug', 'callstack.disabled');
			$result = $this->executeModuleRequest($handleCallStack);

			if ($this->request->isStreamCallStack()) {
				$this->response->printXmlAsString($result);
			}

			if ($this->moduleRouter->isNotFoundState()) {
				$this->buffer->status('404 Not Found');
			}

			$this->buffer->push($result);
			$this->buffer->option('generation-time', true);
			$this->staticCache->save($this->buffer->content());
		}

		/** Сохраняет значение измененных свойств, полученных вне объекта */
		private function saveProperties() {
			$this->propertyHelper->saveProperties();
		}

		/** Отключает показ баннеров */
		private function disableBanners() {
			if ($this->request->isAdmin()) {
				return;
			}

			$banners = $this->moduleRouter->getModule('banners');

			if ($banners instanceof \banners) {
				$banners->saveUpdates();
			}
		}

		/** Обновляет статистику посещений сайта, если включен сбор статистики */
		private function updateStatistics() {
			if ($this->request->isAdmin()) {
				return;
			}

			$statistics = $this->moduleRouter->getModule('stat');

			if ($statistics instanceof \stat && $statistics->isEnabled()) {
				$statistics->pushStat();
			}
		}

		/** Удаляет статический кэш обновленных страниц */
		private function cleanStaticCache() {
			$pageIdList = $this->pages->getUpdatedElements();
			$this->staticCache->deletePageListCache($pageIdList);
		}

		/** Завершает запрос и выводит результат в буфер */
		private function endRequest() {
			$this->buffer->end();
		}
	}