<?php
	namespace UmiCms\System\Trade\Offer;

	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Orm\Entity\Collection as AbstractCollection;
	use UmiCms\System\Trade\Offer\Price\iCollection as iPriceCollection;
	use UmiCms\System\Trade\Stock\Balance\iCollection as iStockBalanceCollection;
	use UmiCms\System\Trade\Offer\Characteristic\iCollection as iCharacteristicCollection;

	/**
	 * Класс коллекции торговых предложений
	 * @package UmiCms\System\Trade\Offer
	 */
	class Collection extends AbstractCollection implements iCollection {

		/** @inheritDoc */
		public function sortByPriceCollection(iPriceCollection $collection) {
			return $this->sortByIdList($collection->extractOfferId());
		}

		/** @inheritDoc */
		public function sortByStockBalanceCollection(iStockBalanceCollection $collection) {
			return $this->sortByIdList($collection->extractOfferId());
		}

		/** @inheritDoc */
		public function sortByCharacteristicCollection(iCharacteristicCollection $collection) {
			return $this->sortByValueList(iMapper::DATA_OBJECT_ID, $collection->extractDataObjectId());
		}

		/** @inheritDoc */
		public function extractDataObjectId() {
			return $this->extractField(iMapper::DATA_OBJECT_ID);
		}

		/** @inheritDoc */
		public function extractUniqueDataObjectId() {
			return $this->extractUniqueField(iMapper::DATA_OBJECT_ID);
		}

		/** @inheritDoc */
		public function extractTypeId() {
			return $this->extractField(iMapper::TYPE_ID);
		}

		/** @inheritDoc */
		public function extractUniqueTypeId() {
			return $this->extractUniqueField(iMapper::TYPE_ID);
		}

		/** @inheritDoc */
		public function filterByNonZeroTotalCount() {
			return $this->filter([
				iMapper::TOTAL_COUNT => [
					Filter::COMPARE_TYPE_NOT_EQUALS => 0
				]
			]);
		}
	}