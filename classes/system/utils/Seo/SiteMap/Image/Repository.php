<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\System\Orm\Entity\Repository as AbstractRepository;

	/**
	 * Класс репозитория изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Repository extends AbstractRepository implements iRepository {

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
			return $entity instanceof iImage;
		}
	}