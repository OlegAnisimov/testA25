<?php
	namespace UmiCms\System\Selector\Order;

	/**
	 * Интерфейс сортировки по абстрактному атрибуту страницы или объекта
	 * @package UmiCms\System\Selector\Order
	 */
	interface iAttribute {

		/** Устанавливает режим сортировки по возрастанию */
		public function asc();

		/** Устанавливает режим сортировки по убыванию */
		public function desc();

		/** Устанавливает режим сортировки случайный */
		public function rand();

		/**
		 * Магический геттер свойств класса
		 * @param string $prop имя свойства класса
		 * @return bool
		 */
		public function __get($prop);

		/**
		 * Проверяет наличие свойства
		 * @param string $prop имя свойства
		 * @return bool
		 */
		public function __isset($prop);

		/**
		 * Вызывает действия до выполнения выборки
		 * @param \selectorExecutor $executor исполнитель выборок
		 * @throws \selectorException
		 */
		public function beforeQuery(\selectorExecutor $executor) : void;

		/**
		 * Вызывает действия послен выполнения выборки
		 * @param \selectorExecutor $executor исполнитель выборок
		 * @throws \selectorException
		 */
		public function afterQuery(\selectorExecutor $executor) : void;
	}