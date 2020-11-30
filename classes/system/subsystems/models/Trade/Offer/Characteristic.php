<?php
	namespace UmiCms\System\Trade\Offer;

	use \iUmiField as iField;
	use \iUmiObject as iObject;
	use UmiCms\System\Orm\Entity;
	use \iUmiObjectsCollection as iObjectFacade;

	/**
	 * Класс характеристики торгового предложения
	 * @package UmiCms\System\Trade\Offer
	 */
	class Characteristic extends Entity implements iCharacteristic {

		/** @var \iUmiField $field поле */
		private $field;

		/** @var iObject|null $dataObject объект данных */
		private $dataObject;

		/** @var iObjectFacade $objectFacade фасад объектов данных */
		private $objectFacade;

		/** @inheritDoc */
		public function __construct(iField $field, iObjectFacade $objectFacade) {
			$this->field = $field;
			$this->objectFacade = $objectFacade;
		}

		/** @inheritDoc */
		public function getId() {
			return $this->getField()->getId();
		}

		/** @inheritDoc */
		public function setId($id) {
			throw new \ErrorException('Forbidden operation');
		}

		/** @inheritDoc */
		public function getName() {
			return $this->getField()->getName();
		}

		/** @inheritDoc */
		public function getTitle() {
			return $this->getField()->getTitle();
		}

		/** @inheritDoc */
		public function getFieldType() {
			return $this->getField()->getDataType();
		}

		/** @inheritDoc */
		public function getViewType() {
			switch ($this->getFieldType()) {
				case 'file' : {
					return 'file';
				}
				case 'img_file' : {
					return 'image';
				}
				case 'float' :
				case 'counter' :
				case 'int' : {
					return 'number';
				}
				case 'text' : {
					return 'text';
				}
				case 'wysiwyg' : {
					return 'wysiwyg';
				}
				case 'string' : {
					return 'string';
				}
				case 'boolean' : {
					return 'bool';
				}
				case 'date' : {
					return 'date';
				}
				case 'symlink' : {
					return 'symlink';
				}
				case 'tags' :
				case 'relation' : {
					return 'relation';
				}
				case 'multiple_image' : {
					return 'multiple_image';
				}
				case 'multiple_file' : {
					return 'multiple_file';
				}
				default : {
					throw new \ErrorException(sprintf('Incorrect field type given "%s"', $this->getFieldType()));
				}
			}
		}

		/** @inheritDoc */
		public function isMultiple() {
			return $this->getField()->isMultiple();
		}

		/** @inheritDoc */
		public function getGuideId() {
			return $this->getField()->getGuideId();
		}

		/** @inheritDoc */
		public function getValue() {
			$rawValue = $this->getRawValue();
			return $this->prepareOutputValue($rawValue);
		}

		/** @inheritDoc */
		public function getRawValue() {
			try {
				return $this->getDataObject()
					->getValue($this->getName());
			} catch (\expectObjectException $exception) {
				return $this->isMultiple() ? [] : null;
			}
		}

		/** @inheritDoc */
		public function setValue($value) {
			try {
				$this->getDataObject()
					->setValue($this->getName(), $value);
			} catch (\expectObjectException $exception) {
				//nothing
			}

			return $this;
		}

		/** @inheritDoc */
		public function hasDataObject() {
			return $this->dataObject instanceof iObject;
		}

		/** @inheritDoc */
		public function getDataObjectId() {
			try {
				return $this->getDataObject()->getId();
			} catch (\expectObjectException $exception) {
				return null;
			}
		}

		/** @inheritDoc */
		public function setDataObject(iObject $dataObject) {
			$this->dataObject = $dataObject;
			return $this;
		}

		/** @inheritDoc */
		public function getField() {
			return $this->field;
		}

		/** @inheritDoc */
		public function getProperty() {
			try {
				return $this->getDataObject()
					->getPropByName($this->getName());
			} catch (\expectObjectException $exception) {
				return null;
			}
		}

		/**
		 * Подготавливает возвращаемое значение
		 * @param mixed $value
		 * @return mixed
		 */
		private function prepareOutputValue($value) {
			switch (true) {
				case ($value instanceof \iUmiDate) : {
					return $value->getFormattedDate('d.m.Y');
				}
				case ($value instanceof \iUmiFile) : {
					return $value->getFilePath(true);
				}
				case ($this->getFieldType() == 'relation' && is_numeric($value)) : {
					return $this->getObjectName($value);
				}
				case ($this->getFieldType() == 'relation' && is_array($value)) : {
					$nameList = [];

					foreach ($value as $id) {
						$nameList[] = $this->getObjectName($id);
					}

					return implode(', ', $nameList);
				}
				case ($this->getFieldType() == 'symlink' && is_array($value) && !$value) :
				case ($this->getFieldType() == 'multiple_image' && is_array($value) && !$value) :
				case ($this->getFieldType() == 'multiple_file' && is_array($value) && !$value) : {
					return null;
				}
				default : {
					return $value;
				}
			}
		}

		/**
		 * Возвращает имя объекта по его идентификатору
		 * @param int $id идентификатор объекта
		 * @return string
		 */
		private function getObjectName($id) {
			$object = $this->getObjectFacade()->getObject($id);
			return ($object instanceof \iUmiObject) ? $object->getName() : '';
		}

		/**
		 * Возвращает объект данных
		 * @return iObject
		 * @throws \expectObjectException
		 */
		private function getDataObject() {
			if (!$this->hasDataObject()) {
				throw new \expectObjectException('Data object expected');
			}

			return $this->dataObject;
		}

		/**
		 * Возвращает фасад объектов
		 * @return iObjectFacade
		 */
		private function getObjectFacade() {
			return $this->objectFacade;
		}
	}