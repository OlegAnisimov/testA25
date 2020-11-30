<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Hierarchy\Domain\iDetector;
	use UmiCms\System\Orm\Entity\iFacade as iAbstractFacade;

	/**
	 * Интерфейс фасада записей об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	interface iFacade extends iAbstractFacade {

		/**
		 * Ведет журнал записей об обработке ответа с ошибкой
		 * @param string $url адрес запроса
		 * @param int $code код ошибки ответа
		 * @return iEntry
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		public function log($url, $code);

		/**
		 * Возвращает часть списка записей с заданным доменом
		 * @param int $domainId идентификатор домена
		 * @param int $offset смещение списка
		 * @param int $limit размер списка
		 * @param array $orderMap $orderMap карта сортировки
		 * @return iEntry[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getSliceByDomain($domainId, $offset = 0, $limit = 20, array $orderMap = []);

		/**
		 * Возвращает количество записей с указанным доменом
		 * @param int $domainId идентификатор домена
		 * @return int
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByDomain($domainId);

		/**
		 * @inheritDoc
		 * @return iEntry
		 */
		public function create(array $attributeList = []);

		/**
		 * @inheritDoc
		 * @return iEntry
		 */
		public function get($id);

		/**
		 * Устанавливает определителя текущего домена
		 * @param iDetector $detector определитель
		 * @return $this
		 */
		public function setDomainDetector(iDetector $detector);

		/**
		 * @inheritDoc
		 * @return \UmiCms\System\Response\Error\Entry\iCollection
		 */
		public function getCollectionBy($name, $value);
	}