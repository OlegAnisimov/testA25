<?php
	namespace UmiCms\System\Selector\Order;

	use UmiCms\System\Selector\Order\Attribute\iField;
	use UmiCms\System\Selector\Order\Attribute\iProperty;

	/**
	 * Интерфейс фабрики сортировок селектора
	 * @package UmiCms\System\Selector\Order
	 */
	interface iFactory {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'SelectorOrderFactory';

		/**
		 * Конструктор
		 * @param \iServiceContainer $serviceContainer контейнер сервисов
		 */
		public function __construct(\iServiceContainer $serviceContainer);

		/**
		 * Создает сортировку по полю страницы или объекта
		 * @param array $fieldIdList список идентификаторов полей
		 * @return iField
		 */
		public function createForField(array $fieldIdList) : iField;

		/**
		 * Создает сортировку по системному свойству страницы или объекта
		 * @param string $fieldName имя системного свойства
		 * @return iProperty
		 */
		public function createForProperty(string $fieldName) : iProperty;
	}