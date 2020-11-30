<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use \iConfiguration as iConfig;
	use \iUmiHierarchy as iPageFacade;
	use \iUmiHierarchyElement as iPage;
	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iFacade as iImageFacade;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iFacade as iLocationFacade;

	/**
	 * Интерфейс класса обновления карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	interface iUpdater {

		/**
		 * Конструктор
		 * @param iLocationFacade $locationFacade фасад адресов карты сайта
		 * @param iPageFacade $pageFacade фасад страниц
		 * @param iEventFactory $eventFactory фабрика событий
		 * @param iImageFacade $imageFacade фасад изображений карты сайта
		 * @param iConfig $config конфигурация
		 */
		public function __construct(
			iLocationFacade $locationFacade,
			iPageFacade $pageFacade,
			iEventFactory $eventFactory,
			iImageFacade $imageFacade,
			iConfig $config
		);

		/**
		 * Обновляет карту, используя страницу
		 * @param iPage $page страница
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 * @throws \publicAdminException
		 */
		public function update(iPage $page);

		/**
		 * Обновляет карту, используя страницу и ее изображения
		 * @param iPage $page страница
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		public function updateImages(iPage $page) : iUpdater;

		/**
		 * Обновляет карту, используя список страниц
		 * @param iPage[] $pageList список страниц
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 * @throws \publicAdminException
		 */
		public function updateList(array $pageList);

		/**
		 * Обновляет карту, используя список страниц с изображениями
		 * @param array $pageList список страниц
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 * @throws \publicAdminException
		 */
		public function updateWithImagesList(array $pageList) : iUpdater;

		/**
		 * Удаляет из карты сайта данные страницы по заданному идентификатору
		 * @param int $pageId идентификатор страницы
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		public function delete($pageId);

		/**
		 * Удаляет из карты сайта данные страниц по заданному списку идентификаторов
		 * @param array $pageIdList список идентификаторов страниц
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteList(array $pageIdList);

		/**
		 * Удаляет все содержимое карты сайта
		 * @return iUpdater
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteAll();

		/**
		 * Удаляет из карты сайта страницы заданного домена
		 * @param int $id идентификатор домена
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByDomain($id);

		/**
		 * Удаляет из карты изображений все изображения, принадлежащие страницам заданного домена
		 * @param int $id идентификатор домена
		 * @return iUpdater
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteImagesByDomain(int $id) : iUpdater;
	}
