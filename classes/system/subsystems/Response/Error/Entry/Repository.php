<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Orm\Entity\Repository as AbstractRepository;

	/**
	 * Класс репозитория записей об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	class Repository extends AbstractRepository implements iRepository {

		/** @inheritDoc */
		public function getSliceByDomain($domainId, $offset, $limit, array $orderMap = []) {
			return $this->getListSliceBy(iMapper::DOMAIN_ID, $domainId, $offset, $limit, $orderMap);
		}

		/** @inheritDoc */
		public function getCountByDomain($domainId) {
			return $this->getCountBy(iMapper::DOMAIN_ID, $domainId);
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iEntry;
		}
	}