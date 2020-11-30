<?php
	namespace UmiCms\System\Trade\Offer\Price;

	/**
	 * Класс валюты
	 * @todo: сделать все сеттеры
	 * @package UmiCms\System\Trade\Offer\Price
	 */
	class Currency implements iCurrency {

		/** @var \iUmiObject $dataObject объект данных валюты */
		private $dataObject;

		/** @inheritDoc */
		public function __construct(\iUmiObject $dataObject) {
			$this->dataObject = $dataObject;
		}

		/** @inheritDoc */
		public function getId() {
			return $this->getDataObject()
				->getId();
		}

		/** @inheritDoc */
		public function getName() {
			return $this->getDataObject()
				->getName();
		}

		/** @inheritDoc */
		public function getCode() {
			return (string) $this->getDataObject()
				->getValue(self::CODE);
		}

		/**
		 * @inheritDoc
		 * @todo: Ввести отдельное поле и нормально реализовать метод
		 */
		public function getISOCode() {
			$code = $this->getCode();
			return ($code == 'RUR') ? 'RUB' : $code;
		}

		/** @inheritDoc */
		public function getDenomination() {
			return (int) $this->getDataObject()
				->getValue(self::DENOMINATION);
		}

		/** @inheritDoc */
		public function setDenomination($denomination) {
			$this->getDataObject()
				->setValue(self::DENOMINATION, (int) $denomination);
			return $this;
		}

		/** @inheritDoc */
		public function getRate() {
			return (float) $this->getDataObject()
				->getValue(self::RATE);
		}

		/** @inheritDoc */
		public function setRate($rate) {
			$this->getDataObject()
				->setValue(self::RATE, (float) $rate);
			return $this;
		}

		/** @inheritDoc */
		public function getPrefix() {
			return (string) $this->getDataObject()
				->getValue(self::PREFIX);
		}

		/** @inheritDoc */
		public function getSuffix() {
			return (string) $this->getDataObject()
				->getValue(self::SUFFIX);
		}

		/** @inheritDoc */
		public function getValue($name) {
			if (!is_string($name) || $name === '') {
				throw new \wrongParamException('Wrong property name given');
			}

			switch ($name) {
				case 'id' : {
					return $this->getId();
				}
				case 'name' : {
					return $this->getName();
				}
				case self::CODE : {
					return $this->getCode();
				}
				case self::ISO_CODE : {
					return $this->getISOCode();
				}
				case self::DENOMINATION : {
					return $this->getDenomination();
				}
				case self::RATE : {
					return $this->getRate();
				}
				case self::PREFIX : {
					return $this->getPrefix();
				}
				case self::SUFFIX : {
					return $this->getSuffix();
				}
				default : {
					throw new \wrongParamException(sprintf('Currency has no "%s"', $name));
				}
			}
		}

		/** @inheritDoc */
		public function format($price) {
			$price = (float) $price;
			$formattedPrice = sprintf('%s %.2f %s', $this->getPrefix(), $price, $this->getSuffix());
			return trim($formattedPrice);
		}

		/** @inheritDoc */
		public function getDataObject() {
			return $this->dataObject;
		}
	}
