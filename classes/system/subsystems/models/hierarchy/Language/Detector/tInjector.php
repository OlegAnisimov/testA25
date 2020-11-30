<?php
	namespace UmiCms\System\Hierarchy\Language\Detector;

	use UmiCms\System\Hierarchy\Language\iDetector;

	/**
	 * Трейт инжектора определителя текущего языка
	 * @package UmiCms\System\Hierarchy\Language\Detector
	 */
	trait tInjector {

		/** @var iDetector|null $domainDetector определитель текущего языка */
		private $languageDetector;

		/**
		 * Устанавливает определителя текущего языка
		 * @param iDetector $detector
		 * @return $this
		 */
		public function setLanguageDetector(iDetector $detector) {
			$this->languageDetector = $detector;
			return $this;
		}

		/**
		 * Возвращает определителя текущего языка
		 * @return iDetector
		 * @throws \DependencyNotInjectedException
		 */
		public function getLanguageDetector() {
			if (!$this->languageDetector instanceof iDetector) {
				throw new \DependencyNotInjectedException('You should inject language detector first');
			}

			return $this->languageDetector;
		}
	}