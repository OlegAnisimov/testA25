<?php
	namespace UmiCms\System\Orm\Entity;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Map\Sort;
	use UmiCms\System\Orm\Entity\Map\Filter;

	/**
	 * Интерфейс коллекции сущностей
	 * @package UmiCms\System\Orm\Entity
	 */
	interface iCollection extends \Iterator {

		/**
		 * Конструктор
		 * @param iAccessor $attributeAccessor аксессор атрибутов сущностей
		 */
		public function __construct(iAccessor $attributeAccessor);

		/**
		 * Возвращает список сущностей
		 * @return iEntity[]
		 */
		public function getList();

		/**
		 * Возвращает сущность с заданным идентификатором
		 * @param int $id идентификатор
		 * @return iEntity|null
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function get($id);

		/**
		 * Возвращает первую сущность
		 * @return iEntity|null
		 */
		public function getFirst();

		/**
		 * Возвращает список сущностей с заданным значением указанного атрибута
		 * @param string $name атрибут
		 * @param mixed $value значение
		 * @param string $compareType тип сравнения, смотри константы класса
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getListBy($name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS);

		/**
		 * Возвращает список сущностей, отсортированный по заданному атрибуту
		 * @param string $name атрибут
		 * @param string $sortType тип сортировки, смотри константы
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getSortedList($name, $sortType = Sort::SORT_TYPE_ASC);

		/**
		 * Возвращает сущность с заданным значением указанного атрибута
		 * @param string $name атрибут
		 * @param mixed $value значение
		 * @param string $compareType тип сравнения
		 * @return iEntity|null
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getFirstBy($name, $value, $compareType = Filter::COMPARE_TYPE_EQUALS);

		/**
		 * Помещает сущность в коллекцию
		 * @param iEntity $entity сущность
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function push(iEntity $entity);

		/**
		 * Помещает список сущностей в коллекцию
		 * @param iEntity[] $entityList список сущностей
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function pushList(array $entityList);

		/**
		 * Удаляет сущность из коллекции
		 * @param int $id идентификатор сущности
		 * @return iEntity|null
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function pull($id);

		/**
		 * Удаляет список сущностей из коллекции
		 * @param array $idList список идентификаторов
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function pullList(array $idList);

		/**
		 * Удаляет коллекцию сущностей из коллекции
		 * @param iCollection $collection коллекция удаляемых сущностей
		 * @return array|iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function pullCollection(iCollection $collection) : array;

		/**
		 * Фильтрует коллекцию по карте фильтра
		 * @param array $map карта фильтра
		 * @example:
		 *
		 * [
		 *		'id' => [
		 * 			'eq' => 'Foo'
		 * 		],
		 * 		'name' => [
		 * 			'like' => 'Bar'
		 * 		]
		 * ]
		 *
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function filter(array $map);

		/**
		 * Фильтрует коллекцию по карте соответствия
		 * @param array $map карта соответствия
		 * @example:
		 *
		 * [
		 *		'id' => 'Foo',
		 * 		'name' => 'Bar'
		 * ]
		 * @return iCollection
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function filterByEqualityMap(array $map) : iCollection;

		/**
		 * Фильтрует коллекцию по списку карт фильтров
		 * @param array $mapList список карт фильтров
		 * @see $this->filter();
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function filterByList(array $mapList);

		/**
		 * Сортирует коллекцию
		 * @param array $map карта сортировки
		 * @example:
		 *
		 * [
		 *		'id' => 'asc',
		 *		'name' => 'desc',
		 * ]
		 *
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function sort(array $map);

		/**
		 * Сортирует коллекцию по порядку идентификаторов в списке
		 * @param int[] $idList
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function sortByIdList(array $idList);

		/**
		 * Сортирует коллекцию по порядку значений поля в списке
		 * @param string $name имя поля
		 * @param array $valueList список значений
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function sortByValueList($name, array $valueList);

		/**
		 * Извлекает значения поля сущности коллекции
		 * @param string $name имя поля
		 * @return array
		 * @throws \ReflectionException
		 * @throws \ErrorException
		 */
		public function extractField($name);

		/**
		 * Извлекает уникальные значения поля сущности коллекции
		 * @param string $name имя поля
		 * @return array
		 * @throws \ReflectionException
		 * @throws \ErrorException
		 */
		public function extractUniqueField($name);

		/**
		 * Извлекает идентификаторы сущностей коллекции
		 * @return int[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function extractId();

		/**
		 * Срезает часть элементов коллекции
		 * @param int $offset смещение среза
		 * @param int|null $limit длина среза
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function slice($offset, $limit = null);

		/**
		 * Преобразует коллекцию в массив атрибутов сущностей
		 * @return array
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function map();

		/**
		 * Возвращает копию коллекции
		 * @return $this
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function copy();

		/**
		 * Очищает коллекцию
		 * @return $this
		 */
		public function clear();

		/**
		 * Возвращает количество сущностей в коллекции
		 * @return int
		 */
		public function getCount();

		/**
		 * Определяет отсутсвуют ли сущности в коллекции
		 * @return bool
		 */
		public function isEmpty();

		/**
		 * Определяет существуют ли сущности в коллекции
		 * @return bool
		 */
		public function isNotEmpty();

		/**
		 * Обработчик копирования коллекции
		 * @return $this
		 */
		public function __clone();

		/** @deprecated use Filter */
		const COMPARE_TYPE_EQUALS = 'eq';

		/** @deprecated use Filter */
		const COMPARE_TYPE_NOT_EQUALS = 'ne';

		/** @deprecated use Filter */
		const COMPARE_TYPE_LIKE = 'like';

		/** @deprecated use Filter */
		const COMPARE_TYPE_IN_LIST = 'in_list';

		/** @deprecated use Sort */
		const SORT_TYPE_ASC = 'asc';

		/** @deprecated use Sort */
		const SORT_TYPE_DESC = 'desc';
	}