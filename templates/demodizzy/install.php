<?php

	use UmiCms\Service;

	/** Класс сценария установки демошаблона "demodizzy". */
	class demodizzyInstallScenario extends siteInstallScenario implements iSiteInstallScenario {

		/** @inherit */
		public function run() {
			$this->setCallOrderTemplate();
			$this->addAppointmentData();
			$this->setUsersSecuritySettings();
			$this->saveSvPhone();
			$this->createApiShipDelivery();
			$this->setCatalogObjectsWeight();
			$this->setWeightField();
			$this->setTaxRateField();
		}

		/** Устанавливает поле, из которого будет браться вес товара */
		private function setWeightField() {
			$query = new selector('pages');
			$query->types('object-type')->guid('catalog-object');
			$weightFieldId = $query->searchField('weight', true);

			if (!is_numeric($weightFieldId)) {
				return false;
			}

			$umiRegistry = Service::Registry();
			$umiRegistry->setVal('//modules/emarket/order-item-weightField', $weightFieldId);
		}

		/** Добавляет поле "tax_rate_id" в типы данных, дочерние типу "Объект каталога" */
		private function setTaxRateField() {
			$umiObjectTypes = umiObjectTypesCollection::getInstance();
			$rootProductType = $umiObjectTypes->getTypeByGUID('catalog-object');

			if (!$rootProductType instanceof iUmiObjectType) {
				return;
			}

			$taxRateFieldId = $rootProductType->getFieldId('tax_rate_id');

			if (!is_numeric($taxRateFieldId)) {
				return;
			}

			$childrentTypeIdList = $umiObjectTypes->getChildTypeIds($rootProductType->getId());

			foreach ($childrentTypeIdList as $childrenTypeId) {
				$childrenType = $umiObjectTypes->getType($childrenTypeId);

				if (!$childrenType instanceof iUmiObjectType) {
					continue;
				}

				$childrenGroup = $childrenType->getFieldsGroupByName('cenovye_svojstva');

				if (!$childrenGroup instanceof iUmiFieldsGroup) {
					$umiObjectTypes->unloadType($childrenTypeId);
					continue;
				}

				$childrenGroup->attachField($taxRateFieldId);
				$umiObjectTypes->unloadType($childrenTypeId);
			}
		}

		/**
		 * Устанавливает вес товаров
		 * @throws selectorException
		 */
		private function setCatalogObjectsWeight() {
			$query = new selector('pages');
			$query->types('object-type')->name('catalog', 'object');
			$products = $query->result();
			$umiObjects = umiObjectsCollection::getInstance();

			foreach ($products as $product) {
				if (!$product instanceof iUmiHierarchyElement) {
					continue;
				}

				$product->setValue('weight', 500);
				$product->commit();
				$umiObjects->unloadObject($product->getId());
			}
		}

		/** Устанавливает номер телефона супервайзера */
		private function saveSvPhone() {
			$svUser = $umiObjects = umiObjectsCollection::getInstance()
				->getObjectByGUID('system-supervisor');

			if (!$svUser instanceof iUmiObject) {
				return false;
			}

			$svUser->setValue('phone', 88123090315);
			$svUser->commit();
		}

		/** Создает способ доставки "ApiShip" */
		private function createApiShipDelivery() {
			$umiObjectsTypes = umiObjectTypesCollection::getInstance();
			$apiShipDeliveryTypeId = $umiObjectsTypes->getTypeIdByGUID('emarket-delivery-842');

			if (!is_numeric($apiShipDeliveryTypeId)) {
				return false;
			}

			$umiObjects = umiObjectsCollection::getInstance();
			$apiShipDeliveryId = $umiObjects->addObject('Доставка через сервис ApiShip', $apiShipDeliveryTypeId);
			$apiShipDelivery = $umiObjects->getObject($apiShipDeliveryId);

			if (!$apiShipDelivery instanceof iUmiObject) {
				return false;
			}

			$providersKeys = [
				'a1',
				'b2cpl',
				'boxberry',
				'cdek',
				'dpd',
				'hermes',
				'iml',
				'maxi',
				'pickpoint',
				'pony',
				'spsr'
			];

			$apiShipDelivery->setValue('login', 'test');
			$apiShipDelivery->setValue('password', 'test');
			$apiShipDelivery->setValue('dev_mode', true);
			$apiShipDelivery->setValue('keep_log', true);
			$apiShipDelivery->setValue('delivery_types', '["1","2"]');
			$apiShipDelivery->setValue('pickup_types', '["1","2"]');
			$apiShipDelivery->setValue(
				'providers', '["' . implode('", "', $providersKeys) . '"]'
			);
			$apiShipDelivery->setValue('delivery_type_id', $umiObjects->getObjectIdByGUID('emarket-deliverytype-27958'));
			$apiShipDelivery->commit();
		}

		/** Включает настройки безопасности в модуле "Пользователи" */
		private function setUsersSecuritySettings() {
			$umiRegistry = Service::Registry();
			$umiRegistry->setVal("//modules/users/check_csrf_on_user_update", true);
			$umiRegistry->setVal("//modules/users/require_current_password", true);
		}

		/** Заполняет модуль "Онлайн-запись" демонстрационными данными */
		private function addAppointmentData() {
			$appointment = cmsController::getInstance()->getModule('appointment');

			if ($appointment instanceof appointment) {
				$this->addAppointmentPage();
				$this->addAppointmentEntities();
				$this->addRegistryData();
			}
		}

		/** Меняет ключи реестра */
		private function addRegistryData() {
			$umiRegistry = Service::Registry();
			$umiRegistry->setVal("//modules/appointment/new-record-admin-notify", true);
			$umiRegistry->setVal("//modules/appointment/new-record-user-notify", true);
			$umiRegistry->setVal("//modules/appointment/record-status-changed-user-notify", true);

			$fromQueryTemplate = '//modules/appointment/work-time-%d-from';
			$toQueryTemplate = '//modules/appointment/work-time-%d-to';
			$fromTime = '08:00';
			$toTime = '20:00';

			for ($dayNumber = 0; $dayNumber < 5; $dayNumber++) {
				$umiRegistry->setVal(sprintf($fromQueryTemplate, $dayNumber), $fromTime);
				$umiRegistry->setVal(sprintf($toQueryTemplate, $dayNumber), $toTime);
			}
		}

		/**
		 * Создает страницу с записью на прием
		 * @return bool
		 * @throws coreException
		 */
		private function addAppointmentPage() {
			$umiHierarchyTypes = umiHierarchyTypesCollection::getInstance();
			/** @var iUmiHierarchyType|iUmiEntinty $appointmentPageType */
			$appointmentPageType = $umiHierarchyTypes->getTypeByName('appointment', 'page');

			if (!$appointmentPageType instanceof iUmiHierarchyType) {
				$this->addLogMessage('Не удалось получить иерархический тип страницы с записью на прием');
				return false;
			}

			$umiHierarchy = umiHierarchy::getInstance();
			$appointmentPageId = $umiHierarchy->addElement(
				0,
				$appointmentPageType->getId(),
				'Онлайн-запись',
				'appointment'
			);

			/** @var iUmiHierarchyElement|iUmiEntinty $appointmentPage */
			$appointmentPage = $umiHierarchy->getElement($appointmentPageId, true);

			if (!$appointmentPage instanceof iUmiHierarchyElement) {
				$this->addLogMessage('Не удалось создать страницу с записью на прием');
				return false;
			}

			$umiPermissions = permissionsCollection::getInstance();
			$systemUsersPermissions = \UmiCms\Service::SystemUsersPermissions();
			$guestId = $systemUsersPermissions->getGuestUserId();
			$pageId = $appointmentPage->getId();
			$level = permissionsCollection::E_READ_ALLOWED_BIT;
			$umiPermissions->setElementPermissions($guestId, $pageId, $level);

			$appointmentPage->setValue('title', 'Запись на прием');
			$appointmentPage->setIsVisible(true);
			$appointmentPage->setIsActive(true);
			$appointmentPage->commit();

			return true;
		}

		/**
		 * Создает сущность модуля "Онлайн-запись"
		 * @throws Exception
		 */
		private function addAppointmentEntities() {
			$serviceContainer = ServiceContainerFactory::create();
			/** @var AppointmentServiceGroupsCollection $groupsCollection */
			$groupsCollection = $serviceContainer->get('AppointmentServiceGroups');
			$defaultGroupData = [
				$groupsCollection->getMap()->get('NAME_FIELD_NAME') => 'Услуги'
			];
			/** @var AppointmentServiceGroup $group */
			$group = $groupsCollection->create($defaultGroupData);

			/** @var AppointmentServicesCollection $servicesCollection */
			$servicesCollection = $serviceContainer->get('AppointmentServices');
			$servicesCollectionMap = $servicesCollection->getMap();

			$defaultServiceData = [
				$servicesCollectionMap->get('GROUP_ID_FIELD_NAME') => $group->getId(),
				$servicesCollectionMap->get('NAME_FIELD_NAME') => 'Починка бытовой техники',
				$servicesCollectionMap->get('TIME_FIELD_NAME') => '03:00:00',
				$servicesCollectionMap->get('PRICE_FIELD_NAME') => 100.500
			];
			/** @var AppointmentService $service */
			$service = $servicesCollection->create($defaultServiceData);

			/** @var AppointmentEmployeesCollection $employeesCollection */
			$employeesCollection = $serviceContainer->get('AppointmentEmployees');
			$employeesCollectionMap = $employeesCollection->getMap();

			$defaultEmployeeData = [
				$employeesCollectionMap->get('NAME_FIELD_NAME') => 'Василий Зайцев',
				$employeesCollectionMap->get('PHOTO_FIELD_NAME') => new umiImageFile(INSTALLER_CURRENT_WORKING_DIR . '/images/employee.jpg'),
				$employeesCollectionMap->get('DESCRIPTION_FIELD_NAME') => 'Ремонтник со стажем.'
			];
			/** @var AppointmentEmployee $employee */
			$employee = $employeesCollection->create($defaultEmployeeData);

			/** @var AppointmentEmployeesServicesCollection $employeesServicesCollection */
			$employeesServicesCollection = $serviceContainer->get('AppointmentEmployeesServices');
			$employeesServicesCollectionMap = $employeesServicesCollection->getMap();

			$defaultEmployeeServiceData = [
				$employeesServicesCollectionMap->get('EMPLOYEE_ID_FIELD_NAME') => $employee->getId(),
				$employeesServicesCollectionMap->get('SERVICE_ID_FIELD_NAME') => $service->getId()
			];

			$employeesServicesCollection->create($defaultEmployeeServiceData);

			/** @var AppointmentEmployeesSchedulesCollection $employeesSchedulesCollection */
			$employeesSchedulesCollection = $serviceContainer->get('AppointmentEmployeesSchedules');
			$employeesSchedulesCollectionMap = $employeesSchedulesCollection->getMap();
			$employeeIdField = $employeesSchedulesCollectionMap->get('EMPLOYEE_ID_FIELD_NAME');
			$dayNumberField = $employeesSchedulesCollectionMap->get('DAY_NUMBER_FIELD_NAME');
			$timeStartField = $employeesSchedulesCollectionMap->get('TIME_START_FIELD_NAME');
			$timeEndField = $employeesSchedulesCollectionMap->get('TIME_END_FIELD_NAME');

			$defaultEmployeeScheduleData = [
				[
					$employeeIdField => $employee->getId(),
					$dayNumberField => 0,
					$timeStartField => '09:00:00',
					$timeEndField => '18:00:00',
				],
				[
					$employeeIdField => $employee->getId(),
					$dayNumberField => 1,
					$timeStartField => '09:00:00',
					$timeEndField => '18:00:00',
				],
				[
					$employeeIdField => $employee->getId(),
					$dayNumberField => 2,
					$timeStartField => '09:00:00',
					$timeEndField => '18:00:00',
				],
				[
					$employeeIdField => $employee->getId(),
					$dayNumberField => 3,
					$timeStartField => '09:00:00',
					$timeEndField => '18:00:00',
				],
				[
					$employeeIdField => $employee->getId(),
					$dayNumberField => 4,
					$timeStartField => '09:00:00',
					$timeEndField => '18:00:00',
				]
			];

			array_map([$employeesSchedulesCollection, 'create'], $defaultEmployeeScheduleData);

			/** @var AppointmentOrdersCollection $ordersCollection */
			$ordersCollection = $serviceContainer->get('AppointmentOrders');
			$ordersCollectionMap = $ordersCollection->getMap();

			$defaultOrderDate = [
				$ordersCollectionMap->get('SERVICE_ID_FIELD_NAME') => $service->getId(),
				$ordersCollectionMap->get('EMPLOYEE_ID_FIELD_NAME') => $employee->getId(),
				$ordersCollectionMap->get('ORDER_DATE_FIELD_NAME') => new umiDate(rand(1277118393, 1750503993)),
				$ordersCollectionMap->get('DATE_FIELD_NAME') => new umiDate(rand(1277118393, 1750503993)),
				$ordersCollectionMap->get('TIME_FIELD_NAME') => '14:00:00',
				$ordersCollectionMap->get('NAME_FIELD_NAME') => 'Василий Пупкин',
				$ordersCollectionMap->get('PHONE_FIELD_NAME') => '+7 899 434 43 34',
				$ordersCollectionMap->get('EMAIL_FIELD_NAME') => 'tester@mail.ru',
				$ordersCollectionMap->get('COMMENT_FIELD_NAME') => 'На телефон отвечаю не позже 23:00',
				$ordersCollectionMap->get('STATUS_ID_FIELD_NAME') => 2,
			];

			$ordersCollection->create($defaultOrderDate);
		}

		/**
		 * Устанавливает шаблон писем для формы обратной связи "Заказать звонок"
		 * @throws coreException
		 */
		private function setCallOrderTemplate() {
			$objects = umiObjectsCollection::getInstance();
			$callOrderTemplate = $objects->getObjectByGUID('call-order-template');
			$types = umiObjectTypesCollection::getInstance();
			$callOrderFormId = $types->getTypeIdByGUID('call-order-form');

			if ($callOrderTemplate instanceof iUmiObject && is_numeric($callOrderFormId) && $callOrderFormId > 0) {
				$callOrderTemplate->setValue('form_id', $callOrderFormId);
				$callOrderTemplate->commit();
				$this->addLogMessage("Шаблон писем для формы с идентификатором ${callOrderFormId} установлен");
			}
		}
	}
