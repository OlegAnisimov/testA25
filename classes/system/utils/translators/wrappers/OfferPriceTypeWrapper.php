<?php

	use UmiCms\System\Trade\Offer\Price\iType;

	/** Класс xml транслятора (сериализатора) типов цен */
	class offerPriceTypeWrapper extends translatorWrapper {

		/**
		 * @inheritDoc
		 * @param iType $object
		 */
		public function translate($object) {
			return $this->translateData($object);
		}

		/**
		 * Преобразует язык в массив с разметкой для последующей сериализации в xml
		 * @param iType $price
		 * @return array
		 */
		protected function translateData(iType $price) {
			$result = [
				'attribute:id' => $price->getId(),
				'attribute:name' => $price->getName(),
				'node:title' => $price->getTitle()
			];

			if ($price->isDefault()) {
				$result['attribute:is-default'] = 1;
			}

			return $result;
		}
	}
