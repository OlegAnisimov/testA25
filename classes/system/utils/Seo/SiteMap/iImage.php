<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use UmiCms\System\Orm\iEntity;

	/**
	 * Интерфейс изображения
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	interface iImage extends iEntity {

		/**
		 * Возвращает идентификатор адреса
		 * @return int|null
		 */
		public function getLocationId();

		/**
		 * Устанавливает идентификатор адреса
		 * @param int $id идентификатор адреса
		 * @return iImage
		 * @throws \ErrorException
		 */
		public function setLocationId(int $id) : iImage;

		/**
		 * Возвращает идентификатор домена
		 * @return int|null
		 */
		public function getDomainId();

		/**
		 * Устанавливает идентификатор домена
		 * @param int $id идентификатор домена
		 * @return iImage
		 * @throws \ErrorException
		 */
		public function setDomainId(int $id) : iImage;

		/**
		 * Возвращает ссылку
		 * @return string|null
		 */
		public function getLink();

		/**
		 * Устанавливает ссылку
		 * @param string $source ссылка
		 * @return iImage
		 * @throws \ErrorException
		 */
		public function setLink(string $source) : iImage;

		/**
		 * Возвращает альтернативный текст
		 * @return string|null
		 */
		public function getAlt();

		/**
		 * Устанавливает альтернативный текст
		 * @param string $alt
		 * @return iImage
		 * @throws \ErrorException
		 */
		public function setAlt(string $alt) : iImage;

		/**
		 * Возвращает заголовок
		 * @return string|null
		 */
		public function getTitle();

		/**
		 * Устанавливает заголовок
		 * @param string $title заголовок
		 * @return iImage
		 * @throws \ErrorException
		 */
		public function setTitle(string $title) : iImage;
	}