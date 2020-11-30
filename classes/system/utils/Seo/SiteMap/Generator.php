<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDocumentFactory;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iFacade as iLocationFacade;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iCollection as iLocationCollection;

	/**
	 * Класс генератора карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	class Generator implements iGenerator {

		/** @var iLocationFacade $locationFacade репозиторий адресов карты сайта */
		protected $locationFacade;

		/** @var iDocumentFactory $documentFactory фабрика DOM документов */
		protected $documentFactory;

		/** @inheritDoc */
		public function __construct(iLocationFacade $locationFacade, iDocumentFactory $documentFactory) {
			$this->locationFacade = $locationFacade;
			$this->documentFactory = $documentFactory;
		}

		/** @inheritDoc */
		public function getIndex(\iDomain $domain, $limit = self::SEARCH_ENGINES_SITE_MAP_URL_LIMIT) {
			$domainId = $domain->getId();
			$count = $this->locationFacade->getCountByDomain($domainId);

			if ($count > 0 && $count > $limit) {
				return $this->genSiteIndex($domain);
			}

			return $this->genSiteMap($domainId);
		}

		/** @inheritDoc */
		public function getByPageNumber(\iDomain $domain, $pageNumber) {
			$pageNumber = (int) $pageNumber;
			return $this->genSiteMap($domain->getId(), $pageNumber);
		}

		/** @inheritDoc */
		public function getEmpty() {
			$containerTags = $this->getContainerTags();
			return $containerTags[0] . $containerTags[1];
		}

		/**
		 * Возвращает теги, обрамляющие адреса карты сайта
		 * @return array
		 */
		protected function getContainerTags() : array {
			return [
				'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
				'</urlset>',
			];
		}

		/**
		 * Возвращает адрес корневой карты сайта
		 * @param \iDomain $domain домен
		 * @param int $sort индекс сортировки
		 * @return string
		 */
		protected function getSiteIndexLocation(\iDomain $domain, int $sort) : string {
			return sprintf('%s://%s/sitemap%d.xml', $domain->getProtocol(), $domain->getHost(), $sort);
		}

		/**
		 * Возвращает коллекцию адресов карты сайта
		 * @param int $domainId идентификатор домена
		 * @param int|null $sort индекс сортировки
		 * @return iLocationCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		protected function getLocationCollection(int $domainId, int $sort = null) : iLocationCollection {
			if ($sort !== null) {
				return $this->locationFacade->getCollectionByDomainAndSort($domainId, $sort);
			}

			return $this->locationFacade->getCollectionByDomain($domainId);
		}

		/**
		 * Возвращает xml представление адреса карты сайта
		 * @param iLocation $location адрес карты сайта
		 * @return \DOMDocument
		 */
		protected function getLocationXml(iLocation $location) : \DOMDocument {
			$dom = $this->documentFactory->create();
			$url = $dom->createElement('url');
			$loc = $dom->createElement('loc', $location->getLink());
			$priority = $dom->createElement('priority', $location->getPriority());
			$date = date('c', strtotime($location->getDateTime()));
			$lastMod = $dom->createElement('lastmod', $date);
			$dom->appendChild($url);
			$url->appendChild($loc);
			$url->appendChild($lastMod);
			$url->appendChild($priority);

			$changeFrequency = $location->getChangeFrequency();
			if ($changeFrequency) {
				$changeFrequencyNode = $dom->createElement('changefreq', $changeFrequency);
				$url->appendChild($changeFrequencyNode);
			}

			return $dom;
		}

		/**
		 * Возвращает индексную страницу карты сайта с пагинацией
		 * @param \iDomain $domain домен
		 * @return string
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		private function genSiteIndex(\iDomain $domain) {
			$siteMap = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			foreach ($this->locationFacade->getIndexListByDomain($domain->getId()) as $row) {
				list($sort, $updateTime) = $row;
				$dom = $this->documentFactory->create();
				$url = $dom->createElement('sitemap');
				$loc = $dom->createElement('loc', $this->getSiteIndexLocation($domain, (int) $sort));
				$lastMod = $dom->createElement('lastmod', date('c', strtotime($updateTime)));
				$dom->appendChild($url);
				$url->appendChild($loc);
				$url->appendChild($lastMod);
				$siteMap .= $dom->saveXML($url);
			}

			$siteMap .= '</sitemapindex>';
			return $siteMap;
		}

		/**
		 * Возвращает карту сайта
		 * @param int $domainId идентификатор домена
		 * @param null|int $pageNumber номер страницы в рамках пагинации
		 * @return string
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		private function genSiteMap($domainId, $pageNumber = null) {
			$containerTags = $this->getContainerTags();
			$siteMap = $containerTags[0];
			$locationCollection = $this->getLocationCollection($domainId, $pageNumber);

			foreach ($locationCollection as $location) {
				$document = $this->getLocationXml($location);
				$url = $document->getElementsByTagName('url')->item(0);
				$siteMap .= $document->saveXML($url);
			}

			$siteMap .= $containerTags[1];;
			return $siteMap;
		}
	}

