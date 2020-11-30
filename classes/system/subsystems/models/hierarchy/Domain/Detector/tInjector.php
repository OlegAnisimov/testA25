<?php
	namespace UmiCms\System\Hierarchy\Domain\Detector;

	use UmiCms\System\Hierarchy\Domain\iDetector;

	/**
	 * Трейт инжектора определителя текущего домена
	 * @package UmiCms\System\Hierarchy\Domain\Detector
	 */
	trait tInjector {

		/** @var iDetector|null $domainDetector определитель текущего домена */
		private $domainDetector;

		/**
		 * Устанавливает определителя текущего домена
		 * @param iDetector $detector
		 * @return $this
		 */
		public function setDomainDetector(iDetector $detector) {
			$this->domainDetector = $detector;
			return $this;
		}

		/**
		 * Возвращает определителя текущего домена
		 * @return iDetector
		 * @throws \DependencyNotInjectedException
		 */
		public function getDomainDetector() {
			if (!$this->domainDetector instanceof iDetector) {
				throw new \DependencyNotInjectedException('You should inject domain detector first');
			}

			return $this->domainDetector;
		}
	}