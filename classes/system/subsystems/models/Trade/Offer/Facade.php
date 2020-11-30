<?php
	namespace UmiCms\System\Trade\Offer;

	use iRegedit as iRegistry;
	use \iUmiObject as iObject;
	use \iUmiObjectType as iType;
	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Trade\iOffer;
	use UmiCms\System\Orm\Entity\Facade as AbstractFacade;
	use UmiCms\System\Trade\Stock\iBalance as iStockBalance;
	use UmiCms\System\Trade\Offer\Price\iFacade as iOfferPriceFacade;
	use UmiCms\System\Trade\Stock\Balance\iFacade as iStockBalanceFacade;
	use UmiCms\System\Trade\Offer\Data\Object\Type\iFacade as iTypeFacade;
	use UmiCms\System\Trade\Offer\Data\Object\iFacade as iDataObjectFacade;
	use UmiCms\System\Trade\Offer\Vendor\Code\iGenerator as VendorCodeGenerator;

	/**
	 * Класс фасада торговых предложений
	 * @package UmiCms\System\Trade\Offer
	 */
	class Facade extends AbstractFacade implements iFacade  {

		/** @var iDataObjectFacade $facade фасад объектов данных торговых предложений */
		private $dataObjectFacade;

		/** @var iOfferPriceFacade $offerPriceFacade фасад цен торговых предложений */
		private $offerPriceFacade;

		/** @var VendorCodeGenerator $vendorCodeGenerator генератор артикулов */
		private $vendorCodeGenerator;

		/** @var iTypeFacade $typeFacade фасад типов торговых предложений */
		private $typeFacade;

		/** @var iStockBalanceFacade $stockBalanceFacade фасад складских остатков */
		private $stockBalanceFacade;

		/** @var iRegistry $registry реестр системы */
		private $registry;

		/** @inheritDoc */
		public function create(array $attributeList = []) {
			if (!isset($attributeList[iMapper::NAME])) {
				throw new \ErrorException('Trade offer name expected');
			}

			$typeFacade = $this->getTypeFacade();

			if (!isset($attributeList[iMapper::TYPE_ID])) {
				$attributeList[iMapper::TYPE_ID] = $typeFacade
					->getRootType()
					->getId();
			} else {
				$type = $typeFacade->get($attributeList[iMapper::TYPE_ID]);

				if (!$type instanceof iType) {
					throw new \ErrorException('Incorrect trade offer type id given');
				}
			}

			/** @var iOffer $offer */
			$offer = parent::create($attributeList);

			try {
				$this->handleVendorUniqueness($offer);
			} catch (\databaseException $exception) {
				$this->delete($offer->getId());
				throw $exception;
			}

			if (!$offer->hasDataObjectId()) {
				$dataObject = $this->getDataObjectFacade()
					->createByOffer($offer);
				$offer->setDataObject($dataObject);
			}

			if ($this->shouldGenerateVendorCode($offer)) {
				$vendorCode = $this->getVendorCodeGenerator()
					->generate($offer);
				$offer->setVendorCode($vendorCode);
			}

			if ($offer->isUpdated()) {
				$this->save($offer);
			}

			return $offer;
		}

		/** @inheritDoc */
		public function createForProduct(iObject $product) {
			return $this->create([
				iMapper::TYPE_ID => $product->getTypeId(),
				iMapper::NAME => $product->getName()
			]);
		}

		/** @inheritDoc */
		public function save(iEntity $offer) {
			$this->handleVendorUniqueness($offer);

			parent::save($offer);

			$id = $offer->getId();
			/** @var iOffer $offer */
			$offer = parent::get($id);

			if ($offer->hasDataObjectId()) {
				$this->saveDataObject($offer);
			}

			$priceFacade = $this->getOfferPriceFacade();

			/** @var iPrice $price */
			foreach ($priceFacade->getCollectionByOffer($id) as $price) {
				$priceFacade->save($price);
			}

			$stockBalanceFacade = $this->getStockBalanceFacade();

			/** @var iStockBalance $stockBalance */
			foreach ($stockBalanceFacade->getCollectionByOffer($id) as $stockBalance) {
				$stockBalanceFacade->save($stockBalance);
			}

			return $this;
		}

		/** @inheritDoc */
		public function delete($id) {
			/** @var iOffer $offer */
			$offer = parent::get($id);

			if ($this->isValidEntity($offer) && $offer->hasDataObjectId()) {
				try {
					$this->getDataObjectFacade()
						->delete($offer->getDataObjectId());
				} catch (\ErrorException $exception) {
					//nothing
				}
			}

			$priceFacade = $this->getOfferPriceFacade();
			$priceIdList = [];

			/** @var iPrice $price */
			foreach ($priceFacade->getCollectionByOffer($id) as $price) {
				$priceIdList[] = $price->getId();
			}

			$priceFacade->deleteList($priceIdList);

			$stockBalanceFacade = $this->getStockBalanceFacade();
			$stockBalanceIdList = [];

			/** @var iStockBalance $stockBalance */
			foreach ($stockBalanceFacade->getCollectionByOffer($id) as $stockBalance) {
				$stockBalanceIdList[] = $stockBalance->getId();
			}

			$stockBalanceFacade->deleteList($stockBalanceIdList);

			return parent::delete($id);
		}

		/** @inheritDoc */
		public function copy(iEntity $source) {
			$attributeList = $this->extractAttributeList($source);
			unset($attributeList[iMapper::ID]);

			if ($this->shouldCheckVendorCodeUniqueness()) {
				unset($attributeList[iMapper::VENDOR_CODE]);
			}

			unset($attributeList[iMapper::ORDER]);
			$copy = $this->create($attributeList);

			try {
				$dataObjectFacade = $this->getDataObjectFacade();
				/** @var iOffer $source */
				$sourceDataObject = $dataObjectFacade->get($source->getDataObjectId());

				if ($sourceDataObject instanceof iObject) {
					$copyDataObject = $dataObjectFacade->copy($sourceDataObject);
					$copy->setDataObjectId($copyDataObject->getId());
				}

				if ($this->shouldGenerateVendorCode($copy)) {
					$vendorCode = $this->getVendorCodeGenerator()
						->generate($copy);
					$copy->setVendorCode($vendorCode);
				}

				$this->save($copy);

				$priceFacade = $this->getOfferPriceFacade();

				/** @var iPrice $sourcePrice */
				foreach ($priceFacade->getCollectionByOffer($source->getId()) as $sourcePrice) {
					/** @var iPrice $priceCopy */
					$priceCopy = $priceFacade->copy($sourcePrice);
					$priceCopy->setOfferId($copy->getId());
					$priceFacade->save($priceCopy);
				}

				$stockBalanceFacade = $this->getStockBalanceFacade();

				/** @var iStockBalance $stockBalance */
				foreach ($stockBalanceFacade->getCollectionByOffer($source->getId()) as $stockBalance) {
					/** @var iStockBalance $stockBalanceCopy */
					$stockBalanceCopy = $stockBalanceFacade->copy($stockBalance);
					$stockBalanceCopy->setOfferId($copy->getId());
					$stockBalanceFacade->save($stockBalanceCopy);
				}

			} catch (\Exception $exception) {
				if ($copy instanceof iOffer) {
					$this->delete($copy->getId());
				}

				throw $exception;
			}

			return $copy;
		}

		/** @inheritDoc */
		public function moveCollectionByMode(iCollection $collection, iOffer $staticOffer, $mode) {
			if (!in_array($mode, $this->getMoveModeList())) {
				throw new \ErrorException('Invalid move mode given');
			}

			switch ($mode) {
				case self::MOVE_MODE_BEFORE_ENTITY : {
					return $this->moveCollectionBefore($collection, $staticOffer);
				}
				case self::MOVE_MODE_AFTER_ENTITY : {
					return $this->moveCollectionAfter($collection, $staticOffer);
				}
				default : {
					throw new \ErrorException('Unexpected move mode');
				}
			}
		}

		/** @inheritDoc */
		public function moveCollectionAfter(iCollection $collection, iOffer $staticOffer) {
			$orderIndex = $staticOffer->getOrder();
			$movedOfferIndex = $orderIndex;

			foreach ($collection as $movedOffer) {
				$this->changeOrderIndex($movedOffer, $movedOfferIndex, function($index) {
					return $index + 1;
				});
			}

			return $this;
		}

		/** @inheritDoc */
		public function moveCollectionBefore(iCollection $collection, iOffer $staticOffer) {
			$orderIndex = $staticOffer->getOrder();
			$movedOfferIndex = $orderIndex;

			foreach ($collection as $movedOffer) {
				$this->changeOrderIndex($movedOffer, $movedOfferIndex, function($index) {
					 return $index - 1;
				});
			}

			return $this;
		}

		/** @inheritDoc */
		public function setDataObjectFacade(iDataObjectFacade $facade) {
			$this->dataObjectFacade = $facade;
			return $this;
		}

		/** @inheritDoc */
		public function setOfferPriceFacade(iOfferPriceFacade $facade) {
			$this->offerPriceFacade = $facade;
			return $this;
		}

		/** @inheritDoc */
		public function setVendorCoderGenerator(VendorCodeGenerator $generator) {
			$this->vendorCodeGenerator = $generator;
			return $this;
		}

		/** @inheritDoc */
		public function setTypeFacade(iTypeFacade $typeFacade) {
			$this->typeFacade = $typeFacade;
			return $this;
		}

		/** @inheritDoc */
		public function setStockBalanceFacade(iStockBalanceFacade $stockBalanceFacade) {
			$this->stockBalanceFacade = $stockBalanceFacade;
			return $this;
		}

		/** @inheritDoc */
		public function setRegistry(iRegistry $registry) : iFacade {
			$this->registry = $registry;
			return $this;
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iEntity;
		}

		/** @inheritDoc */
		protected function getMoveModeList() {
			return [
				self::MOVE_MODE_AFTER_ENTITY,
				self::MOVE_MODE_BEFORE_ENTITY,
			];
		}

		/**
		 * Определяет нужно ли автоматически создать артикул для предложения
		 * @param iOffer $offer предложение
		 * @return bool
		 */
		private function shouldGenerateVendorCode(iOffer $offer) : bool {
			if ($offer->hasVendorCode()) {
				return false;
			}

			if ($this->registry->get('//modules/catalog/disable_generate_vendor_code')) {
				return false;
			}

			return true;
		}

		/**
		 * Проверяет артикул предложения на уникальность, если необходимо
		 * @param iOffer $offer торговое предложение
		 * @return void
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		private function handleVendorUniqueness(iOffer $offer) : void {
			$vendorCode = $offer->getVendorCode();

			if ($this->shouldCheckVendorCodeUniqueness() && $this->isOfferWithVendorCodeExists($offer)) {
				throw new \databaseException(getLabel('label-error-duplicate-vendor-code', 'catalog', $vendorCode));
			}
		}

		/**
		 * Определяет нужно ли автоматически проверять уникальность артикула
		 * @return bool
		 */
		private function shouldCheckVendorCodeUniqueness() : bool {
			return (bool) $this->registry->get('//modules/catalog/disable_unique_vendor_code') === false;
		}

		/**
		 * Определяет существует ли предложение с артикулом заданного предложения
		 * @param iOffer $offer торговое предложение
		 * @return bool
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \databaseException
		 */
		private function isOfferWithVendorCodeExists(iOffer $offer) : bool {
			$vendorCode = $offer->getVendorCode();

			if ($vendorCode === null || $vendorCode == '') {
				return false;
			}

			$existOffer = $this->getRepository()->getOneByEqualityMap([
				iMapper::VENDOR_CODE => $vendorCode
			]);

			return ($existOffer instanceof iOffer && $existOffer->getId() !== $offer->getId());
		}

		/**
		 * Изменяет индекс сортировки торгового предложения
		 * @param iOffer $offer торговое предложение
		 * @param int $initialOrderIndex индекс сортировки, относительно которого формирует новый индекс
		 * @param callable $indexCalculator функция вычисления нового индекса сортировки
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 * @throws \coreException
		 * @throws \databaseException
		 */
		private function changeOrderIndex(iOffer $offer, &$initialOrderIndex, callable $indexCalculator) {
			$initialOrderIndex = $indexCalculator($initialOrderIndex);
			$offer->setOrder($initialOrderIndex);
			$this->save($offer);
		}

		/**
		 * Сохраняет объект данных предложения
		 * @param iOffer $offer предложение
		 * @return $this
		 * @throws \ErrorException
		 * @throws \coreException
		 */
		private function saveDataObject(iOffer $offer) {
			$dataObjectFacade = $this->getDataObjectFacade();
			$dataObject = $dataObjectFacade->get($offer->getDataObjectId());

			if ($dataObject instanceof iObject) {
				$dataObjectFacade->save($dataObject);
			}

			return $this;
		}

		/**
		 * Возвращает фасад объектов данных предложений
		 * @return iDataObjectFacade
		 */
		private function getDataObjectFacade() {
			return $this->dataObjectFacade;
		}

		/**
		 * Возвращает фасада цен торговых предложений
		 * @return iOfferPriceFacade
		 */
		private function getOfferPriceFacade() {
			return $this->offerPriceFacade;
		}

		/**
		 * Возвращает генератор артикулов
		 * @return VendorCodeGenerator
		 */
		private function getVendorCodeGenerator() {
			return $this->vendorCodeGenerator;
		}

		/**
		 * Возвращает фасад типов предложений
		 * @return iTypeFacade
		 */
		private function getTypeFacade() {
			return $this->typeFacade;
		}

		/**
		 * Возвращает фасад складских остатков
		 * @return iStockBalanceFacade
		 */
		private function getStockBalanceFacade() {
			return $this->stockBalanceFacade;
		}
	}