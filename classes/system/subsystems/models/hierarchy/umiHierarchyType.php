<?php

	/**
	 * Базовый тип, используется:
	 * 1. Для связывания страниц с соответствующим обработчиком (модуль/метод)
	 * 2. Для категоризации типов данных
	 * В новой терминологии getName()/getExt() значило бы getModule()/getMethod() соответственно
	 */
	class umiHierarchyType extends umiEntinty implements iUmiHierarchyType {

		/** @inheritDoc */
		protected $store_type = 'element_type';

		/** @var string|null название ответственного модуля */
		private $name;

		/** @var string|null заголовок типа */
		private $title;

		/** @var string|null название ответственного метода */
		private $ext;

		/** @var bool|null скрывать страницы типа */
		private $hidePages;

		/** @inheritDoc */
		public function getName() {
			return $this->name;
		}

		/** @inheritDoc */
		public function getTitle($ignoreI18n = false) {
			return $ignoreI18n ? $this->title : $this->translateLabel($this->title);
		}

		/** @inheritDoc */
		public function getModule() {
			return $this->getName();
		}

		/** @inheritDoc */
		public function getMethod() {
			return $this->getExt();
		}

		/** @inheritDoc */
		public function getExt() {
			return $this->ext;
		}

		/** @inheritDoc */
		public function setName($name) {
			if ($this->getName() != $name) {
				$this->name = $name;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setTitle($title) {
			if ($this->getTitle() != $title) {
				$title = $this->translateI18n($title, 'hierarchy-type-');
				$this->title = $title;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setExt($ext) {
			if ($this->getExt() != $ext) {
				$this->ext = $ext;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function hidePages() : bool {
			return $this->hidePages;
		}

		/** @inheritDoc */
		public function setHidePages(bool $status = true) : iUmiHierarchyType {
			if ($this->hidePages() !== $status) {
				$this->hidePages = $status;
				$this->setIsUpdated();
			}

			return $this;
		}

		/** @inheritDoc */
		protected function loadInfo($row = false) {
			if (!is_array($row) || count($row) < 5) {
				$connection = ConnectionPool::getInstance()->getConnection();
				$escapedId = (int) $this->getId();
				$sql = <<<SQL
SELECT `id`, `name`, `title`, `ext`, `hide_pages` FROM `cms3_hierarchy_types` WHERE `id` = $escapedId LIMIT 0,1
SQL;
				$result = $connection->queryResult($sql);
				$result->setFetchType(IQueryResult::FETCH_ROW);
				$row = $result->fetch();
			}

			if (!is_array($row) || count($row) < 4) {
				return false;
			}

			list(, $name, $title, $ext, $hidePages) = $row;
			$this->name = $name;
			$this->title = $title;
			$this->ext = $ext;
			$this->hidePages = (bool) $hidePages;
			return true;
		}

		/** @inheritDoc */
		protected function save() {
			$name = self::filterInputString($this->name);
			$title = self::filterInputString($this->title);
			$ext = self::filterInputString($this->ext);
			$hidePages = (int) $this->hidePages;
			$id = (int) $this->id;

			$sql = <<<SQL
UPDATE `cms3_hierarchy_types`
SET `name` = '$name', `title` = '$title', `ext` = '$ext', `hide_pages` = $hidePages
WHERE `id` = $id
SQL;
			ConnectionPool::getInstance()
				->getConnection()
				->query($sql);

			return true;
		}
	}
