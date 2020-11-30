<?php
	namespace UmiCms\System\Orm\Entity;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Map\Sort;
	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Orm\Entity\Attribute\Accessor\tInjector as tAttributeAccessorInjector;

	/**
	 * Абстрактный класс коллекции сущностей
	 * @package UmiCms\System\Orm\Entity
	 */
	abstract class Collection implements iCollection {

		/** @var iEntity[] $collection содержимое коллекции */
		private $collection = [];

		/** @var int[] $idList список идентификаторов сущностей коллекции */
		private $idList = [];

		/** @var int $position текущая позиция итерирования */
		private $position = 0;

		use tAttributeAccessorInjector;

		/** @inheritDoc */
		public function __construct(iAccessor $attributeAccessor) {
			$this->setAttributeAccessor($attributeAccessor);
		}

		/** @inheritDoc */
		public function getList() {
			return $this->collection;
		}

		/** @inheritDoc */
		public function get($id) {
			if (!is_numeric($id)) {
				return null;
			}

			foreach ($this->getList() as $entity) {
				if ($entity->getId() == $id) {
					return $entity;
				}
			}

			return null;
		}

		/** @inheritDoc */
		public function getFirst() {
			return getFirstValue($this->getList());
		}

		/** @inheritDoc */
		public function getListBy($name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS) {
			return $this->filterListBy($this->getList(), $name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS);
		}

		/** @inheritDoc */
		public function getSortedList($name, $sortType = Sort::SORT_TYPE_ASC) {
			return $this->sortListBy($this->getList(), $name, $sortType);
		}

		/** @inheritDoc */
		public function getFirstBy($name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS) {
			$result = $this->getListBy($name, $value);
			return getFirstValue($result);
		}

		/** @inheritDoc */
		public function push(iEntity $entity) {

			if ($this->isPushed($entity)) {
				return $this;
			}

			$this->collection[] = $entity;
			$id = $entity->getId();
			$this->idList[$id] = $id;
			return $this;
		}

		/** @inheritDoc */
		public function pushList(array $entityList) {

			foreach ($entityList as $entity) {
				$this->push($entity);
			}

			return $this;
		}

		/** @inheritDoc */
		public function pull($id) {
			$entity = $this->get($id);

			if ($entity === null) {
				return null;
			}

			$index = array_search($entity, $this->collection);

			if ($index !== false) {
				unset($this->collection[$index]);
			}

			unset($this->idList[$id]);
			return $entity;
		}

		/** @inheritDoc */
		public function pullList(array $idList) {
			$entityList = [];

			foreach ($idList as $id) {
				$entity = $this->pull($id);

				if (!$entity instanceof iEntity) {
					continue;
				}

				$entityList[$entity->getId()] = $entity;
			}

			return $entityList;
		}

		/** @inheritDoc */
		public function pullCollection(iCollection $collection) : array {
			$idList = $collection->extractId();
			return $this->pullList($idList);
		}

		/** @inheritDoc */
		public function filter(array $map) {
			if (isEmptyArray($map)) {
				return $this;
			}

			$filteredList = $this->getList();

			foreach ($map as $name => $expression) {
				if (!is_array($expression) || count($expression) !== 1) {
					$value = $expression;
					$compareType = Filter::COMPARE_TYPE_EQUALS;
				} else {
					$value = getFirstValue($expression);
					$compareType = getFirstValue(array_keys($expression));
				}

				$filteredList = $this->filterListBy($filteredList, $name, $value, $compareType);
			}

			return $this->clear()
				->pushList($filteredList);
		}

		/** @inheritDoc */
		public function filterByEqualityMap(array $map) : iCollection {
			$filterMap = [];

			foreach ($map as $name => $value) {
				$filterMap[] = [
					$name => [
						Filter::COMPARE_TYPE_EQUALS => $value
					]
				];
			}

			return $this->filter($filterMap);
		}

		/** @inheritDoc */
		public function filterByList(array $mapList) {

			foreach ($mapList as $map) {
				$this->filter($map);
			}

			return $this;
		}

		/** @inheritDoc */
		public function sort(array $map) {
			if (isEmptyArray($map)) {
				return $this;
			}

			$sortedList = $this->getList();

			foreach ($map as $field => $sortType) {
				$sortedList = $this->sortListBy($sortedList, $field, $sortType);
			}

			return $this->clear()
				->pushList($sortedList);
		}

		/** @inheritDoc */
		public function sortByIdList(array $idList) {
			return $this->sortByValueList('id', $idList);
		}

		/** @inheritDoc */
		public function sortByValueList($name, array $valueList) {
			if (isEmptyArray($valueList)) {
				return $this;
			}

			$list = $this->getList();
			$list = $this->sortListByValueList($list, $name, $valueList);
			return $this->clear()
				->pushList($list);
		}

		/** @inheritDoc */
		public function extractField($name) {
			return $this->getAttributeAccessor()
				->accessCollection($this, $name);
		}

		/** @inheritDoc */
		public function extractUniqueField($name) {
			$valueList = $this->extractField($name);
			return array_unique($valueList);
		}

		/** @inheritDoc */
		public function extractId() {
			return $this->extractField('id');
		}

		/** @inheritDoc */
		public function slice($offset, $limit = null) {
			$slicedList = array_slice($this->getList(), $offset, $limit);
			return $this->clear()
				->pushList($slicedList);
		}

		/** @inheritDoc */
		public function map() {
			$attributeAccessor = $this->getAttributeAccessor();
			$result = [];

			foreach ($this->getList() as $entity) {
				$result[$entity->getId()] = $attributeAccessor->accessOneToAll($entity);
			}

			return $result;
		}

		/** @inheritDoc */
		public function copy() {
			$collection = clone $this;
			return $collection->pushList($this->getList());
		}

		/** @inheritDoc */
		public function clear() {
			$this->collection = [];
			$this->idList = [];
			return $this;
		}

		/** @inheritDoc */
		public function getCount() {
			return count($this->getList());
		}

		/** @inheritDoc */
		public function isEmpty() {
			return $this->getCount() === 0;
		}

		/** @inheritDoc */
		public function isNotEmpty() {
			return $this->isEmpty() === false;
		}

		/** @inheritDoc */
		public function __clone() {
			$this->clear();
			return $this;
		}

		/** @inheritDoc */
		public function current() {
			return $this->getList()[$this->key()];
		}

		/** @inheritDoc */
		public function next() {
			return $this->incrementPosition();
		}

		/** @inheritDoc */
		public function key() {
			return $this->getPosition();
		}

		/** @inheritDoc */
		public function valid() {
			return isset($this->getList()[$this->key()]);
		}

		/** @inheritDoc */
		public function rewind() {
			return $this->setPosition(0);
		}

		/**
		 * Сортирует список сущностей по порядку значений поля в списке
		 * @param iEntity[] $entityList список значений
		 * @param string $name имя сортируемого поля
		 * @param array $valueList список значений в искомом порядке
		 * @return mixed
		 */
		protected function sortListByValueList($entityList, $name, array $valueList) {
			$accessor = $this->getAttributeAccessor();

			usort($entityList, function(iEntity $firstEntity, iEntity $secondEntity) use ($name, $valueList, $accessor) {
				$firstPosition = array_search($accessor->accessOne($firstEntity, $name), $valueList);
				$secondPosition = array_search($accessor->accessOne($secondEntity, $name), $valueList);
				return ($firstPosition < $secondPosition) ? -1 : 1;
			});

			return $entityList;
		}

		/**
		 * Определяет есть ли сущность в коллекции
		 * @param iEntity $entity сущность
		 * @return bool
		 */
		protected function isPushed(iEntity $entity) {
			return isset($this->idList[$entity->getId()]);
		}

		/**
		 * Фильтрует заданный список сущностей по значению атрибута
		 * @param iEntity[] $entityList список сущностей
		 * @param string $name атрибут
		 * @param mixed $value значение
		 * @param string $compareType тип сравнения, смотри константы класса
		 * @return iEntity[]
		 * @throws \ErrorException
		 */
		private function filterListBy(array $entityList, $name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS) {
			$accessor = $this->getAttributeAccessor();
			$result = [];

			foreach ($entityList as $index => $entity) {

				$entityValue = $accessor->accessOne($entity, $name);

				if ($this->assert($entityValue, $value, $compareType)) {
					$result[$index] = $entity;
				}
			}

			return $result;
		}

		/**
		 * Сортирует заданный список сущностей по значению атрибута
		 * @param iEntity[] $entityList список сущностей
		 * @param string $name атрибут
		 * @param string $sortType тип сортировки, смотри константы
		 * @return iEntity[]
		 * @throws \ErrorException
		 */
		protected function sortListBy(array $entityList, $name, $sortType = Sort::SORT_TYPE_ASC) {
			$attributeList = $this->getAttributeAccessor()
				->accessMany($entityList, $name);
			$attributeList = $this->sortAttributeList($attributeList, $sortType);
			return $this->sortListByValueList($entityList, $name, $attributeList);
		}

		/**
		 * Сравнивает значения
		 * @param mixed $firstValue первое значение
		 * @param mixed $secondValue второе значение
		 * @param string $type тип сравнения
		 * @return bool
		 * @throws \ErrorException
		 */
		private function assert($firstValue, $secondValue, $type) {
			switch ($type) {
				case Filter::COMPARE_TYPE_EQUALS : {
					return $firstValue == $secondValue;
				}
				case Filter::COMPARE_TYPE_NOT_EQUALS : {
					return $firstValue != $secondValue;
				}
				case Filter::COMPARE_TYPE_LIKE : {
					$firstValue = mb_convert_case((string) $firstValue, MB_CASE_LOWER);
					$secondValue = mb_convert_case((string) $secondValue, MB_CASE_LOWER);
					return contains($firstValue, $secondValue);
				}
				case Filter::COMPARE_TYPE_NOT_LIKE : {
					$firstValue = mb_convert_case((string) $firstValue, MB_CASE_LOWER);
					$secondValue = mb_convert_case((string) $secondValue, MB_CASE_LOWER);
					return !contains($firstValue, $secondValue);
				}
				case Filter::COMPARE_TYPE_IN_LIST : {
					$secondValue = (array) $secondValue;
					return in_array($firstValue, $secondValue);
				}
				case Filter::COMPARE_TYPE_NOT_IN_LIST : {
					$secondValue = (array) $secondValue;
					return !in_array($firstValue, $secondValue);
				}
				case Filter::COMPARE_TYPE_IS_NULL : {
					return $firstValue === null;
				}
				case Filter::COMPARE_TYPE_IS_NOT_NULL : {
					return $firstValue !== null;
				}
				case Filter::COMPARE_TYPE_LESS : {
					return $firstValue < $secondValue;
				}
				case Filter::COMPARE_TYPE_LESS_OR_EQUALS : {
					return $firstValue <= $secondValue;
				}
				case Filter::COMPARE_TYPE_MORE : {
					return $firstValue > $secondValue;
				}
				case Filter::COMPARE_TYPE_MORE_OR_EQUALS : {
					return $firstValue >= $secondValue;
				}
				case Filter::COMPARE_TYPE_BETWEEN : {
					$secondValue = (array) $secondValue;
					$startRange = array_shift($secondValue);
					$endRange = array_shift($secondValue);
					return ($firstValue > $startRange && $firstValue < $endRange);
				}
				default : {
					throw new \ErrorException(sprintf('Incorrect compare type: "%s"', $type));
				}
			}
		}

		/**
		 * Сортирует список аттрибутов
		 * @param array $attributeList список аттрибутов
		 * @example
		 *
		 * [
		 *		индекс массива => 'значение атрибута'
		 * ]
		 *
		 * @param string $sortType тип сортировки, смотри константы
		 * @return array
		 * @throws \ErrorException
		 */
		protected function sortAttributeList(array $attributeList, $sortType) {
			switch ($sortType) {
				case Sort::SORT_TYPE_ASC : {
					sort($attributeList, SORT_NATURAL | SORT_FLAG_CASE);
					break;
				}
				case Sort::SORT_TYPE_DESC : {
					sort($attributeList, SORT_NATURAL | SORT_FLAG_CASE);
					$attributeList = array_reverse($attributeList);
					break;
				}
				case Sort::SORT_TYPE_RAND : {
					$keys = array_keys($attributeList);
					shuffle($keys);
					$newAttributeList = [];

					foreach ($keys as $key) {
						$newAttributeList[$key] = $attributeList[$key];
					}

					$attributeList = $newAttributeList;
					break;
				}
				default : {
					throw new \ErrorException(sprintf('Incorrect sort type: "%s"', $sortType));
				}
			}

			return $attributeList;
		}

		/**
		 * Возвращает текущую позицию итерирования
		 * @return int
		 */
		private function getPosition() {
			return $this->position;
		}

		/**
		 * Устанавливает текущую позицию итерирования
		 * @param int $position позиция
		 * @return $this
		 */
		private function setPosition($position) {
			$this->position = $position;
			return $this;
		}

		/**
		 * Инкрементирует текущую позицию итерирования
		 * @return $this
		 */
		private function incrementPosition() {
			$position = $this->getPosition();
			return $this->setPosition($position + 1);
		}
	}