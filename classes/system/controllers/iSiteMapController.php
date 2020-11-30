<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\Utils\SiteMap\iGenerator;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Интерфейс контроллера sitemap.xml
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iSiteMapController extends iController {

		/** @var int PAGE_NUMBER_LIMIT ограничение на количество страниц в постраничной навигации */
		const PAGE_NUMBER_LIMIT = 16;

		/**
		 * Устанавливает генератор sitemap.xml
		 * @param iGenerator $generator генератор sitemap.xml
		 * @return $this
		 */
		public function setGenerator(iGenerator $generator);

		/**
		 * Устанавливает определитель домена
		 * @param iDomainDetector $detector определитель домена
		 * @return $this
		 */
		public function setDomainDetector(iDomainDetector $detector);
	}