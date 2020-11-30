<?php
	namespace UmiCms\System\Selector\Order\Attribute\Property;

	use UmiCms\System\Selector\Order\Attribute\iProperty;

	/**
	 * Интерфейс сортировки по глобальному порядку страниц в иерархии
	 * @package UmiCms\System\Selector\Order\Attribute\Property
	 */
	interface iGlobalOrd extends iProperty {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'SelectorOrderPropertyGlobalOrd';

		/**
		 * Устанавливает подключение к бд
		 * @param \IConnection $connection подключение к бд
		 * @return $this|iGlobalOrd
		 */
		public function setConnection(\IConnection $connection) : iGlobalOrd;
	}