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
	 * Интерфейс корневого контроллера
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iIndexController extends iController, MiddleWares\iAuth, MiddleWares\iUmiManager, MiddleWares\iStub,
		MiddleWares\iUmapRouter, MiddleWares\iModuleRouter, MiddleWares\iMirrorHandler {

		/**
		 * Устанавливает фасад страниц
		 * @param iPages $pages фасад страниц
		 * @return $this
		 */
		public function setPages(iPages $pages);

		/**
		 * Устанавливает конфигурацию
		 * @param iConfig $config конфигурация
		 * @return $this
		 */
		public function setConfig(iConfig $config);

		/**
		 * Устанавливает реестр
		 * @param iRegistry $registry реестр
		 * @return $this
		 */
		public function setRegistry(iRegistry $registry);

		/**
		 * Устанавливает сессию
		 * @param iSession $session сессия
		 * @return $this
		 */
		public function setSession(iSession $session);

		/**
		 * Устанавливает фасад статического кеша
		 * @param iStaticCache $staticCache фасад статического кеша
		 * @return $this
		 */
		public function setStaticCache(iStaticCache $staticCache);

		/**
		 * Устанавливает фасад полей объектов
		 * @param iPropertyHelper $propertyHelper фасад полей объектов
		 * @return $this
		 */
		public function setPropertyHelper(iPropertyHelper $propertyHelper);

		/**
		 * Устанавливает определитель домена
		 * @param iDomainDetector $domainDetector определитель домена
		 * @return $this
		 */
		public function setDomainDetector(iDomainDetector $domainDetector);

		/**
		 * Устанавливает определитель языка
		 * @param iLanguageDetector $languageDetector определитель языка
		 * @return $this
		 */
		public function setLanguageDetector(iLanguageDetector $languageDetector);
	}