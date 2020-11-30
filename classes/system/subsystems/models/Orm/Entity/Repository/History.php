<?php
	namespace UmiCms\System\Orm\Entity\Repository;

	/**
	 * Класс истории репозитория сущностей
	 * @package UmiCms\System\Orm\Entity\Repository
	 */
	class History implements iHistory {

		/** @var array $createLog журнал записей о создании сущностей */
		private $createLog = [];

		/** @var array $updateLog журнал записей об обновлении сущностей */
		private $updateLog = [];

		/** @var array $deleteLog журнал записей об удалении сущностей */
		private $deleteLog = [];

		/** @var array $getLog журнал записей о запросе сущности(ей) по значению атрибута */
		private $getLog = [];

		/** @var array $getAllLog журнал записей полного списка сущностей */
		private $getAllLog = [];

		/** @inheritDoc */
		public function logCreate($id) {
			$this->createLog[] = $id;
			return $this;
		}

		/** @inheritDoc */
		public function logUpdate($id) {
			$this->updateLog[] = $id;
			return $this;
		}

		/** @inheritDoc */
		public function logDelete($id) {
			$this->deleteLog[] = $id;
			return $this;
		}

		/** @inheritDoc */
		public function logGet($name, $value) {
			$this->getLog[] = $this->packGetArgs($name, $value);
			return $this;
		}

		/** @inheritDoc */
		public function logEqualityMap(array $equalityMap) : iHistory {
			foreach ($equalityMap as $name => $value) {
				$this->logGet($name, $value);
			}

			return $this;
		}

		/** @inheritDoc */
		public function logGetAll($count) {
			$this->getAllLog[] = $count;
			return $this;
		}

		/** @inheritDoc */
		public function isCreationLogged($id) {
			return in_array($id, $this->createLog);
		}

		/** @inheritDoc */
		public function isUpdatingLogged($id) {
			return in_array($id, $this->updateLog);
		}

		/** @inheritDoc */
		public function isDeletionLogged($id) {
			return in_array($id, $this->deleteLog);
		}

		/** @inheritDoc */
		public function isGettingLogged($name, $value) {
			return in_array($this->packGetArgs($name, $value), $this->getLog);
		}

		/** @inheritDoc */
		public function isEqualityMapLogged(array $equalityMap) : bool {
			$counter= 0;
			foreach ($equalityMap as $name => $value) {
				if ($this->isGettingLogged($name, $value)) {
					$counter++;
				}
			}
			return $counter === count($equalityMap);
		}

		/** @inheritDoc */
		public function isGetAllLogged() {
			return count($this->getAllLog) > 0;
		}

		/** @inheritDoc */
		public function readCreateLog() {
			return $this->createLog;
		}

		/** @inheritDoc */
		public function readUpdateLog() {
			return $this->updateLog;
		}

		/** @inheritDoc */
		public function readDeleteLog() {
			return $this->deleteLog;
		}

		/** @inheritDoc */
		public function readGetLog() {
			return $this->getLog;
		}

		/** @inheritDoc */
		public function readGetAllLog() {
			return $this->getAllLog;
		}

		/** @inheritDoc */
		public function clear() {
			$this->createLog = [];
			$this->updateLog = [];
			$this->deleteLog = [];
			$this->getLog = [];
			$this->getAllLog = [];
			return $this;
		}

		/**
		 * Упаковывает параметры запроса сущности(ей) по значению атрибута
		 * @param string $name имя атрибута
		 * @param string $value значение атрибута
		 * @return string
		 */
		private function packGetArgs($name, $value) {
			return sprintf('%s::%s', $name, serialize($value));
		}
	}