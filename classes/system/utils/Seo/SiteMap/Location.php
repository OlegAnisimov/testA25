<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use \iDomain as iDomain;
	use \iLang as iLanguage;
	use UmiCms\System\Orm\Composite\Entity;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iMapper;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iCollection as iImageCollection;

	/**
	 * Класс адреса карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	class Location extends Entity implements iLocation {

		/** @var int|null $domainId идентификатор домена */
		protected $domainId;

		/** @var string|null $link абсолютная ссылка на страницу */
		protected $link;

		/** @var int|null $sort индекс сортировки */
		protected $sort;

		/** @var float|null $priority приоритет индексации */
		protected $priority;

		/** @var string|null $priority дата изменения */
		protected $dateTime;

		/** @var int|null $level уроверь иерархии */
		protected $level;

		/** @var int|null $languageId идентификатор языка */
		protected $languageId;

		/** @var string|null $changeFrequency вероятная частота изменения этой страницы  */
		protected $changeFrequency;

		/** @var iDomain|null $domain домен */
		protected $domain;

		/** @var iLanguage|null $language язык */
		protected $language;

		/** @var iImageCollection|null $imageCollection коллекция изображений */
		protected $imageCollection;

		/** @inheritDoc */
		public function setId($id) {
			parent::setId($id);
			$this->imageCollection = null;
			return $this;
		}

		/** @inheritDoc */
		public function getDomainId() {
			return $this->domainId;
		}

		/** @inheritDoc */
		public function setDomainId(int $id) : iLocation {
			$this->domain = null;
			return $this->setDifferentValue('domainId', $id);
		}

		/** @inheritDoc */
		public function getLink() {
			return $this->link;
		}

		/** @inheritDoc */
		public function setLink(string $link) : iLocation {
			return $this->setDifferentValue('link', $link);
		}

		/** @inheritDoc */
		public function getSort() {
			return $this->sort;
		}

		/** @inheritDoc */
		public function setSort(int $index) : iLocation {
			return $this->setDifferentValue('sort', $index);
		}

		/** @inheritDoc */
		public function getPriority() {
			return $this->priority;
		}

		/** @inheritDoc */
		public function setPriority(float $priority) : iLocation {
			return $this->setDifferentValue('priority', $priority);
		}

		/** @inheritDoc */
		public function getDateTime() {
			return $this->dateTime;
		}

		/** @inheritDoc */
		public function setDateTime(string $dateTime) : iLocation {
			return $this->setDifferentValue('dateTime', $dateTime);
		}

		/** @inheritDoc */
		public function getLevel() {
			return $this->level;
		}

		/** @inheritDoc */
		public function setLevel(int $level) : iLocation {
			return $this->setDifferentValue('level', $level);
		}

		/** @inheritDoc */
		public function getLanguageId() {
			return $this->languageId;
		}

		/** @inheritDoc */
		public function setLanguageId(int $languageId) : iLocation {
			$this->language = null;
			return $this->setDifferentValue('languageId', $languageId);
		}

		/** @inheritDoc */
		public function getChangeFrequency() {
			return $this->changeFrequency;
		}

		/** @inheritDoc */
		public function setChangeFrequency(string $changeFrequency) : iLocation {
			return $this->setDifferentValue('changeFrequency', $changeFrequency);
		}

		/** @inheritDoc */
		public function getDomain() : iDomain {
			if ($this->domain === null) {
				$this->loadRelation(iMapper::DOMAIN);
			}

			return $this->domain;
		}

		/** @inheritDoc */
		public function setDomain(iDomain $domain) : iLocation {
			return $this->setDomainId($domain->getId())
				->setDifferentValue('domain', $domain);
		}

		/** @inheritDoc */
		public function getLanguage() : iLanguage {
			if ($this->language === null) {
				$this->loadRelation(iMapper::LANGUAGE);
			}

			return $this->language;
		}

		/** @inheritDoc */
		public function setLanguage(iLanguage $language) : iLocation {
			return $this->setLanguageId($language->getId())
				->setDifferentValue('language', $language);
		}

		/** @inheritDoc */
		public function getImageCollection() : iImageCollection {
			if ($this->imageCollection === null) {
				$this->loadRelation(iMapper::IMAGE_COLLECTION);
			}

			return $this->imageCollection;
		}

		/** @inheritDoc */
		public function setImageCollection(iImageCollection $collection) : iLocation {
			$id = $this->getId();

			/** @var iImage $image */
			foreach ($collection as $image) {
				$image->setLocationId($id);
			}

			return $this->setDifferentValue('imageCollection', $collection);
		}

		/** @inheritDoc */
		public function createWithId(): bool {
			return true;
		}
	}