<?php
	namespace UmiCms\System\Selector\Order\Attribute;

	use UmiCms\System\Selector\Order\Attribute;

	/**
	 * Класс сортировки по полю страницы или объекта
	 * @package UmiCms\System\Selector\Order\Attribute
	 */
	class Field extends Attribute implements iField {

		/** @var array $fieldIdList список идентификаторов полей  */
		protected $fieldIdList;

		/** @inheritDoc */
		public function __construct(array $fieldIdList) {
			$this->fieldIdList = $fieldIdList;
		}

		/** @inheritDoc */
		public function getFieldIdList() {
			return $this->fieldIdList;
		}
	}