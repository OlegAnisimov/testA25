<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\Utils\SiteMap\iGenerator;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Класс контроллера sitemap.xml
	 * @package UmiCms\Classes\System\Controllers
	 */
	class SiteMapController extends AbstractController implements iSiteMapController {

		/** @var iGenerator $generator генератор sitemap.xml */
		private $generator;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @inheritDoc */
		public function setGenerator(iGenerator $generator) {
			$this->generator = $generator;
			return $this;
		}

		/** @inheritDoc */
		public function setDomainDetector(iDomainDetector $detector) {
			$this->domainDetector = $detector;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!array_key_exists('id', $this->parameters)) {
				throw new \ErrorException('Incorrect router parameters given, id expected');
			}

			$domain = $this->domainDetector->detect();
			$pageNumber = $this->parameters['id'];

			switch (true) {
				case ($pageNumber === '') : {
					$siteMap = $this->generator->getIndex($domain);
					break;
				}
				case ($pageNumber >= 0 && $pageNumber <= self::PAGE_NUMBER_LIMIT) : {
					$siteMap = $this->generator->getByPageNumber($domain, $pageNumber);
					break;
				}
				default : {
					$siteMap = $this->generator->getEmpty();
				}
			}

			$this->response->printXmlAsString($siteMap);
		}
	}