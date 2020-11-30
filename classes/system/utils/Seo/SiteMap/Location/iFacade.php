<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use \iUmiEventPoint as iEvent;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\iFacade as iAbstractFacade;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iFacade as iImageFacade;

	/**
	 * Интерфейс фасада адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	interface iFacade extends iAbstractFacade {

		/**
		 * @inheritDoc
		 * @return iLocation
		 */
		public function create(array $attributeList = []);

		/**
		 * @inheritDoc
		 * @return iCollection
		 */
		public function mapCollection(array $entityList);

		/**
		 * Создает адрес карты сайта по данным события создания карты сайта
		 * @param iEvent $event событие создания карты сайта (before_update_sitemap)
		 * @return iLocation
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		public function createByEvent(iEvent $event) : iLocation;

		/**
		 * Возвращает список корневых адресов карт сайта
		 * @param int $id идентификатор домена
		 * @return array
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getIndexListByDomain(int $id) : array;

		/**
		 * Возвращает количество адресов для заданного домена
		 * @param int $id идентификатор домена
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByDomain(int $id) : int;

		/**
		 * Возвращает коллекцию адресов для заданного домена
		 * @param int $id идентификатор домена
		 * @return iCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getCollectionByDomain(int $id) : iCollection;

		/**
		 * Возвращает коллекцию адресов карты сайта с задаными доменом и индексов сортировки
		 * @param int $id идентификатор домена
		 * @param int $sort индекс сортировки
		 * @return iCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getCollectionByDomainAndSort(int $id, int $sort) : iCollection;

		/**
		 * Удаляет все адреса карты сайта заданного домена
		 * @param int $id идентификатор домена
		 * @return iFacade
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByDomain(int $id) : iFacade;

		/**
		 * Устанавливает фасад изображений
		 * @param iImageFacade $imageFacade фасад изображений
		 * @return iFacade
		 */
		public function setImageFacade(iImageFacade $imageFacade) : iFacade;
	}