<?php
	namespace UmiCms\System\Trade\Offer\Price\Currency;

	use UmiCms\System\Trade\Offer\Price\iCurrency;

	/**
	 * Класс коллекции валют
	 * @package UmiCms\System\Trade\Offer\Price\Currency
	 */
	class Collection implements iCollection {

		/** @var iCurrency[] $list список загруженных валют */
		private $list = [];

		/** @inheritDoc */
		public function getBy($name, $value) {

			foreach ($this->getAll() as $currency) {

				switch ($name) {
					case iCurrency::ID : {
						$sourceValue = $currency->getId();
						break;
					}
					case iCurrency::NAME : {
						$sourceValue = $currency->getName();
						break;
					}
					default : {
						$sourceValue = $currency->getValue($name);
					}
				}

				if ($sourceValue === $value) {
					return $currency;
				}
			}

			throw new \privateException(sprintf('Currency with "%s" = "%s" not found', $name, $value));
		}

		/** @inheritDoc */
		public function getAll() {
			return $this->list;
		}

		/** @inheritDoc */
		public function load(iCurrency $currency) {
			$this->list[$currency->getId()] = $currency;
			return $this;
		}

		/** @inheritDoc */
		public function loadList(array $currencyList) {

			foreach ($currencyList as $currency) {
				$this->load($currency);
			}

			return $this;
		}

		/** @inheritDoc */
		public function isLoaded($id) {
			if (!is_string($id) && !is_int($id)) {
				return false;
			}

			return isset($this->list[$id]);
		}

		/** @inheritDoc */
		public function unload($id) {
			if ($this->isLoaded($id)) {
				unset($this->list[$id]);
			}

			return $this;
		}
	}
