<?php
	namespace UmiCms\Utils\DomainKeyCode;

	use \iRegedit as iRegistry;
	use \iConfiguration as iConfig;
	use \iDomainsCollection as iDomains;
	use \iCmsController as iModuleLoader;
	use \umiTemplater as iTemplateEngine;
	use UmiCms\System\Request\iFacade as iRequest;
	use \umiRemoteFileGetter as iLicenseServerClient;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Класс сохранения доменного ключа
	 * @package UmiCms\Utils\DomainKeyCode
	 */
	class Saver implements iSaver {

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iRequest $request запрос */
		private $request;

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iDomains $domains фасад доменов */
		private $domains;

		/** @var iModuleLoader $moduleLoader загрузчик модулей */
		private $moduleLoader;

		/** @var iTemplateEngine $templateEngine шаблонизатор */
		private $templateEngine;

		/** @var iLicenseServerClient $client клиент сервера лицензий */
		private $client;

		/** @inheritDoc */
		public function __construct(
			iRegistry $registry, iRequest $request, iConfig $config, iDomains $domains,
			iModuleLoader $moduleLoader, iTemplateEngineFactory $templateEngineFactory, iLicenseServerClient $client
		) {
			$this->registry = $registry;
			$this->request = $request;
			$this->config = $config;
			$this->domains = $domains;
			$this->moduleLoader = $moduleLoader;
			$this->templateEngine = $templateEngineFactory->createPhp(null);
			$this->client = $client;
		}

		/** @inheritDoc */
		public function saveToServer($keyCode) {
			$this->dropCache();
			$params = [
				'ip' => $this->request->serverAddress() ?: $this->request->documentRoot(),
				'domain' => $this->request->host(),
				'keycode' => $keyCode,
				'previous_edition' => $this->registry->get('//settings/system_edition'),
				'last_update_time' => $this->registry->get('//settings/last_updated')
			];
			$url = 'aHR0cDovL3Vkb2QudW1paG9zdC5ydS91ZGF0YTovL2N1c3RvbS9wcmltYXJ5Q2hlY2tDb2RlLw==';
			$url = base64_decode($url) . base64_encode(serialize($params)) . '/';
			$result = $this->client::get($url, false, false, false, false, false, 30);
			$xml = simplexml_load_string($result);
			$xml->addChild('is_domain_not_default', (int) !$this->domains->isDefaultDomain($this->request->host()));
			return $xml;
		}

		/** @inheritDoc */
		public function saveToCms($keyCode, $edition) {
			$this->dropCache();

			if (!$edition) {
				throw new \ErrorException('Incorrect edition given');
			}

			$this->validateKeyCode($keyCode, $edition);
			$this->setDefaultDomain();
			$this->registry->set('//settings/keycode', $keyCode);
			$this->registry->set('//settings/system_edition', $edition);
			$this->registry->set('//settings/previous_edition', $this->registry->get('//settings/system_edition'));
			$this->deleteIllegalComponents();
		}

		/** Удаляет кеш */
		private function dropCache() {
			$cacheDirectory = $this->config->includeParam('system.runtime-cache');

			foreach ([$cacheDirectory . 'registry', $cacheDirectory . 'trash'] as $filePath) {
				if (is_file($filePath)) {
					unlink($filePath);
				}
			}
		}

		/**
		 * Валидирует доменный ключ
		 * @param string $keyCode доменный ключ
		 * @param string $edition системное имя редакции
		 * @throws \ErrorException
		 */
		private function validateKeyCode($keyCode, $edition) {
			if (mb_strlen(str_replace('-', '', $keyCode)) != 33) {
				throw new \ErrorException('Incorrect key code format given');
			}

			$host = $this->request->host();
			$proEditionList = ['commerce', 'business', 'corporate', 'commerce_enc', 'business_enc', 'corporate_enc', 'gov'];
			$internalCodeName = in_array($edition, $proEditionList) ? 'pro' : $edition;
			$checkKey = $this->templateEngine::getSomething($internalCodeName, $host);

			if ($checkKey != mb_substr($keyCode, 12)) {
				throw new \ErrorException('Incorrect key code given');
			}
		}

		/**
		 * Устанавливает основной домен
		 * @throws \coreException
		 * @throws \databaseException
		 * @throws \wrongParamException
		 */
		private function setDefaultDomain() {
			$host = $this->request->host();

			try {
				$defaultDomain = $this->domains->getDefaultDomain();
				$defaultDomain->setHost($host);
				$defaultDomain->commit();
			} catch (\databaseException $exception) {
				if ($exception->getCode() == \IConnection::DUPLICATE_KEY_ERROR_CODE) {
					$currentDomainId = $this->domains->getDomainId($host);
					$this->domains->setDefaultDomain($currentDomainId);
				} else {
					throw $exception;
				}
			}
		}

		/**
		 * Удаляет нелегальные компоненты
		 * @throws \publicException
		 */
		private function deleteIllegalComponents() {
			$autoUpdates = $this->moduleLoader->getModule('autoupdate');

			if ($autoUpdates instanceof \autoupdate) {
				/* @var \AutoUpdateService $autoUpdates */
				$autoUpdates->deleteIllegalComponents();
			}
		}
	}