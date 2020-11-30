<?php
	namespace UmiCms\System\Trade\Offer\Price;

	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Orm\Entity\Collection as AbstractCollection;

	/**
	 * Класс коллекции цен торговых предложений
	 * @package UmiCms\System\Trade\Offer
	 */
	class Collection extends AbstractCollection implements iCollection {

		/** @inheritDoc */
		public function filterByType($id) {
			return $this->filter([
				iMapper::TYPE_ID => [
					Filter::COMPARE_TYPE_EQUALS => $id
				]
			]);
		}

		/** @inheritDoc */
		public function filterByCurrency($id) {
			return $this->filter([
				iMapper::CURRENCY_ID => [
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
		public function extractTypeId() {
			return $this->extractField(iMapper::TYPE_ID);
		}

		/** @inheritDoc */
		public function extractUniqueTypeId() {
			return $this->extractUniqueField(iMapper::TYPE_ID);
		}

		/** @inheritDoc */
		public function extractCurrencyId() {
			return $this->extractField(iMapper::CURRENCY_ID);
		}

		/** @inheritDoc */
		public function extractUniqueCurrencyId() {
			return $this->extractUniqueField(iMapper::CURRENCY_ID);
		}

		/** @inheritDoc */
		public function getMain() {
			return $this->getFirstBy(iMapper::IS_MAIN, true);
		}

		/** @inheritDoc */
		public function getByTypeId($typeId) {
			return $this->getFirstBy(iMapper::TYPE_ID, $typeId);
		}
	}