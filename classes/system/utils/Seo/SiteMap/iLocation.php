<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use \iDomain as iDomain;
	use \iLang as iLanguage;
	use UmiCms\System\Orm\iEntity;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iCollection as iImageCollection;

	/**
	 * Интерфейс адреса карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	interface iLocation extends iEntity {

		/**
		 * Возвращает идентификатор домена
		 * @return int|null
		 */
		public function getDomainId();

		/**
		 * Устанавливает идентификатор домена
		 * @param int $id идентификатор домена
		 * @return iLocation|Location
		 * @throws \ErrorException
		 */
		public function setDomainId(int $id) : iLocation;

		/**
		 * Возвращает ссылку
		 * @return string|null
		 */
		public function getLink();

		/**
		 * Устанавливает ссылку
		 * @param string $link ссылка
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setLink(string $link) : iLocation;

		/**
		 * Возвращает индекс сортировки
		 * @return int|null
		 */
		public function getSort();

		/**
		 * Устанавливает индекс сортировки
		 * @param int $index индекс сортировки
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setSort(int $index) : iLocation;

		/**
		 * Возвращает приоритет индексации
		 * @return float|null
		 */
		public function getPriority();

		/**
		 * Устанавливает приоритет индексации
		 * @param float $priority приоритет индексации
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setPriority(float $priority) : iLocation;

		/**
		 * Возвращает дату изменения страницы
		 * @return string|null
		 */
		public function getDateTime();

		/**
		 * Устанавливает дату изменения страницы
		 * @param string $dateTime
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setDateTime(string $dateTime) : iLocation;

		/**
		 * Возвращает уровень вложенности страницы
		 * @return int|null
		 */
		public function getLevel();

		/**
		 * Устанавливает уровень вложенности страницы
		 * @param int $level уровень вложенности
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setLevel(int $level) : iLocation;

		/**
		 * Возвращает идентификатор языка
		 * @return int|null
		 */
		public function getLanguageId();

		/**
		 * Устанавливает идентификатор ящыка
		 * @param int $languageId идентификатор языка
		 * @return iLocation|Location
		 * @throws \ErrorException
		 */
		public function setLanguageId(int $languageId) : iLocation;

		/**
		 * Возвращает вероятную частоту обновления карты сайта
		 * @link https://www.sitemaps.org/ru/protocol.html#changefreqdef
		 * @return string|null
		 */
		public function getChangeFrequency();

		/**
		 * Устанавливает вероятную частоту обновления карты сайта
		 * @param string $changeFrequency ключевое слово
		 * @link https://www.sitemaps.org/ru/protocol.html#changefreqdef
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setChangeFrequency(string $changeFrequency) : iLocation;

		/**
		 * Возвращает домен
		 * @return iDomain
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getDomain() : iDomain;

		/**
		 * Устанвливает домен
		 * @param iDomain $domain домен
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setDomain(iDomain $domain) : iLocation;

		/**
		 * Возвращает язык
		 * @return iLanguage
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getLanguage() : iLanguage;

		/**
		 * Устанавливает язык
		 * @param iLanguage $language язык
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setLanguage(iLanguage $language) : iLocation;

		/**
		 * Возвращает коллекцию изображений
		 * @return iImageCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getImageCollection() : iImageCollection;

		/**
		 * Устанавливает коллекцию изображений
		 * @param iImageCollection $collection коллекция изображений
		 * @return iLocation
		 * @throws \ErrorException
		 */
		public function setImageCollection(iImageCollection $collection) : iLocation;
	}