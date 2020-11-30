<?php
	namespace UmiCms\System\Selector\Order\Attribute\Property;

	use UmiCms\System\Selector\Order\Attribute\Property;

	/**
	 * Класс сортировки по глобальному порядку страниц в иерархии
	 * @package UmiCms\System\Selector\Order\Attribute\Property
	 */
	class GlobalOrd extends Property implements iGlobalOrd {

		/** @var \IConnection $connection подключение к бд */
		protected $connection;

		/** @inheritDoc */
		public function setConnection(\IConnection $connection) : iGlobalOrd {
			$this->connection = $connection;
			return $this;
		}

		/** @inheritDoc */
		public function beforeQuery(\selectorExecutor $executor) : void {
			$pageIdList = $executor->getHierarchyElementCandidateIdList();
			$objectTypeIdList = $executor->getObjectTypeIdList();
			$hierarchyTypeIdList = $executor->getHierarchyTypeIdList();

			if (count($pageIdList) === 0 && count($objectTypeIdList) === 0 && count($hierarchyTypeIdList) === 0) {
				return;
			}

			$this->dropTemporaryTable();

			if ($pageIdList) {
				$this->createTemporaryTableByPageIdList($pageIdList);
				return;
			}

			if ($objectTypeIdList) {
				$this->createTemporaryTableByObjectTypeIdList($objectTypeIdList);
				return;
			}

			if ($hierarchyTypeIdList) {
				$this->createTemporaryTableByHierarchyTypeIdList($hierarchyTypeIdList);
				return;
			}
		}

		/** @inheritDoc */
		public function afterQuery(\selectorExecutor $executor) : void {
			$this->dropTemporaryTable();
		}

		/**
		 * Создает временную таблицу для сортировки по глобальному порядку в иерархии по идентификаторам страниц
		 * @param array $pageIdList список идентификаторов сортируемых страниц
		 * @throws \selectorException
		 */
		private function createTemporaryTableByPageIdList(array $pageIdList) : void {
			$pageIdListStatement = implode(', ', $pageIdList);
			$sql = <<<SQL
CREATE TEMPORARY TABLE `cms3_hierarchy_global_ord`
SELECT child.`id`, child.`ord`, sum(parents.`ord`) as parent_ord
FROM `cms3_hierarchy` as child
LEFT JOIN `cms3_hierarchy_relations` relations ON relations.child_id = child.`id`
LEFT JOIN `cms3_hierarchy` parents ON parents.id = relations.rel_id
WHERE child.`id` IN ($pageIdListStatement)
GROUP BY child.`id`;
SQL;
			try {
				$this->connection->query($sql);
			} catch (\databaseException $exception) {
				throw new \selectorException('Cannot create temporary table `cms3_hierarchy_global_ord` by page id list');
			}
		}

		/**
		 * Создает временную таблицу для сортировки по глобальному порядку в иерархии по идентификаторам объектных типов
		 * @param array $objectTypeIdList список идентификаторов объектных типов, к которым принадлежат сортируемые страницы
		 * @throws \selectorException
		 */
		private function createTemporaryTableByObjectTypeIdList(array $objectTypeIdList) : void {
			$objectTypeIdStatement = implode(', ', $objectTypeIdList);
			$sql = <<<SQL
CREATE TEMPORARY TABLE `cms3_hierarchy_global_ord`
SELECT child.`id`, child.`ord`, sum(parents.`ord`) as parent_ord
FROM `cms3_hierarchy` as child
LEFT JOIN `cms3_hierarchy_relations` relations ON relations.child_id = child.`id`
LEFT JOIN `cms3_hierarchy` parents ON parents.id = relations.rel_id
WHERE child.`id` IN (SELECT `id` FROM `cms3_hierarchy` WHERE `obj_id` IN (
	SELECT `id` FROM `cms3_objects` WHERE `type_id` IN ($objectTypeIdStatement)
))
GROUP BY child.`id`;
SQL;
			try {
				$this->connection->query($sql);
			} catch (\databaseException $exception) {
				throw new \selectorException('Cannot create temporary table `cms3_hierarchy_global_ord` by object type id list');
			}
		}

		/**
		 * Создает временную таблицу для сортировки по глобальному порядку в иерархии по идентификаторам иерархических
		 * (базовых) типов
		 * @param array $hierarchyTypeIdList список идентификаторов иерархических (базовых) типов, к которым принадлежат
		 * сортируемые страницы
		 * @throws \selectorException
		 */
		private function createTemporaryTableByHierarchyTypeIdList(array $hierarchyTypeIdList) : void {
			$hierarchyTypeIdStatement = implode(', ', $hierarchyTypeIdList);
			$sql = <<<SQL
CREATE TEMPORARY TABLE `cms3_hierarchy_global_ord`
SELECT child.`id`, child.`ord`, sum(parents.`ord`) as parent_ord
FROM `cms3_hierarchy` as child
LEFT JOIN `cms3_hierarchy_relations` relations ON relations.child_id = child.`id`
LEFT JOIN `cms3_hierarchy` parents ON parents.id = relations.rel_id
WHERE child.`id` IN (SELECT `id` FROM `cms3_hierarchy` WHERE `type_id` IN ($hierarchyTypeIdStatement))
GROUP BY child.`id`;
SQL;
			try {
				$this->connection->query($sql);
			} catch (\databaseException $exception) {
				throw new \selectorException('Cannot create temporary table `cms3_hierarchy_global_ord` by hierarchy type id list');
			}
		}

		/**
		 * Удаляет временную таблицу для сортировки по глобальному порядку в иерархии
		 * @throws \selectorException
		 */
		private function dropTemporaryTable() : void {
			$sql = <<<SQL
DROP TEMPORARY TABLE IF EXISTS `cms3_hierarchy_global_ord`;
SQL;
			try {
				$this->connection->query($sql);
			} catch (\databaseException $exception) {
				throw new \selectorException('Cannot drop temporary table `cms3_hierarchy_global_ord`');
			}
		}
	}