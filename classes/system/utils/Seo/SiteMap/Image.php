<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use UmiCms\System\Orm\Entity;

	/**
	 * Класс изображения
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	class Image extends Entity implements iImage {

		/** @var int|null $locationId идентификатор адреса */
		protected $locationId;

		/** @var int|null $domainId идентификатор домена */
		protected $domainId;

		/** @var string|null $link ссылка */
		protected $link;

		/** @var string|null $alt альтернативный текст */
		protected $alt;

		/** @var string|null $title заголовок */
		protected $title;

		/** @inheritDoc */
		public function getLocationId() {
			return $this->locationId;
		}

		/** @inheritDoc */
		public function setLocationId(int $id) : iImage {
			return $this->setDifferentValue('locationId', $id);
		}

		/** @inheritDoc */
		public function getDomainId() {
			return $this->domainId;
		}

		/** @inheritDoc */
		public function setDomainId(int $id) : iImage {
			return $this->setDifferentValue('domainId', $id);
		}

		/** @inheritDoc */
		public function getLink() {
			return $this->link;
		}

		/** @inheritDoc */
		public function setLink(string $link) : iImage {
			return $this->setDifferentValue('link', $link);
		}

		/** @inheritDoc */
		public function getAlt() {
			return $this->alt;
		}

		/** @inheritDoc */
		public function setAlt(string $alt) : iImage {
			return $this->setDifferentValue('alt', $alt);
		}

		/** @inheritDoc */
		public function getTitle() {
			return $this->title;
		}

		/** @inheritDoc */
		public function setTitle(string $title) : iImage {
			return $this->setDifferentValue('title', $title);
		}
	}