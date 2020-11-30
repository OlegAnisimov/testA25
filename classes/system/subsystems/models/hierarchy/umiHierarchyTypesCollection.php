<?php

	/** Класс-коллекция, который обеспечивает управление иерархическими типами */
	class umiHierarchyTypesCollection extends singleton implements iSingleton, iUmiHierarchyTypesCollection {

		/** @var iUmiHierarchyType[] $typeList список загруженных иерархических типов */
		private $typeList = [];

		/** @inheritDoc */
		protected function __construct() {}

		/**
		 * @inheritDoc
		 * @return iUmiHierarchyTypesCollection|iSingleton
		 */
		public static function getInstance($c = null) {
			return parent::getInstance(__CLASS__);
		}

		/** @inheritDoc */
		public function getType($id) {
			$type = $this->getLoadedType($id);

			if ($type instanceof iUmiHierarchyType) {
				return $type;
			}

			return false;
		}

		/** @inheritDoc */
		public function getTypeByName($name, $ext = false) {
			if ($name == 'content' and $ext == 'page') {
				$ext = false;
			}

			foreach ($this->getTypesList() as $type) {
				if ($type->getName() == $name && $ext === false) {
					return $type;
				}
				if ($type->getName() == $name && $type->getExt() == $ext && $ext !== false) {
					return $type;
				}
			}

			return false;
		}

		/** @inheritDoc */
		public function getTypesByModules($modules) {
			$modules = (array) $modules;
			return array_filter(
				$this->getTypesList(),
				function ($type) use ($modules) {
					/** @var iUmiHierarchyType $type */
					return in_array($type->getName(), $modules);
				}
			);
		}

		/** @inheritDoc */
		public function addType($name, $title, $ext = '', bool $hidePages = false) {
			$type = $this->getTypeByName($name, $ext);

			if ($type instanceof iUmiHierarchyType) {
				$type->setTitle($title);
				return $type->getId();
			}

			$connection = ConnectionPool::getInstance()
				->getConnection();
			$escapedName = $connection->escape($name);
			$sql = <<<SQL
INSERT INTO `cms3_hierarchy_types` (`name`) VALUES('$escapedName')
SQL;

			$connection->query($sql);
			$typeId = $connection->insertId();

			try {
				$type = new umiHierarchyType($typeId);
				$type->setTitle($title);
				$type->setExt($ext);
				$type->setHidePages($hidePages)
					->commit();
			} catch (Exception $exception) {
				$this->delType($typeId);
				\umiExceptionHandler::report($exception);
			}

			$this->setLoadedType($type);
			return $typeId;
		}

		/** @inheritDoc */
		public function delType($id) {
			if (!$this->isExists($id)) {
				return false;
			}

			$this->unsetLoadedType($id);

			$id = (int) $id;
			$sql = "DELETE FROM `cms3_hierarchy_types` WHERE `id` = $id";
			ConnectionPool::getInstance()
				->getConnection()
				->query($sql);

			return true;
		}

		/** @inheritDoc */
		public function isExists($id) {
			if (!is_string($id) && !is_int($id)) {
				return false;
			}

			return array_key_exists($id, $this->getTypesList());
		}

		/** @inheritDoc */
		public function getTypesList() {
			if (empty($this->typeList)) {
				$this->loadTypeList();
			}

			return $this->typeList;
		}

		/** @inheritDoc */
		public function clearCache() {
			$this->typeList = [];
			$this->loadTypeList();
		}

		/** Загружает список типов */
		private function loadTypeList() {
			$sql = <<<SQL
SELECT `id`, `name`, `title`, `ext`, `hide_pages` FROM `cms3_hierarchy_types` ORDER BY `name`, `ext`
SQL;
			$result = ConnectionPool::getInstance()
				->getConnection()
				->queryResult($sql);
			$result->setFetchType(IQueryResult::FETCH_ROW);

			foreach ($result as $row) {
				$id = $row[0];

				try {
					$type = new umiHierarchyType($id, $row);
				} catch (privateException $e) {
					$e->unregister();
					continue;
				}

				$this->setLoadedType($type);
			}

			return true;
		}

		/**
		 * Удаляет тип из кеша
		 * @param int $id id типа
		 * @return $this
		 */
		private function unsetLoadedType($id) {
			unset($this->typeList[$id]);
			return $this;
		}

		/**
		 * Добавляет тип в кеш
		 * @param iUmiHierarchyType $type тип
		 * @return $this
		 */
		private function setLoadedType(iUmiHierarchyType $type) {
			$this->typeList[$type->getId()] = $type;
			return $this;
		}

		/**
		 * Возвращает тип из кеша
		 * @param int $id id типа
		 * @return iUmiHierarchyType|null
		 */
		private function getLoadedType($id) {
			if ($this->isExists($id)) {
				return $this->typeList[$id];
			}

			return null;
		}
	}
