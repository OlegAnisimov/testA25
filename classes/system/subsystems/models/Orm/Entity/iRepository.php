<?php
	namespace UmiCms\System\Orm\Entity;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Repository\iHistory;

	/**
	 * Интерфейс репозитория сущностей
	 * @package UmiCms\System\Orm\Entity
	 */
	interface iRepository {

		/**
		 * Конструктор
		 * @param \IConnection $connection подключение к бд
		 * @param iHistory $history история репозитория
		 * @param iSchema $schema схема хранения сущности
		 * @param iAccessor $accessor аксессор атрибутов сущности
		 * @param iFactory $factory фабрика сущности
		 * @param iBuilder $builder строитель сущности
		 */
		public function __construct(
			\IConnection $connection,
			iHistory $history,
			iSchema $schema,
			iAccessor $accessor,
			iFactory $factory,
			iBuilder $builder
		);

		/**
		 * Возвращает сущность с заданным идентификатором
		 * @param int $id идентификатор
		 * @return iEntity|null
		 * @throws \databaseException
		 * @throws \ReflectionException
		 * @throws \ErrorException
		 */
		public function get($id);

		/**
		 * Возвращает полный список сущностей
		 * @return iEntity[]
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		public function getAll();

		/**
		 * Возвращает список сущностей с заданными идентификаторами
		 * @param int[] $idList список идентификаторов
		 * @return iEntity[]
		 * @throws \databaseException
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getListByIdList(array $idList);

		/**
		 * Возвращает список сущностей с заданными значениями поля
		 * @param string $name имя поля
		 * @param array $valueList список значений
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		public function getListByValueList($name, array $valueList);

		/**
		 * Возвращает список сущностей с заданным значением указанного атрибута
		 * @param string $name имя поля
		 * @param mixed $value значение
		 * @return iEntity[]
		 * @throws \databaseException
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		public function getListBy($name, $value);

		/**
		 * Возвращает часть списка сущностей с заданным значением указанного атрибута
		 * @param string $name имя поля
		 * @param mixed $value значение
		 * @param int|null $offset смещение части списка
		 * @param int|null $limit размер части списка
		 * @param array $orderMap $orderMap карта сортировки
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListSliceBy($name, $value, $offset, $limit, array $orderMap);

		/**
		 * Возвращает количество строк с заданным значением указанного поля
		 * @param string $name имя поля значение
		 * @param mixed $value
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountBy($name, $value);

		/**
		 * Возвращает количество строк, соответствующих карте
		 * @param array $equalityMap карта соответствия
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByEqualityMap(array $equalityMap) : int;

		/**
		 * Возвращает количество строк, соответствующих карте
		 * @param array $map карта фильтрации
		 * @return int
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getCountByFilterMap(array $map) : int;

		/**
		 * Возвращает список сущностей с параметрами, удовлетворяющими карте соответствия
		 * @param array $equalityMap карта соответствия
		 * @return iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListByEqualityMap(array $equalityMap);

		/**
		 * Возвращает часть списка сущностей с параметрами, удовлетворяющими карте фильтрации
		 * @param array $map карта фильтрации
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
		 * @param int|null $offset смещение части списка
		 * @param int|null $limit размер части списка
		 * @return array|iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListSliceByFilterMap(array $map, int $offset, int $limit) : array;

		/**
		 * Возвращает часть отсортированного списка сущностей с параметрами, удовлетворяющими карте фильтрации
		 * @param array $filterMap карта фильтрации
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
		 * @param int|null $offset смещение части списка
		 * @param int|null $limit размер части списка
		 * @param array $orderMap
		 * @example:
		 *
		 * [
		 *		'foo' => 'asc',
		 *		'bar' => 'desc'
		 * ]
		 *
		 * @return array|iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getSortedListSliceByFilterMap(array $filterMap, int $offset, int $limit, array $orderMap) : array;

		/**
		 * Возвращает часть списка сущностей с параметрами, удовлетворяющими карте соответствия
		 * @param array $equalityMap карта соответствия
		 * @example:
		 *
		 * [
		 *		'id' => 'Foo'
		 * ]
		 *
		 * @param int|null $offset смещение части списка
		 * @param int|null $limit размер части списка
		 * @return array|iEntity[]
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getListSliceByEqualityMap(array $equalityMap, int $offset, int $limit) : array;

		/**
		 * Возвращает сущность с параметрами, удовлетворяющими карте соответствия
		 * @param array $equalityMap карта соответствия
		 * @return iEntity|null
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function getOneByEqualityMap(array $equalityMap);

		/**
		 * Сохраняет сущность
		 * @param iEntity $entity сущность
		 * @return iEntity
		 * @throws \databaseException
		 * @throws \ReflectionException
		 * @throws \ErrorException
		 */
		public function save(iEntity $entity);

		/**
		 * Удаляет сущность с заданным идентификатором
		 * @param int $id идентификатор
		 * @return $this
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		public function delete($id);

		/**
		 * Удаляет список сущностей с заданными идентификаторами
		 * @param int[] $idList список идентификаторов
		 * @return $this
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		public function deleteList(array $idList);

		/**
		 * Удаляет список сущностей с параметрами, удовлетворяющими карте соответствия
		 * @param array $equalityMap карта соответствия
		 * @return int
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		public function deleteByEqualityMap(array $equalityMap) : int;

		/**
		 * Очищает репозиторий
		 * @return $this
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		public function clear();

		/**
		 * Возвращает историю репозитория
		 * @return iHistory
		 */
		public function getHistory();
	}