<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Orm\Entity\iRepository as iAbstractRepository;

	/**
	 * Интерфейс репозитория записей об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	interface iRepository extends iAbstractRepository {

		/**
		 * Возвращает часть списка записей
		 * @param int $domainId идентификатор домена
		 * @param int $offset смещение списка
		 * @param int $limit размер списка
		 * @param array $orderMap $orderMap карта сортировки
		 * @return iEntry[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getSliceByDomain($domainId, $offset, $limit, array $orderMap = []);

		/**
		 * Возвращает количество записей с заданным доменом
		 * @param int $domainId
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByDomain($domainId);
	}