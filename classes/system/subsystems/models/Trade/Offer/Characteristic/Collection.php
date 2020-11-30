<?php
	namespace UmiCms\System\Trade\Offer\Characteristic;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\Map\Filter;
	use UmiCms\System\Trade\Offer\iCharacteristic;
	use UmiCms\System\Orm\Entity\Collection as AbstractCollection;

	/**
	 * Класс коллекции характеристик торговых предложений
	 * @package UmiCms\System\Trade\Offer\Characteristic
	 */
	class Collection extends AbstractCollection implements iCollection {

		/** @inheritDoc */
		public function filterByDataObject($id) {
			return $this->filter([
				iMapper::DATA_OBJECT_ID => [
					Filter::COMPARE_TYPE_EQUALS => $id
				]
			]);
		}

		/** @inheritDoc */
		public function filterByField($name) {
			return $this->filter([
				iMapper::NAME => [
					Filter::COMPARE_TYPE_EQUALS => $name
				]
			]);
		}

		/** @inheritDoc */
		public function extractDataObjectId() {
			return $this->extractField(iMapper::DATA_OBJECT_ID);
		}

		/** @inheritDoc */
		protected function isPushed(iEntity $entity) {
			/** @var iCharacteristic $existEntity */
			$existEntity = $this->get($entity);

			if ($existEntity === null) {
				return false;
			}

			/** @var iCharacteristic $entity */
			if ($existEntity->hasDataObject() && $entity->hasDataObject()) {
				return $existEntity->getDataObjectId() === $entity->getDataObjectId();
			}

			return false;
		}
	}