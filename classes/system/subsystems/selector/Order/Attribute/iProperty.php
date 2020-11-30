<?php
	namespace UmiCms\System\Selector\Order\Attribute;

	use UmiCms\System\Selector\Order\iAttribute;

	/**
	 * Интерфейс сортировки по системному свойству страницы или объекта
	 * @package UmiCms\System\Selector\Order\Attribute
	 */
	interface iProperty extends iAttribute {

		/**
		 * Конструктор
		 * @param string $name имя системного свойства
		 */
		public function __construct($name);
	}