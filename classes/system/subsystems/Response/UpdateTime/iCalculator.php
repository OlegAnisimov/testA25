<?php

	namespace UmiCms\System\Response\UpdateTime;

	use UmiCms\System\Cache\Statical\iFacade as iStaticCache;

	/**
	 * Интерфейс вычислителя времени последнего обновления данных ответа
	 * @package UmiCms\System\Cache\Request
	 */
	interface iCalculator {

		/**
		 * Конструктор
		 * @param \iUmiHierarchy $pageCollection коллекция страниц
		 * @param \iUmiObjectsCollection $objectCollection коллекция объектов
		 * @param iStaticCache $staticCache фасад статического кеша
		 */
		public function __construct(
			\iUmiHierarchy $pageCollection, \iUmiObjectsCollection $objectCollection, iStaticCache $staticCache
		);

		/**
		 * Вычисляет время (timestamp) последнего обновления данных ответа
		 * @return int
		 */
		public function calculate();
	}
