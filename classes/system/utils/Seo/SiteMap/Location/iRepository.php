<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\iRepository as iAbstractRepository;

	/**
	 * Интерфейс репозитория адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	interface iRepository extends iAbstractRepository {

		/**
		 * Возвращает количество адресов для заданного домена
		 * @param int $id идентификатор домена
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByDomain(int $id) : int;

		/**
		 * Возвращает список корневых адресов карт сайта
		 * @param int $id идентификатор домена
		 * @return array
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getIndexListForDomain(int $id) : array;

		/**
		 * Возвращает список адресов карты сайта с задаными доменом
		 * @param int $id идентификатор домена
		 * @return iLocation[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListByDomain(int $id) : array;

		/**
		 * Возвращает список адресов карты сайта с задаными доменом и индексов сортировки
		 * @param int $id идентификатор домена
		 * @param int $sort индекс сортировки
		 * @return iLocation[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListByDomainAndSort(int $id, int $sort) : array;

		/**
		 * Удаляет все адреса карты сайта заданного домена
		 * @param int $id идентификатор домена
		 * @return iRepository
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByDomain(int $id) : iRepository;
	}