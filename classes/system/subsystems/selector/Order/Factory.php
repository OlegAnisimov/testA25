<?php
	namespace UmiCms\System\Selector\Order;

	use UmiCms\System\Selector\Order\Attribute\Field;
	use UmiCms\System\Selector\Order\Attribute\iField;
	use UmiCms\System\Selector\Order\Attribute\Property;
	use UmiCms\System\Selector\Order\Attribute\iProperty;
	use UmiCms\System\Selector\Order\Attribute\Property\GlobalOrd;
	use UmiCms\System\Selector\Order\Attribute\Property\iGlobalOrd;

	/**
	 * Класс фабрики сортировок селектора
	 * @package UmiCms\System\Selector\Order
	 */
	class Factory implements iFactory {

		/** @var \iServiceContainer $serviceContainer контейнер сервисов */
		private $serviceContainer;

		/** @inheritDoc */
		public function __construct(\iServiceContainer $serviceContainer) {
			$this->serviceContainer = $serviceContainer;
		}

		/** @inheritDoc */
		public function createForField(array $fieldIdList) : iField {
			return new Field($fieldIdList);
		}

		/** @inheritDoc */
		public function createForProperty(string $fieldName) : iProperty {
			if ($fieldName !== 'global_ord') {
				return new Property($fieldName);
			}

			$service = new GlobalOrd($fieldName);
			$this->serviceContainer->initService($service::SERVICE_NAME, $service);
			return $service;
		}
	}