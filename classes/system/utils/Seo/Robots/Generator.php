<?php
	namespace UmiCms\Classes\System\Utils\Robots;

	use \iRegedit as iRegistry;
	use \iConfiguration as iConfig;
	use UmiCms\System\Selector\iFactory as iSelectorFactory;
	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as iLanguageDetector;

	/**
	 * Класс генератора robots.txt
	 * @package UmiCms\Classes\System\Utils\Robots
	 */
	class Generator implements iGenerator {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iEventFactory $eventFactory фабрика событий */
		private $eventFactory;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @var iSelectorFactory $selectorFactory фабрика селекторов */
		private $selectorFactory;

		/** @var iLanguageDetector $languageDetector определитель языка */
		private $languageDetector;

		/** @inheritDoc */
		public function __construct(
			iConfig $config, iRegistry $registry, iEventFactory $eventFactory,
			iDomainDetector $domainDetector, iSelectorFactory $selectorFactory, iLanguageDetector $languageDetector
		) {
			$this->config = $config;
			$this->registry = $registry;
			$this->eventFactory = $eventFactory;
			$this->domainDetector = $domainDetector;
			$this->selectorFactory = $selectorFactory;
			$this->languageDetector = $languageDetector;
		}

		/** @inheritDoc */
		public function execute() {
			$crawlDelay = $this->getCrawlDelay();
			$rules = $this->getRules();
			$host = $this->getHost();
			$result = $this->getRobotCustomContent($rules, $host, $crawlDelay);

			if ($result !== null) {
				return $result;
			}

			$rules = 'Disallow: /?' . PHP_EOL . $rules . PHP_EOL . PHP_EOL;
			$rules = explode(PHP_EOL, $rules);
			$siteMap = $this->getSiteMap();

			$event = $this->eventFactory->create('formationRobots', 'before');
			$event->addRef('rules', $rules)
				->addRef('host', $host)
				->addRef('sitemap', $siteMap)
				->addRef('crawlDelay', $crawlDelay)
				->call();

			$content = [
				'User-Agent: Googlebot' => $rules,
				'User-Agent: Yandex' => $rules,
				'User-Agent: *' => $rules,
				'Host: ' => $host,
				'Sitemap: ' => $siteMap,
				'Crawl-delay: ' => $crawlDelay,
			];

			$event->setMode('after')
				->addRef('bufferContent', $content)
				->call();

			$result = '';

			foreach ($content as $key => $value) {
				$result .= $key;

				if (is_array($value)) {
					foreach ($value as $rule) {
						$result .= PHP_EOL . $rule;
					}
				} else {
					$result .= $value . PHP_EOL;
				}
			}

			return $result;
		}

		/**
		 * Возвращает значение директивы "crawl-delay"
		 * @return string
		 */
		private function getCrawlDelay() {
			return (string) $this->config->get('seo', 'crawl-delay');
		}

		/**
		 * Возвращает адрес сайта
		 * @return string
		 * @throws \coreException
		 */
		private function getHost() {
			$domain = $this->domainDetector->detect();
			$host = $domain->getHost();
			$host = preg_replace('/^www./', '', $host);
			$primaryWww = (bool) $this->config->get('seo', 'primary-www');

			if ($primaryWww) {
				$host = 'www.' . $host;
			}

			return $domain->getProtocol() . "://{$host}";
		}

		/**
		 * Возвращает правила robots.txt
		 * @return string
		 * @throws \coreException
		 * @throws \selectorException
		 */
		private function getRules() {
			if ($this->isDisallowAll()) {
				return 'Disallow: /';
			}

			$disallowedPages = $this->selectorFactory->createPage();
			$disallowedPages->where('robots_deny')->equals(1);
			$disallowedPages->where('lang')->isnotnull();

			$rules = '';

			/** @var \iUmiHierarchyElement $page */
			foreach ($disallowedPages as $page) {
				if ($page->getHierarchyType()->hidePages()) {
					continue;
				}

				$rules .= 'Disallow: ' . $page->link . PHP_EOL;
			}

			$rules .= <<<RULES
Disallow: /admin
Disallow: /index.php
Disallow: /emarket/addToCompare
Disallow: /emarket/basket
Disallow: /emarket/gateway
Disallow: /go-out.php
Disallow: /cron.php
Disallow: /filemonitor.php
Disallow: /search
Disallow: /captcha.php
Disallow: /counter.php
Disallow: /license_restore.php
Disallow: /packer.php
Disallow: /save_domain_keycode.php
Disallow: /session.php
Disallow: /standalone.php
Disallow: /static_banner.php
Disallow: /updater.php
Disallow: /users/login_do
Disallow: /autothumbs.php
Disallow: /~/
Disallow: /install.php
Disallow: /installer.php
RULES;
			$event = $this->eventFactory->create('formationRobotsRules', 'after');
			$event->addRef('rules', $rules)
				->call();

			return $rules;
		}

		/**
		 * Необходимо ли закрывать все страницы от поискового робота
		 * @return bool
		 * @throws \coreException
		 */
		private function isDisallowAll() {
			$domainId = $this->domainDetector->detectId();
			$langId = $this->languageDetector->detectId();

			$isDisallowForAllSites = (bool) $this->registry->get('//umiStub/robot-stub');
			$isUseDomainSettings = (bool) $this->registry->get("//umiStub/$domainId/$langId/use-custom-settings");
			$isDisallowForCurrentSite = (bool) $this->registry->get("//umiStub/$domainId/$langId/robot-stub");

			return ($isUseDomainSettings && $isDisallowForCurrentSite) || (!$isUseDomainSettings && $isDisallowForAllSites);
		}

		/**
		 * Возвращает содержимое кастомного robots.txt
		 * @param string $rules
		 * @param string $host
		 * @param string $crawlDelay
		 * @return string[]|null
		 * @throws \coreException
		 */
		private function getRobotCustomContent($rules, $host, $crawlDelay) {
			$customRobots  = $this->getRobotsCustom();

			if (!$customRobots) {
				return null;
			}

			$needleList = [
				'%disallow_umi_pages%',
				'%host%',
				'%crawl_delay%'
			];

			$replacementList = [
				$rules,
				$host,
				$crawlDelay
			];

			$content = str_replace($needleList, $replacementList, $customRobots);
			return $content;
		}

		/**
		 * Возвращает адрес карты сайта
		 * @return string
		 * @throws \coreException
		 */
		private function getSiteMap() {
			$host = $this->getHost();
			return $host . '/sitemap.xml' . PHP_EOL . 'Sitemap: ' . $host . '/sitemap-images.xml' . PHP_EOL;
		}

		/**
		 * Возвращает пользовательский robots.txt
		 * @return string|null
		 */
		private function getRobotsCustom() {
			$domainId = $this->domainDetector->detectId();
			$path = CURRENT_WORKING_DIR . '/robots/' . $domainId . '.robots.txt';

			if (file_exists($path)) {
				return file_get_contents($path);
			}

			return $this->getSeoRobotsCustom($domainId);
		}

		/**
		 * Возвращает пользовательский robots.txt из модуля SEO
		 * @return string|null
		 * @throws selectorException
		 */
		private function getSeoRobotsCustom($domainId) {
			$selector = $this->selectorFactory->createObjectTypeName('seo', 'robots-txt');
			$selector->where('domain_id')->equals($domainId);
			$selector->limit(0, 1);
			$object = $selector->first();

			return $object ? $object->getValue('robots_txt') : null;
		}
	}