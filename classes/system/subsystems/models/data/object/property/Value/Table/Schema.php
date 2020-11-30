<?php

	namespace UmiCms\System\Data\Object\Property\Value\Table;

	use UmiCms\System\Data\Object\Property\Value\LangId;
	use UmiCms\System\Data\Object\Property\Value\DomainId;
	use UmiCms\System\Data\Object\Property\Value\OfferId;
	use UmiCms\System\Data\Object\Property\Value\LangIdList;
	use UmiCms\System\Data\Object\Property\Value\PriceTypeId;
	use UmiCms\System\Data\Object\Property\Value\OfferIdList;
	use UmiCms\System\Data\Object\Property\Value\MultipleFile;
	use UmiCms\System\Data\Object\Property\Value\DomainIdList;

	/**
	 * Класс схемы таблиц значений свойств объектов
	 * @package UmiCms\System\Data\Object\Property\Value\Table
	 */
	class Schema implements iSchema {

		/** @inheritDoc */
		public function getTable(\iUmiObjectProperty $property) {
			switch (true) {
				case ($property instanceof \umiObjectPropertyImgFile) :
				case ($property instanceof \umiObjectPropertyMultipleImgFile) : {
					return $this->getImagesTable();
				}
				case ($property instanceof \umiObjectPropertyFile) :
				case ($property instanceof MultipleFile) : {
					return $this->getFilesTable();
				}
				case ($property instanceof \umiObjectPropertyCounter) : {
					return $this->getCounterTable();
				}
				case ($property instanceof DomainId) :
				case ($property instanceof DomainIdList) : {
					return $this->getDomainIdTable();
				}
				case ($property instanceof OfferId) :
				case ($property instanceof OfferIdList) : {
					return $this->getOfferIdTable();
				}
				case ($property instanceof LangId) :
				case ($property instanceof LangIdList) : {
					return $this->getLangIdTable();
				}
				case ($property instanceof PriceTypeId) : {
					return $this->getPriceTypeIdTable();
				}
				default : {
					return $this->getDefaultTable();
				}
			}
		}

		/** @inheritDoc */
		public function getTableByDataType($dataType) {
			switch ($dataType) {
				case 'img_file' :
				case 'multiple_image' : {
					return $this->getImagesTable();
				}
				case 'file' :
				case 'multiple_file' : {
					return $this->getFilesTable();
				}
				case 'cnt' :
				case 'counter' : {
					return $this->getCounterTable();
				}
				case 'domain_id' :
				case 'domain_id_list' : {
					return $this->getDomainIdTable();
				}
				case 'offer_id' :
				case 'offer_id_list' : {
					return $this->getOfferIdTable();
				}
				case 'lang_id' :
				case 'lang_id_list' : {
					return $this->getLangIdTable();
				}
				case 'price_id' : {
					return $this->getPriceTypeIdTable();
				}
				default : {
					return $this->getDefaultTable();
				}
			}
		}

		/** @inheritDoc */
		public function getTableList() {
			return [
				$this->getImagesTable(),
				$this->getFilesTable(),
				$this->getCounterTable(),
				$this->getDomainIdTable(),
				$this->getOfferIdTable(),
				$this->getLangIdTable(),
				$this->getPriceTypeIdTable(),
				$this->getDefaultTable()
			];
		}

		/** @inheritDoc */
		public function getImagesTable() {
			return self::IMAGES_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getFilesTable() {
			return self::FILES_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getCounterTable() {
			return self::COUNTER_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getDomainIdTable() {
			return self::DOMAIN_ID_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getOfferIdTable() {
			return self::OFFER_ID_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getLangIdTable() : string {
			return self::LANG_ID_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getPriceTypeIdTable() : string {
			return self::PRICE_TYPE_ID_TABLE_NAME;
		}

		/** @inheritDoc */
		public function getDefaultTable() {
			return self::DEFAULT_TABLE_NAME;
		}
	}