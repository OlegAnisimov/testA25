<?php
	namespace UmiCms\System\Orm\Entity;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Map\Sort;
	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Orm\Entity\Repository\iHistory;
	use UmiCms\System\Orm\Entity\Mapper\tInjector as tMapperInjector;
	use UmiCms\System\Orm\Entity\Schema\tInjector as tSchemaInjector;
	use UmiCms\System\Orm\Entity\Factory\tInjector as tFactoryInjector;
	use UmiCms\System\Orm\Entity\Builder\tInjector as tBuilderInjector;
	use UmiCms\System\Orm\Entity\Attribute\Accessor\tInjector as tAttributeAccessorInjector;

	/**
	 * Класс абстрактного репозитория сущностей
	 * @package UmiCms\System\Orm\Entity
	 */
	abstract class Repository implements iRepository {

		use tMapperInjector;
		use tSchemaInjector;
		use tFactoryInjector;
		use tBuilderInjector;
		use tAttributeAccessorInjector;

		/** @var \IConnection $connection подключение к бд */
		private $connection;

		/** @var iHistory $history история репозитория */
		private $history;

		/** @inheritDoc */
		public function __construct(
			\IConnection $connection,
			iHistory $history,
			iSchema $schema,
			iAccessor $accessor,
			iFactory $factory,
			iBuilder $builder
		) {
			$this->setConnection($connection)
				->setHistory($history)
				->setSchema($schema)
				->setAttributeAccessor($accessor)
				->setFactory($factory)
				->setBuilder($builder);
		}

		/** @inheritDoc */
		public function get($id) {
			$table = $this->getTable();
			$id = (int) $id;
			$sql = <<<SQL
SELECT * FROM `$table` WHERE `id` = $id LIMIT 0,1;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			$entity = $this->mapEntity($result);

			if ($entity instanceof iEntity) {
				$this->getHistory()
					->logGet(iMapper::ID, $entity->getId());
			}

			return $entity;
		}

		/** @inheritDoc */
		public function getAll() {
			$table = $this->getTable();
			$sql = <<<SQL
SELECT * FROM `$table`;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			$entityList = $this->mapEntityList($result);
			$this->logGetAll($entityList);
			return $entityList;
		}

		/** @inheritDoc */
		public function getListByIdList(array $idList) {
			return $this->getListByValueList(iMapper::ID, $idList);
		}

		/** @inheritDoc */
		public function getListByValueList($name, array $valueList) {
			if (isEmptyArray($valueList)) {
				return [];
			}

			$table = $this->getTable();
			$valueListCondition = $this->glueValueListCondition($valueList);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE `$name` IN $valueListCondition;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			$entityList = $this->mapEntityList($result);

			if (count($entityList) > 0) {
				$this->getHistory()
					->logGet($name, $valueList);
			}

			return $entityList;
		}

		/** @inheritDoc */
		public function getListBy($name, $value) {
			$offset = null;
			$limit = null;
			return $this->getListSliceBy($name, $value, $offset, $limit, []);
		}

		/** @inheritDoc */
		public function getListSliceBy($name, $value, $offset, $limit, array $orderMap) {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueFilter($name, $value);
			$limit = $this->getLimitCondition($offset, $limit);
			$order = $this->getOrderCondition($orderMap);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition $order $limit;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			$entityList = $this->mapEntityList($result);

			if (!isEmptyArray($entityList)) {
				$this->getHistory()
					->logGet($name, $value);
			}

			return $entityList;
		}

		/** @inheritDoc */
		public function getCountBy($name, $value) {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueFilter($name, $value);
			$sql = <<<SQL
SELECT count(`id`) FROM `$table` WHERE $condition
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->fetch();
			return (int) array_shift($result);
		}

		/** @inheritDoc */
		public function getCountByEqualityMap(array $equalityMap) : int {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueMapFilter($equalityMap);
			$sql = <<<SQL
SELECT count(`id`) FROM `$table` WHERE $condition;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->fetch();
			return (int) array_shift($result);
		}

		/** @inheritDoc */
		public function getCountByFilterMap(array $map) : int {
			$table = $this->getTable();
			$condition = $this->getNameExpressionValueMapFilter($map);
			$sql = <<<SQL
SELECT count(`id`) FROM `$table` WHERE $condition;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->fetch();
			return (int) array_shift($result);
		}

		/** @inheritDoc */
		public function getListByEqualityMap(array $equalityMap) {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueMapFilter($equalityMap);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			$entityList = $this->mapEntityList($result);

			if (!isEmptyArray($entityList)) {
				$this->getHistory()->logEqualityMap($equalityMap);
			}

			return $entityList;
		}

		/** @inheritDoc */
		public function getListSliceByFilterMap(array $map, int $offset, int $limit) : array {
			$table = $this->getTable();
			$condition = $this->getNameExpressionValueMapFilter($map);
			$limit = $this->getLimitCondition($offset, $limit);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition $limit;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntityList($result);
		}

		/** @inheritDoc */
		public function getSortedListSliceByFilterMap(array $filterMap, int $offset, int $limit, array $orderMap) : array {
			$table = $this->getTable();
			$condition = $this->getNameExpressionValueMapFilter($filterMap);
			$limit = $this->getLimitCondition($offset, $limit);
			$order = $this->getOrderCondition($orderMap);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition $order $limit;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntityList($result);
		}

		/** @inheritDoc */
		public function getListSliceByEqualityMap(array $equalityMap, int $offset, int $limit) : array {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueMapFilter($equalityMap);
			$limit = $this->getLimitCondition($offset, $limit);
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition $limit;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntityList($result);
		}

		/** @inheritDoc */
		public function getOneByEqualityMap(array $equalityMap) {
			$conditionList = [];

			foreach ($equalityMap as $name => $value) {
				$conditionList[] = $this->getNameEqualsValueFilter($name, $value);
			}

			$condition = implode(' AND ', $conditionList);
			$table = $this->getTable();
			$sql = <<<SQL
SELECT * FROM `$table` WHERE $condition LIMIT 0,1;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntity($result);
		}

		/** @inheritDoc */
		public function save(iEntity $entity) {
			if (!$this->isValidEntity($entity)) {
				throw new \ErrorException('Incorrect entity given');
			}

			if ($entity->hasId() && !$entity->createWithId()) {
				$entity = $this->update($entity);
			} else {
				$entity = $this->create($entity);
			}

			return $entity->setUpdated(false);
		}

		/** @inheritDoc */
		public function delete($id) {
			if (!is_numeric($id)) {
				return $this;
			}

			$table = $this->getTable();
			$id = (int) $id;
			$sql = <<<SQL
DELETE FROM `$table` WHERE `id` = $id;
SQL;
			$connection = $this->getConnection();
			$connection->query($sql);

			if ($connection->affectedRows()) {
				$this->getHistory()
					->logDelete($id);
			}

			return $this;
		}

		/** @inheritDoc */
		public function deleteList(array $idList) {
			if (isEmptyArray($idList)) {
				return $this;
			}

			$table = $this->getTable();
			$idListCondition = $this->glueValueListCondition($idList);
			$sql = <<<SQL
DELETE FROM `$table` WHERE `id` IN $idListCondition;
SQL;
			$connection = $this->getConnection();
			$connection->query($sql);

			if ($connection->affectedRows()) {
				$history = $this->getHistory();
				foreach ($idList as $id) {
					$history->logDelete($id);
				}
			}

			return $this;
		}

		/** @inheritDoc */
		public function deleteByEqualityMap(array $equalityMap) : int {
			$table = $this->getTable();
			$condition = $this->getNameEqualsValueMapFilter($equalityMap);
			$sql = <<<SQL
DELETE FROM `$table` WHERE $condition;
SQL;
			$connection = $this->getConnection();
			$connection->query($sql);
			return (int) $connection->affectedRows();
		}

		/** @inheritDoc */
		public function clear() {
			$table = $this->getTable();
			$sql = <<<SQL
DELETE FROM `$table`;
SQL;
			$this->getConnection()
				->query($sql);
			return $this;
		}

		/** @inheritDoc */
		public function getHistory() {
			return $this->history;
		}

		/**
		 * Возвращает имя таблицы
		 * @return string
		 * @throws \ReflectionException
		 */
		protected function getTable() {
			return $this->getSchema()->getContainerName();
		}

		/**
		 * Определяет валидность сущности
		 * @param mixed $entity сущность
		 * @return bool
		 * @throws \ErrorException
		 */
		abstract protected function isValidEntity($entity);

		/**
		 * Формирует сущность из результатов выборки
		 * @param \IQueryResult $queryResult результат выборки
		 * @return null|iEntity
		 * @throws \ErrorException
		 */
		protected function mapEntity(\IQueryResult $queryResult) {
			if ($queryResult->length() === 0) {
				return null;
			}

			$entity = $this->getFactory()
				->create();
			$attributeList = $queryResult->fetch();
			return $this->getBuilder()
				->buildAttributesList($entity, $attributeList);
		}

		/**
		 * Формирует список сущностей из результатов выборки
		 * @param \IQueryResult $queryResult результат выборки
		 * @return iEntity[]
		 * @throws \ErrorException
		 */
		protected function mapEntityList(\IQueryResult $queryResult) {
			$result = [];

			if ($queryResult->length() === 0) {
				return $result;
			}

			$factory = $this->getFactory();
			$builder = $this->getBuilder();

			foreach ($queryResult as $row) {
				$entity = $factory->create();
				$builder->buildAttributesList($entity, $row);
				$result[] = $entity;
			}

			return $result;
		}

		/**
		 * Обновляет строку сущности
		 * @param iEntity $entity сущность
		 * @return iEntity
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		protected function update(iEntity $entity) {
			if (!$entity->isUpdated()) {
				return $entity;
			}

			$table = $this->getTable();
			$condition = $this->getUpdateCondition($entity);
			$id = (int) $entity->getId();
			$sql = <<<SQL
UPDATE `$table` SET $condition WHERE `id` = $id;
SQL;
			$connection = $this->getConnection();
			$connection->query($sql);

			if ($connection->affectedRows()) {
				$this->getHistory()
					->logUpdate($id);
			}

			return $entity;
		}

		/**
		 * Возвращает часть sql выражения для обновления строки сущности
		 * @param iEntity $entity сущность
		 * @return string
		 */
		protected function getUpdateCondition(iEntity $entity) {
			$condition = '';

			foreach ($this->getEscapedRow($entity) as $index => $value) {
				$value = ($value === null) ? 'NULL' : "'$value'";
				$condition .= " `$index` = $value,";
			}

			return rtrim($condition, ',');
		}

		/**
		 * Возвращает экранированные данные сущности
		 * @param iEntity $entity сущность
		 * @return array
		 *
		 * [
		 *		'field' => escaped value
		 * ]
		 */
		protected function getEscapedRow(iEntity $entity) {
			$attributeAccessor = $this->getAttributeAccessor();
			$row = [];
			$connection = $this->getConnection();

			foreach ($attributeAccessor->accessOneToAll($entity) as $index => $value) {
				$row[$index] = ($value === null) ? $value : $connection->escape($value);
			}

			return $row;
		}

		/**
		 * Создает строку сущности
		 * @param iEntity $entity сущность
		 * @return iEntity
		 * @throws \ErrorException
		 * @throws \databaseException
		 * @throws \ReflectionException
		 */
		protected function create(iEntity $entity) {
			$table = $this->getTable();
			$condition = $this->getInsertCondition($entity);
			$connection = $this->getConnection();
			$sql = <<<SQL
INSERT INTO `$table` $condition
SQL;
			$connection->query($sql);
			$id = $connection->insertId();

			if ($id) {
				$entity->setId($id);
			}

			$this->getHistory()
				->logCreate($entity->getId());

			return $entity;
		}

		/**
		 * Возвращает часть sql выражения для вставки строки сущности
		 * @param iEntity $entity сущность
		 * @return string
		 */
		protected function getInsertCondition(iEntity $entity) {
			$escapedRow = $this->getEscapedRow($entity);
			$fieldList = array_keys($escapedRow);
			$condition = '(`' . implode('`, `', $fieldList) . '`)';

			$valueList = [];

			foreach ($escapedRow as $index => $value) {
				$valueList[] = ($value === null) ? 'NULL' : "'$value'";
			}

			return $condition . ' VALUES (' . implode(', ', $valueList) . ')';
		}

		/**
		 * Подготавливает список значений для вставки в sql запрос
		 *
		 * array(1, 2, 3, 4) => '1, 2, 3, 4';
		 *
		 * @param array $valueList список значений
		 * @return string
		 */
		protected function glueValueListCondition(array $valueList) {
			if (isEmptyArray($valueList)) {
				return '()';
			}

			$valueList = array_map(function($id) {
				return $this->getConnection()->escape($id);
			}, $valueList);
			$valueList = array_unique($valueList);
			return "('" . implode("', '", $valueList) . "')";
		}

		/**
		 * Записывает в историю получение списка сущностей по всем возможным параметрам.
		 * Имеет смысл только при получении полного списка сущностей.
		 * @param iEntity[] $entityList список сущностей
		 * @return $this
		 */
		protected function logGetAll(array $entityList) {
			$history = $this->getHistory();

			if (!isEmptyArray($entityList)) {
				$history->logGetAll(count($entityList));
			}

			$attributeAccessor = $this->getAttributeAccessor();

			foreach ($entityList as $entity) {
				foreach ($attributeAccessor->accessOneToAll($entity) as $name => $value) {
					$history->logGet($name, $value);
				}
			}

			return $this;
		}

		/**
		 * Возвращает подключение к бд
		 * @return \IConnection
		 */
		protected function getConnection() {
			return $this->connection;
		}

		/**
		 * Устанавливает подключение к бд
		 * @param \IConnection $connection подключение к бд
		 * @return $this
		 */
		protected function setConnection(\IConnection $connection) {
			$this->connection = $connection;
			return $this;
		}

		/**
		 * Устанавливает историю репозитория
		 * @param iHistory $history история репозитория
		 * @return $this
		 */
		protected function setHistory(iHistory $history) {
			$this->history = $history;
			return $this;
		}

		/**
		 * Возвращает фильтр по условия с заданным типом сравнения столбца
		 * @param string $name имя столбца
		 * @param string $type тип сравнения
		 * @param string|int|float|string[]|int[]|float[] $value значение
		 * @return string
		 * @throws \ErrorException
		 */
		private function getNameExpressionValueFilter(string $name, string $type, $value) : string {
			switch ($type) {
				case Filter::COMPARE_TYPE_EQUALS : {
					return $this->getNameEqualsValueFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_NOT_EQUALS : {
					return $this->getNameNotEqualsValueFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_LIKE : {
					return $this->getNameLikeValueFilter($name, (string) $value);
				}
				case Filter::COMPARE_TYPE_NOT_LIKE : {
					return $this->getNameNotLikeValueFilter($name, (string) $value);
				}
				case Filter::COMPARE_TYPE_IN_LIST : {
					return $this->getNameInValueListFilter($name, (array) $value);
				}
				case Filter::COMPARE_TYPE_NOT_IN_LIST : {
					return $this->getNameNotInValueListFilter($name, (array) $value);
				}
				case Filter::COMPARE_TYPE_IS_NULL : {
					return $this->getNameIsNullFilter($name);
				}
				case Filter::COMPARE_TYPE_IS_NOT_NULL : {
					return $this->getNameIsNotNullFilter($name);
				}
				case Filter::COMPARE_TYPE_LESS : {
					return $this->getNameLessFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_LESS_OR_EQUALS : {
					return $this->getNameLessOrEqualsFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_MORE : {
					return $this->getNameMoreFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_MORE_OR_EQUALS : {
					return $this->getNameMoreOrEqualsFilter($name, $value);
				}
				case Filter::COMPARE_TYPE_BETWEEN : {
					return $this->getNameBetweenFilter($name, (array) $value);
				}
				default : {
					throw new \ErrorException(sprintf('Incorrect compare type: "%s"', $type));
				}
			}
		}

		/**
		 * Возвращает фильтр по условию равенства значению
		 * @param string $name имя столбца
		 * @param string|int|float $value значение
		 * @return string
		 */
		private function getNameEqualsValueFilter($name, $value) {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` = '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию неравенства значению
		 * @param string $name имя столбца
		 * @param string|int|float $value значение
		 * @return string
		 */
		private function getNameNotEqualsValueFilter(string $name, $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` != '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию подобия значения
		 * @param string $name имя столбца
		 * @param string $value значение
		 * @return string
		 */
		private function getNameLikeValueFilter(string $name, string $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape('%' . $value . '%');
			return sprintf("`%s` LIKE '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию неподобия значения
		 * @param string $name имя столбца
		 * @param string $value значение
		 * @return string
		 */
		private function getNameNotLikeValueFilter(string $name, string $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape('%' . $value . '%');
			return sprintf("`%s` NOT LIKE '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию вхождения значения
		 * @param string $name имя столбца
		 * @param string[]|int[]|float[] $valueList список значений
		 * @return string
		 */
		private function getNameInValueListFilter(string $name, array $valueList) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $this->glueValueListCondition($valueList);
			return sprintf("`%s` IN %s", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию невхождения значения
		 * @param string $name имя столбца
		 * @param string[]|int[]|float[] $valueList список значений
		 * @return string
		 */
		private function getNameNotInValueListFilter(string $name, array $valueList) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $this->glueValueListCondition($valueList);
			return sprintf("`%s` NOT IN %s", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию отсутствия значения
		 * @param string $name имя столбца
		 * @return string
		 */
		private function getNameIsNullFilter(string $name) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			return sprintf("`%s` IS NULL", $escapedName);
		}

		/**
		 * Возвращает фильтр по условию наличия значения
		 * @param string $name имя столбца
		 * @return string
		 */
		private function getNameIsNotNullFilter(string $name) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			return sprintf("`%s` IS NOT NULL", $escapedName);
		}

		/**
		 * Возвращает фильтр по условию меньше значения
		 * @param string $name имя столбца
		 * @param int|float $value значение
		 * @return string
		 */
		private function getNameLessFilter(string $name, $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` < '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию меньше или равно значению
		 * @param string $name имя столбца
		 * @param int|float $value значение
		 * @return string
		 */
		private function getNameLessOrEqualsFilter(string $name, $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` <= '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию больше значения
		 * @param string $name имя столбца
		 * @param int|float $value значение
		 * @return string
		 */
		private function getNameMoreFilter(string $name, $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` > '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию больше или равно значению
		 * @param string $name имя столбца
		 * @param int|float $value значение
		 * @return string
		 */
		private function getNameMoreOrEqualsFilter(string $name, $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$escapedValue = $connection->escape($value);
			return sprintf("`%s` >= '%s'", $escapedName, $escapedValue);
		}

		/**
		 * Возвращает фильтр по условию между значений
		 * @param string $name имя столбца
		 * @param int[]|float[] $value пара значений
		 * @return string
		 */
		private function getNameBetweenFilter(string $name, array $value) : string {
			$connection = $this->getConnection();
			$escapedName = $connection->escape($name);
			$startRange = $connection->escape(array_shift($value));
			$endRange = $connection->escape(array_shift($value));
			return sprintf("`%s` BETWEEN '%s' AND '%s'", $escapedName, $startRange, $endRange);
		}

		/**
		 * Возвращает фильтр по карте соответствия стобцов значениям
		 * @param array $equalityMap array $equalityMap карта соответствия
		 * @return string
		 */
		private function getNameEqualsValueMapFilter(array $equalityMap) : string {
			$conditionList = [];

			foreach ($equalityMap as $name => $value) {
				$conditionList[] = $this->getNameEqualsValueFilter($name, $value);
			}

			return implode(' AND ', $conditionList);
		}

		/**
		 * Возвращает фильтр по карте фильтра
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
		 * @return string
		 * @throws \ErrorException
		 */
		private function getNameExpressionValueMapFilter(array $map) : string {
			$conditionList = [];

			foreach ($map as $name => $expression) {
				if (!is_array($expression) || count($expression) !== 1) {
					$value = $expression;
					$compareType = Filter::COMPARE_TYPE_EQUALS;
				} else {
					$value = getFirstValue($expression);
					$compareType = getFirstValue(array_keys($expression));
				}

				$conditionList[] = $this->getNameExpressionValueFilter($name, $compareType, $value);
			}

			return implode(' AND ', $conditionList);
		}

		/**
		 * Возвращает выражение ограничения на размер выборки
		 * @param int|null $offset смещение выборки
		 * @param int|null $limit размер выборки
		 * @return string
		 */
		private function getLimitCondition($offset = null, $limit = null) {
			if ($offset === null && $limit === null) {
				return '';
			}

			$offset = ($offset === null) ? 0 : (int) $offset;
			$limit = (int) $limit;
			return sprintf('LIMIT %d, %d', $offset, $limit);
		}

		/**
		 * Возвращает выражение сортировки
		 * @param array $orderMap карта сортировки
		 * @return string
		 * @throws \ErrorException
		 */
		private function getOrderCondition(array $orderMap) {
			if ($orderMap === []) {
				return '';
			}

			$parts = [];
			$connection = $this->getConnection();

			foreach ($orderMap as $name => $type) {
				$name = $connection->escape($name);
				$type = $connection->escape($type);

				if (!in_array($type, [Sort::SORT_TYPE_ASC, Sort::SORT_TYPE_DESC, Sort::SORT_TYPE_RAND])) {
					throw new \ErrorException(sprintf('Incorrect sort type given: "%s"', $type));
				}

				$parts[] = sprintf('`%s` %s', $name, mb_convert_case($type, MB_CASE_UPPER));
			}

			return sprintf('ORDER BY %s', implode(', ', $parts));
		}
	}