<?php

	/** Класс xml транслятора (сериализатора) типа полей */
	class umiFieldTypeWrapper extends translatorWrapper {

		/**
		 * @inheritDoc
		 * @param iUmiFieldType $object
		 */
		public function translate($object) {
			return $this->translateData($object);
		}

		/**
		 * Преобразует тип поля в массив с разметкой для последующей сериализации в xml
		 * @param iUmiFieldType $fieldType тип поля
		 * @return array
		 */
		protected function translateData(iUmiFieldType $fieldType) {
			$result = [
				'attribute:id' => $fieldType->getId(),
				'attribute:name' => $fieldType->getName($this->getOption(xmlTranslator::IGNORE_I18N)),
				'attribute:data-type' => $fieldType->getDataType()
			];

			if ($fieldType->getIsMultiple()) {
				$result['attribute:multiple'] = 'multiple';
			}

			return $result;
		}
	}
