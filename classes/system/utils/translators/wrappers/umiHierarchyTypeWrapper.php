<?php

	/** Класс xml транслятора (сериализатора) страниц базовых (иерархических) типов */
	class umiHierarchyTypeWrapper extends translatorWrapper {

		/**
		 * @inheritDoc
		 * @param iUmiHierarchyType $object
		 */
		public function translate($object) {
			return $this->translateData($object);
		}

		/**
		 * Преобразует базовый (иерархический) тип в массив с разметкой для последующей сериализации в xml
		 * @param iUmiHierarchyType $type базовый (иерархический) тип
		 * @return array
		 */
		protected function translateData(iUmiHierarchyType $type) {
			return [
				'attribute:id' => $type->getId(),
				'attribute:module' => $type->getName(),
				'attribute:method' => $type->getExt(),
				'attribute:hide-pages' => (int) $type->hidePages(),
				'node:title' => $type->getTitle($this->getOption(xmlTranslator::IGNORE_I18N))
			];
		}
	}
