<?php
	namespace UmiCms\System\Trade\Stock\Balance;

	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Orm\Entity\Collection as AbstractCollection;

	/**
	 * Класс коллекции складских остатков
	 * @package UmiCms\System\Trade\Stock\Balance
	 */
	class Collection extends AbstractCollection implements iCollection {

		/** @inheritDoc */
		public function filterByStock($id) {
			return $this->filter([
				iMapper::STOCK_ID => [
					Filter::COMPARE_TYPE_EQUALS => $id
				]
			]);
		}

		/** @inheritDoc */
		public function filterByOffer($id) {
			return $this->filter([
				iMapper::OFFER_ID => [
					Filter::COMPARE_TYPE_EQUALS => $id
				]
			]);
		}

		/** @inheritDoc */
		public function extractOfferId() {
			return $this->extractField(iMapper::OFFER_ID);
		}

		/** @inheritDoc */
		public function extractUniqueOfferId() {
			return $this->extractUniqueField(iMapper::OFFER_ID);
		}

		/** @inheritDoc */
		public function extractStockId() {
			return $this->extractField(iMapper::STOCK_ID);
		}

		/** @inheritDoc */
		public function extractUniqueStockId() {
			return $this->extractUniqueField(iMapper::STOCK_ID);
		}
	}