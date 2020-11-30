<?php
	/** Класс фасада типов полей */
	class umiFieldTypesCollection extends singleton implements iUmiFieldTypesCollection {

		/** @var iUmiFieldType[] $idMap identity map для iUmiFieldType  */
		private $idMap = [];

		/**
		 * @inheritDoc
		 * @todo: убрать обращение к базе из конструктора
		 * @throws databaseException
		 */
		protected function __construct() {
			$this->loadFieldTypes();
		}

		/**
		 * @inheritDoc
		 * @return iUmiFieldTypesCollection
		 */
		public static function getInstance($c = null) {
			return parent::getInstance(__CLASS__);
		}

		/** @inheritDoc */
		public function addFieldType($name, $dataType = 'string', $isMultiple = false, $isUnsigned = false) {
			if (!umiFieldType::isValidDataType($dataType)) {
				throw new coreException('Not valid data type given');
			}

			$connection = ConnectionPool::getInstance()->getConnection();
			$sql = "INSERT INTO cms3_object_field_types (data_type) VALUES('{$dataType}')";
			$connection->query($sql);
			$fieldTypeId = $connection->insertId();

			try {
				$fieldType = new umiFieldType($fieldTypeId);
				$fieldType->setName($name);
				$fieldType->setDataType($dataType);
				$fieldType->setIsMultiple($isMultiple);
				$fieldType->setIsUnsigned($isUnsigned);
				$fieldType->commit();
			} catch (Exception $exception) {
				$this->delFieldType($fieldTypeId);
				throw $exception;
			}

			$this->idMap[$fieldTypeId] = $fieldType;
			return $fieldTypeId;
		}

		/** @inheritDoc */
		public function delFieldType($id) {
			if (!$this->isExists($id)) {
				return false;
			}

			$id = (int) $id;
			$connection = ConnectionPool::getInstance()->getConnection();
			$sql = "DELETE FROM cms3_object_field_types WHERE id = '{$id}'";
			$connection->query($sql);

			unset($this->idMap[$id]);
			return true;
		}

		/** @inheritDoc */
		public function getFieldType($id) {
			if ($this->isExists($id)) {
				return $this->idMap[$id];
			}

			return false;
		}

		/** @inheritDoc */
		public function getFieldTypeByDataType($dataType, $isMultiple = false) {
			$dataType = (string) $dataType;

			if ($dataType === '') {
				return false;
			}

			$fieldTypes = $this->getFieldTypesList();
			$fieldType = false;

			foreach ($fieldTypes as $ftype) {
				if ($ftype->getDataType() == $dataType && $ftype->getIsMultiple() == $isMultiple) {
					$fieldType = $ftype;
					break;
				}
			}

			return $fieldType;
		}

		/** @inheritDoc */
		public function isExists($id) {
			return array_key_exists($id, $this->idMap);
		}

		/** @inheritDoc */
		public function getFieldTypeByDataTypeStrict($dataType, $multiple) {
			try {
				$type = $this->getFieldTypeByDataType($dataType, $multiple);
			} catch (databaseException $exception) {
				umiExceptionHandler::report($exception);
				$type = null;
			}

			if (!$type instanceof iUmiFieldType) {
				$multipleType = $multiple ? getLabel('label-multiple-field-type') : getLabel('label-not-multiple-field-type');
				throw new ExpectFieldTypeException(getLabel('label-errors-cannot-get-field-type', false, $multipleType, $dataType));
			}

			return $type;
		}

		/** @inheritDoc */
		public function getBooleanType() {
			return $this->getFieldTypeByDataTypeStrict('boolean', false);
		}

		/** @inheritDoc */
		public function getBooleanTypeId() {
			return $this->getBooleanType()->getId();
		}

		/** @inheritDoc */
		public function getColorType() {
			return $this->getFieldTypeByDataTypeStrict('color', false);
		}

		/** @inheritDoc */
		public function getColorTypeId() {
			return $this->getColorType()->getId();
		}

		/** @inheritDoc */
		public function getCounterType() {
			return $this->getFieldTypeByDataTypeStrict('counter', false);
		}

		/** @inheritDoc */
		public function getCounterTypeId() {
			return $this->getCounterType()->getId();
		}

		/** @inheritDoc */
		public function getDateType() {
			return $this->getFieldTypeByDataTypeStrict('date', false);
		}

		/** @inheritDoc */
		public function getDateTypeId() {
			return $this->getDateType()->getId();
		}

		/** @inheritDoc */
		public function getDomainIdType() {
			return $this->getFieldTypeByDataTypeStrict('domain_id', false);
		}

		/** @inheritDoc */
		public function getDomainIdTypeId() {
			return $this->getDomainIdType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleDomainIdType() {
			return $this->getFieldTypeByDataTypeStrict('domain_id_list', true);
		}

		/** @inheritDoc */
		public function getMultipleDomainIdTypeId() {
			return $this->getMultipleDomainIdType()->getId();
		}

		/** @inheritDoc */
		public function getFileType() {
			return $this->getFieldTypeByDataTypeStrict('file', false);
		}

		/** @inheritDoc */
		public function getFileTypeId() {
			return $this->getFileType()->getId();
		}

		/** @inheritDoc */
		public function getFloatType() {
			return $this->getFieldTypeByDataTypeStrict('float', false);
		}

		/** @inheritDoc */
		public function getFloatTypeId() {
			return $this->getFloatType()->getId();
		}

		/** @inheritDoc */
		public function getImageType() {
			return $this->getFieldTypeByDataTypeStrict('img_file', false);
		}

		/** @inheritDoc */
		public function getImageTypeId() {
			return $this->getImageType()->getId();
		}

		/** @inheritDoc */
		public function getIntType() {
			return $this->getFieldTypeByDataTypeStrict('int', false);
		}

		/** @inheritDoc */
		public function getIntTypeId() {
			return $this->getIntType()->getId();
		}

		/** @inheritDoc */
		public function getObjectTypeIdType() {
			return $this->getFieldTypeByDataTypeStrict('link_to_object_type', false);
		}

		/** @inheritDoc */
		public function getObjectTypeIdTypeId() {
			return $this->getObjectTypeIdType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleFileType() {
			return $this->getFieldTypeByDataTypeStrict('multiple_file', true);
		}

		/** @inheritDoc */
		public function getMultipleFileTypeId() {
			return $this->getMultipleFileType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleImageType() {
			return $this->getFieldTypeByDataTypeStrict('multiple_image', true);
		}

		/** @inheritDoc */
		public function getMultipleImageTypeId() {
			return $this->getMultipleImageType()->getId();
		}

		/** @inheritDoc */
		public function getOfferIdType() {
			return $this->getFieldTypeByDataTypeStrict('offer_id', false);
		}

		/** @inheritDoc */
		public function getOfferIdTypeId() {
			return $this->getOfferIdType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleOfferIdType() {
			return $this->getFieldTypeByDataTypeStrict('offer_id_list', true);
		}

		/** @inheritDoc */
		public function getMultipleOfferIdTypeId() {
			return $this->getMultipleOfferIdType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleOptionType() {
			return $this->getFieldTypeByDataTypeStrict('optioned', true);
		}

		/** @inheritDoc */
		public function getMultipleOptionTypeId() {
			return $this->getMultipleOptionType()->getId();
		}

		/** @inheritDoc */
		public function getPasswordType() {
			return $this->getFieldTypeByDataTypeStrict('password', false);
		}

		/** @inheritDoc */
		public function getPasswordTypeId() {
			return $this->getPasswordType()->getId();
		}

		/** @inheritDoc */
		public function getPriceType() {
			return $this->getFieldTypeByDataTypeStrict('price', false);
		}

		/** @inheritDoc */
		public function getPriceTypeId() {
			return $this->getPriceType()->getId();
		}

		/** @inheritDoc */
		public function getObjectIdType() {
			return $this->getFieldTypeByDataTypeStrict('relation', false);
		}

		/** @inheritDoc */
		public function getObjectIdTypeId() {
			return $this->getObjectIdType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleObjectIdType() {
			return $this->getFieldTypeByDataTypeStrict('relation', true);
		}

		/** @inheritDoc */
		public function getMultipleObjectIdTypeId() {
			return $this->getMultipleObjectIdType()->getId();
		}

		/** @inheritDoc */
		public function getStringType() {
			return $this->getFieldTypeByDataTypeStrict('string', false);
		}

		/** @inheritDoc */
		public function getStringTypeId() {
			return $this->getStringType()->getId();
		}

		/** @inheritDoc */
		public function getSwfFieldType() {
			return $this->getFieldTypeByDataTypeStrict('swf_file', false);
		}

		/** @inheritDoc */
		public function getSwfFieldTypeId() {
			return $this->getSwfFieldType()->getId();
		}

		/** @inheritDoc */
		public function getMultiplePageType() {
			return $this->getFieldTypeByDataTypeStrict('symlink', true);
		}

		/** @inheritDoc */
		public function getMultiplePageTypeId() {
			return $this->getMultiplePageType()->getId();
		}

		/** @inheritDoc */
		public function getMultipleTagType() {
			return $this->getFieldTypeByDataTypeStrict('tags', true);
		}

		/** @inheritDoc */
		public function getMultipleTagTypeId() {
			return $this->getMultipleTagType()->getId();
		}

		/** @inheritDoc */
		public function getSimpleTextType() {
			return $this->getFieldTypeByDataTypeStrict('text', false);
		}

		/** @inheritDoc */
		public function getSimpleTextTypeId() {
			return $this->getSimpleTextType()->getId();
		}

		/** @inheritDoc */
		public function getVideoType() {
			return $this->getFieldTypeByDataTypeStrict('video_file', false);
		}

		/** @inheritDoc */
		public function getVideoTypeId() {
			return $this->getVideoType()->getId();
		}

		/** @inheritDoc */
		public function getHtmlTextType() {
			return $this->getFieldTypeByDataTypeStrict('wysiwyg', false);
		}

		/** @inheritDoc */
		public function getHtmlTextTypeId() {
			return $this->getHtmlTextType()->getId();
		}

		/** @inheritDoc */
		public function getDomainIdListType() {
			return $this->getMultipleDomainIdType();
		}

		/** @inheritDoc */
		public function getDomainIdListTypeId() {
			return $this->getMultipleDomainIdTypeId();
		}

		/** @inheritDoc */
		public function getLinkToObjectTypeType() {
			return $this->getObjectTypeIdType();
		}

		/** @inheritDoc */
		public function getLinkToObjectTypeTypeId() {
			return $this->getObjectTypeIdTypeId();
		}

		/** @inheritDoc */
		public function getOfferIdListType() {
			return $this->getMultipleOfferIdType();
		}

		/** @inheritDoc */
		public function getOfferIdListTypeId() {
			return $this->getMultipleOfferIdTypeId();
		}

		/** @inheritDoc */
		public function getOptionedType() {
			return $this->getMultipleOptionType();
		}

		/** @inheritDoc */
		public function getOptionedTypeId() {
			return $this->getMultipleOptionTypeId();
		}

		/** @inheritDoc */
		public function getRelationType() {
			return $this->getObjectIdType();
		}

		/** @inheritDoc */
		public function getRelationTypeId() {
			return $this->getObjectIdTypeId();
		}

		/** @inheritDoc */
		public function getMultipleRelationType() {
			return $this->getMultipleObjectIdType();
		}

		/** @inheritDoc */
		public function getMultipleRelationTypeId() {
			return $this->getMultipleObjectIdTypeId();
		}

		/** @inheritDoc */
		public function getSymlinkType() {
			return $this->getMultiplePageType();
		}

		/** @inheritDoc */
		public function getSymlinkTypeId() {
			return $this->getMultiplePageTypeId();
		}

		/** @inheritDoc */
		public function getTagsType() {
			return $this->getMultipleTagType();
		}

		/** @inheritDoc */
		public function getTagsTypeId() {
			return $this->getMultipleTagTypeId();
		}

		/** @inheritDoc */
		public function getFieldTypesList() {
			if (!is_array($this->idMap) || umiCount($this->idMap) == 0) {
				$this->loadFieldTypes();
			}

			return $this->idMap;
		}

		/** @inheritDoc */
		public function clearCache() {
			$keys = array_keys($this->idMap);
			foreach ($keys as $key) {
				unset($this->idMap[$key]);
			}
			$this->idMap = [];
			$this->loadFieldTypes();
		}

		/**
		 * Загружает в коллекцию все типы полей, создает экземпляры класса umiFieldType для каждого типа
		 * @return bool true, если удалось загрузить, либо строку - описание ошибки, в случае неудачи.
		 * @throws databaseException
		 */
		private function loadFieldTypes() {
			$connection = ConnectionPool::getInstance()->getConnection();
			$sql = 'SELECT id, name, data_type, is_multiple, is_unsigned FROM cms3_object_field_types';
			$result = $connection->queryResult($sql);
			$result->setFetchType(IQueryResult::FETCH_ROW);

			foreach ($result as $row) {
				$fieldTypeId = $row[0];

				try {
					$fieldType = new umiFieldType($fieldTypeId, $row);
				} catch (privateException $e) {
					$e->unregister();
					continue;
				}

				$this->idMap[$fieldTypeId] = $fieldType;
			}

			return true;
		}
	}
