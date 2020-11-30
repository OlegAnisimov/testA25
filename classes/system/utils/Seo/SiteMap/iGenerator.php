<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDocumentFactory;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iFacade as iLocationFacade;

	/**
	 * Интерфейс генератора карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	interface iGenerator {

		/** @var int SEARCH_ENGINES_SITE_MAP_URL_LIMIT максимальное количество адресов в одной карте сайта */
		const SEARCH_ENGINES_SITE_MAP_URL_LIMIT = 50000;

		/**
		 * Конструктор
		 * @param iLocationFacade $locationFacade фасад адресов карты сайта
		 * @param iDocumentFactory $documentFactory фабрика DOM документов
		 */
		public function __construct(iLocationFacade $locationFacade, iDocumentFactory $documentFactory);

		/**
		 * Возвращает корневую карту сайта для заданного домена
		 * @param \iDomain $domain  домен
		 * @param int $limit лимит на количество адресов в карте сайта без постраничной навигации
		 * @return string
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getIndex(\iDomain $domain, $limit = self::SEARCH_ENGINES_SITE_MAP_URL_LIMIT);

		/**
		 * Возвращает карту сайта для заданного домена и страницы
		 * @param \iDomain $domain домен
		 * @param int $pageNumber номер страницы в рамках пагинации
		 * @return string
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getByPageNumber(\iDomain $domain, $pageNumber);

		/**
		 * Возвращает пустую карту сайта
		 * @return string
		 */
		public function getEmpty();
	}