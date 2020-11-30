<?php
	namespace UmiCms\System\Selector\Order;

	/**
	 * Абстрактный класс сортировки по абстрактному атрибуту страницы или объекта
	 * @package UmiCms\System\Selector\Order
	 */
	abstract class Attribute implements iAttribute {

		/** @var bool $asc режим сортировки */
		public $asc = true;

		/** @inheritDoc */
		public function asc() {
			$this->asc = true;
		}

		/** @inheritDoc */
		public function desc() {
			$this->asc = false;
		}

		/** @inheritDoc */
		public function rand() {
			$this->name = 'rand';
		}

		/** @inheritDoc */
		public function __get($prop) {
			if (isset($this->$prop)) {
				return $this->$prop;
			}

			return false;
		}

		/** @inheritDoc */
		public function __isset($prop) {
			return property_exists(get_class($this), $prop);
		}

		/** @inheritDoc */
		public function beforeQuery(\selectorExecutor $executor) : void {}

		/** @inheritDoc */
		public function afterQuery(\selectorExecutor $executor) : void {}
	}
