<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Map\Sort;
	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Orm\Entity\iCollection;
	use UmiCms\System\Hierarchy\Domain\iDetector;
	use UmiCms\System\Orm\Entity\Facade as AbstractFacade;

	/**
	 * Класс фасада записей об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	class Facade extends AbstractFacade implements iFacade {

		/** @var iDetector $domainDetector определитель текущего домена */
		private $domainDetector;

		/** @inheritDoc */
		public function log($url, $code) {
			$entry = $this->getOneByUrlAndCode($url, $code);

			if ($entry instanceof iEntry) {
				$entry->incrementHitsCount();
				$this->save($entry);
				return $entry;
			}

			return $this->createByUrlAndCode($url, $code);
		}

		/** @inheritDoc */
		public function getSliceByDomain($domainId, $offset = 0, $limit = 20, array $orderMap = []) {
			$orderMap = $orderMap ?: [iMapper::ID => Sort::SORT_TYPE_DESC];
			$entryCollection = $this->getCollectionSliceWithDomain($domainId, $offset, $limit, $orderMap);

			if ($entryCollection->isNotEmpty()) {
				return $entryCollection->getList();
			}

			/** @var iRepository $repository */
			$repository = $this->getRepository();
			$entryList = $repository->getSliceByDomain($domainId, $offset, $limit, $orderMap);

			if (count($entryList) > 0) {
				$this->getCollection()
					->pushList($entryList);
			}

			return $entryList;
		}

		/** @inheritDoc */
		public function getCountByDomain($domainId) {
			$collection = $this->getCollection()
				->copy()
				->filter([iMapper::DOMAIN_ID => [
					Filter::COMPARE_TYPE_EQUALS => $domainId
				]]);

			if ($collection->isNotEmpty()) {
				return $collection->getCount();
			}

			/** @var iRepository $repository */
			$repository = $this->getRepository();
			return $repository->getCountByDomain($domainId);
		}

		/** @inheritDoc */
		public function create(array $attributeList = []) {
			if (!isset($attributeList[iMapper::URL])) {
				throw new \ErrorException('Response error entry url expected');
			}

			if (!isset($attributeList[iMapper::CODE])) {
				throw new \ErrorException('Response error entry code expected');
			}

			if (!isset($attributeList[iMapper::HITS_COUNT])) {
				$attributeList[iMapper::HITS_COUNT] = 1;
			}

			if (!isset($attributeList[iMapper::DOMAIN_ID])) {
				$attributeList[iMapper::DOMAIN_ID] = $this->domainDetector->detectId();
			}

			if (!isset($attributeList[iMapper::UPDATE_TIME])) {
				$attributeList[iMapper::UPDATE_TIME] = time();
			}

			return parent::create($attributeList);
		}

		/** @inheritDoc */
		public function save(iEntity $entity) {
			if (!$this->isValidEntity($entity)) {
				throw new \ErrorException('Incorrect entity given');
			}

			/** @var iEntry $entity */
			$entity->setUpdateTime(time());
			$this->getRepository()
				->save($entity);

			return $this;
		}

		/** @inheritDoc */
		public function setDomainDetector(iDetector $detector) {
			$this->domainDetector = $detector;
			return $this;
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iEntry;
		}

		/**
		 * Создает запись с заданным адресом запроса и кодом ошибки
		 * @param string $url адрес запроса
		 * @param string $code код ошибки
		 * @return iEntry
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		private function createByUrlAndCode($url, $code) {
			return $this->create([
				iMapper::URL => $url,
				iMapper::CODE => $code
			]);
		}

		/**
		 * Возвращает запись с заданным адресом запроса и кодом ошибки
		 * @param string $url адрес запроса
		 * @param string $code код ошибки
		 * @return iEntry|null
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		private function getOneByUrlAndCode($url, $code) {
			$entry = $this->getCollection()
				->copy()
				->filter([
					iMapper::URL => [
						Filter::COMPARE_TYPE_EQUALS => $url
					],
					iMapper::CODE => [
						Filter::COMPARE_TYPE_EQUALS => $code
					]
				])
				->getFirst();

			if ($entry instanceof iEntry) {
				return $entry;
			}

			$entry = $this->getRepository()
				->getOneByEqualityMap([
					iMapper::URL => $url,
					iMapper::CODE => $code
				]);

			if ($entry instanceof iEntry) {
				$this->getCollection()
					->push($entry);
			}

			return $entry;
		}

		/**
		 * Возвращает часть коллекции записей с указанным доменом
		 * @param int $domainId идентификатор домена
		 * @param int $offset смещение списка записей
		 * @param int $limit размер списка записей
		 * @param array $orderMap карта сортировки
		 * @return iCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		private function getCollectionSliceWithDomain($domainId, $offset = 0, $limit = 20, array $orderMap = []) {
			return $this->getCollection()
				->copy()
				->filter([iMapper::DOMAIN_ID => [
					Filter::COMPARE_TYPE_EQUALS => $domainId
				]])
				->sort($orderMap)
				->slice($offset, $limit);
		}
	}