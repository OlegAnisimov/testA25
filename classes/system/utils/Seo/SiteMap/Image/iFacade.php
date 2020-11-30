<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\iFacade as iAbstractFacade;

	/**
	 * Интерфейс фасада изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iFacade extends iAbstractFacade {

		/**
		 * Устанавливает извлекателя изображений
		 * @param iExtractor $extractor извлекатель изображений
		 * @return iFacade
		 */
		public function setImageExtractor(iExtractor $extractor) : iFacade;

		/**
		 * Создает изображение
		 * @param array $attributeList атрибуты изображения
		 * @return iImage
		 * @throws \databaseException
		 * @throws \coreException
		 * @throws \ErrorException
		 * @throws \Exception
		 */
		public function create(array $attributeList = []);

		/**
		 * Создает изображения из адреса карты сайта
		 * @param iLocation $location адрес карты сайта
		 * @return iImage[]
		 * @throws \Exception
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		public function createByLocation(iLocation $location) : array;

		/**
		 * Удаляет все изображения карты сайта заданного домена
		 * @param int $id идентификатор домена
		 * @return iFacade
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByDomain(int $id) : iFacade;
	}