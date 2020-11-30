<?php

	namespace UmiCms\System\Response\UpdateTime;

	use UmiCms\System\Cache\Statical\iFacade as iStaticCache;

	/**
	 * Класс вычислителя времени последнего обновления данных ответа
	 * @package UmiCms\System\Cache\Response
	 */
	class Calculator implements iCalculator {

		/** @var \iUmiHierarchy $pageCollection коллекция страниц */
		private $pageCollection;

		/** @var \iUmiObjectsCollection $objectCollection коллекция объектов */
		private $objectCollection;

		/** @var iStaticCache $staticCache фасад статического кеша */
		private $staticCache;

		/** @inheritDoc */
		public function __construct(
			\iUmiHierarchy $pageCollection, \iUmiObjectsCollection $objectCollection, iStaticCache $staticCache
		) {
			$this->pageCollection = $pageCollection;
			$this->objectCollection = $objectCollection;
			$this->staticCache = $staticCache;
		}

		/** @inheritDoc */
		public function calculate() {
			$pageLastUpdateTime = $this->getPageCollection()
				->getElementsLastUpdateTime();
			$objectLastUpdateTime = $this->getObjectCollection()
				->getObjectsLastUpdateTime();
			$staticCacheModifyTime = $this->staticCache->getModifyTime();
			return max($pageLastUpdateTime, $objectLastUpdateTime, $staticCacheModifyTime);
		}

		/**
		 * Возвращает коллекцию страниц
		 * @return \iUmiHierarchy
		 */
		private function getPageCollection() {
			return $this->pageCollection;
		}

		/**
		 * Возвращает коллекцию объектов
		 * @return \iUmiObjectsCollection
		 */
		private function getObjectCollection() {
			return $this->objectCollection;
		}
	}
