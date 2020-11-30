<?php
	namespace UmiCms\System\Trade;

	use \iUmiObject as iDataObject;

	/**
	 * Класс склада
	 * @package UmiCms\System\Trade
	 */
	class Stock implements iStock {

		/** @var $dataObject iDataObject объект данных */
		private $dataObject;

		/** @inheritDoc */
		public function __construct(iDataObject $object) {
			$this->setDataObject($object);
		}

		/** @inheritDoc */
		public function getId() {
			return (int) $this->getDataObject()
				->getId();
		}

		/** @inheritDoc */
		public function getName() {
			return (string) $this->getDataObject()
				->getName();
		}

		/** @inheritDoc */
		public function setName($name) {
			if (!is_string($name) || isEmptyString($name)) {
				throw new \ErrorException('Incorrect stock name given');
			}

			$this->getDataObject()
				->setName($name);
			return $this;
		}

		/** @inheritDoc */
		public function isDefault() {
			return (bool) $this->getDataObject()
				->getValue('primary');
		}

		/** @inheritDoc */
		public function setDefault($flag = true) {
			if (!is_bool($flag)) {
				throw new \ErrorException('Incorrect stock default flag given');
			}

			$this->getDataObject()
				->setValue('primary', $flag);
			return $this;
		}

		/** @inheritDoc */
		public function getDataObject() {
			return $this->dataObject;
		}

		/** @inheritDoc */
		public function getBalanceViewType() {
			return 'number';
		}

		/** @inheritDoc */
		public function getBalanceTitle() {
			return getLabel('label-trade-offer-stock-balance', false, $this->getName());
		}

		/**
		 * Устанавливает объект данных
		 * @param iDataObject $object объект данных
		 * @return $this
		 */
		private function setDataObject(iDataObject $object) {
			$this->dataObject = $object;
			return $this;
		}
	}