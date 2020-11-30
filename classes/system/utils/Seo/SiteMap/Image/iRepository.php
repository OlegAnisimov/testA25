<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\System\Orm\Entity\iRepository as iAbstractRepository;

	/**
	 * Интерфейс репозитория изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iRepository extends iAbstractRepository {

		/**
		 * Удаляет все изображения карты сайта заданного домена
		 * @param int $id идентификатор домена
		 * @return iRepository
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByDomain(int $id) : iRepository;
	}