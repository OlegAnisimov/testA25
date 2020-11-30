<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\Repository as AbstractRepository;

	/**
	 * Класс репозитория адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	class Repository extends AbstractRepository implements iRepository {

		/** @inheritDoc */
		public function getCountByDomain(int $id) : int {
			return $this->getCountBy(iMapper::DOMAIN_ID, $id);
		}

		/** @inheritDoc */
		public function getIndexListForDomain(int $id) : array {
			$sortColumn = iMapper::SORT;
			$dateTimeColumn = iMapper::DATE_TIME;
			$table = $this->getTable();
			$domainIdColumn = iMapper::DOMAIN_ID;
			$domainId = (int) $id;
			$sql = <<<SQL
SELECT `$sortColumn`, MAX(`$dateTimeColumn`) FROM `$table` WHERE `$domainIdColumn` = $domainId GROUP BY `$sortColumn`
SQL;
			$indexList = [];
			$queryResult = $this->getConnection()
				->queryResult($sql)
				->setFetchRow();

			foreach ($queryResult as $row) {
				$indexList[] = $row;
			}

			return $indexList;
		}

		/** @inheritDoc */
		public function getListByDomain(int $id) : array {
			$table = $this->getTable();
			$domainIdColumn = iMapper::DOMAIN_ID;
			$domainId = (int) $id;
			$levelColumn = iMapper::LEVEL;
			$sql = <<<SQL
SELECT * FROM `$table` WHERE `$domainIdColumn` = $domainId ORDER BY `$levelColumn`;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntityList($result);
		}

		/** @inheritDoc */
		public function getListByDomainAndSort(int $id, int $sort) : array {
			$table = $this->getTable();
			$domainIdColumn = iMapper::DOMAIN_ID;
			$domainId = (int) $id;
			$sortColumn = iMapper::SORT;
			$sort = (int) $sort;
			$levelColumn = iMapper::LEVEL;
			$sql = <<<SQL
SELECT * FROM `$table` WHERE `$domainIdColumn` = $domainId AND `$sortColumn` = $sort ORDER BY `$levelColumn`;
SQL;
			$result = $this->getConnection()
				->queryResult($sql)
				->setFetchAssoc();
			return $this->mapEntityList($result);
		}

		/** @inheritDoc */
		public function deleteByDomain(int $id) : iRepository {
			$table = $this->getTable();
			$domainIdColumn = iMapper::DOMAIN_ID;
			$domainId = (int) $id;
			$sql = <<<SQL
DELETE FROM `$table` WHERE `$domainIdColumn` = $domainId;
SQL;
			$this->getConnection()
				->query($sql);
			return $this;
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iLocation;
		}
	}