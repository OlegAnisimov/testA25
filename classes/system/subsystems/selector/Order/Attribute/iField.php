<?php
	namespace UmiCms\System\Selector\Order\Attribute;

	use UmiCms\System\Selector\Order\iAttribute;

	/**
	 * Интерфейс сортировки по полю страницы или объекта
	 * @package UmiCms\System\Selector\Order\Attribute
	 */
	interface iField extends iAttribute {

		/**
		 * Конструктор
		 * @param array $fieldIdList список идентификаторов полей
		 */
		public function __construct(array $fieldIdList);

		/**
		 * Возвращает список идентификаторов полей
		 * @return array
		 */
		public function getFieldIdList();
	}