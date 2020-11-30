<?php
	namespace UmiCms\System\Selector\Order\Attribute;

	use UmiCms\System\Selector\Order\Attribute;

	/**
	 * Класс сортировки по системному свойству страницы или объекта
	 * @package UmiCms\System\Selector\Order\Attribute
	 */
	class Property extends Attribute implements iProperty {

		/** @var string $name имя системного свойства */
		public $name;

		/** @inheritDoc */
		public function __construct($name) {
			$this->name = $name;
		}
	}