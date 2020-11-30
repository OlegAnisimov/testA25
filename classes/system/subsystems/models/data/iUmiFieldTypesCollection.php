<?php

	/** Интерфейс для управления/получения доступа к типам полей */
	interface iUmiFieldTypesCollection extends iSingleton {

		/**
		 * Создает новый тип поля
		 * @param string $name описание типа
		 * @param string $dataType тип данных
		 * @param bool $isMultiple является ли тип составным (массив значений)
		 * @param bool $isUnsigned зарезервировано и пока не используется
		 * @return int идентификатор созданного типа, либо false в случае неудачи
		 * @throws Exception
		 */
		public function addFieldType($name, $dataType = 'string', $isMultiple = false, $isUnsigned = false);

		/**
		 * Удаляет тип поля с заданным идентификатором из коллекции
		 * @param int $id идентификатор поля
		 * @return bool true, если удаление удалось
		 * @throws coreException
		 */
		public function delFieldType($id);

		/**
		 * Возвращает тип поля по его идентификатору, либо false в случае неудачи
		 * @param int $id идентификатор типа поля
		 * @return iUmiFieldType|bool
		 */
		public function getFieldType($id);

		/**
		 * Возвращает экземпляр класса umiFieldType по типу данных, либо false в случае неудачи
		 * @param string $dataType тип данных
		 * @param bool $isMultiple может ли значение поля данного типа состоять из массива значений
		 * @return iUmiFieldType|bool
		 * @throws databaseException
		 */
		public function getFieldTypeByDataType($dataType, $isMultiple = false);

		/**
		 * Определяет, существует ли в БД тип поля с заданным идентификатором
		 * @param int $id идентификатор типа
		 * @return bool true, если тип поля существует в БД
		 */
		public function isExists($id);

		/**
		 * Возвращает идентификатор типа поля
		 * @param string $dataType строковый идентификатор типа поля
		 * @param bool $multiple является ли поле многозначным
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getFieldTypeByDataTypeStrict($dataType, $multiple);

		/**
		 * Возвращает тип поля "Кнопка-флажок"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getBooleanType();

		/**
		 * Возвращает идентификатор типа поля "Кнопка-флажок"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getBooleanTypeId();

		/**
		 * Возвращает тип поля "Цвет"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getColorType();

		/**
		 * Возвращает идентификатор типа поля "Цвет"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getColorTypeId();

		/**
		 * Возвращает тип поля "Счетчик"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getCounterType();

		/**
		 * Возвращает идентификатор типа поля "Счетчик"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getCounterTypeId();

		/**
		 * Возвращает тип поля "Дата"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDateType();

		/**
		 * Возвращает идентификатор типа поля "Дата"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDateTypeId();

		/**
		 * Возвращает тип поля "Ссылка на домен"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDomainIdType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на домен"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDomainIdTypeId();

		/**
		 * Возвращает тип поля "Ссылка на список доменов"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleDomainIdType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на список доменов"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleDomainIdTypeId();

		/**
		 * Возвращает тип поля "Файл"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getFileType();

		/**
		 * Возвращает идентификатор типа поля "Файл"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getFileTypeId();

		/**
		 * Возвращает тип поля "Число с точкой"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getFloatType();

		/**
		 * Возвращает идентификатор типа поля "Число с точкой"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getFloatTypeId();

		/**
		 * Возвращает тип поля "Изображение"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getImageType();

		/**
		 * Возвращает идентификатор типа поля "Изображение"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getImageTypeId();

		/**
		 * Возвращает тип поля "Число"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getIntType();

		/**
		 * Возвращает идентификатор типа поля "Число"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getIntTypeId();

		/**
		 * Возвращает тип поля "Ссылка на объектный тип данных"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getObjectTypeIdType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на объектный тип данных"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getObjectTypeIdTypeId();

		/**
		 * Возвращает тип поля "Набор файлов"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleFileType();

		/**
		 * Возвращает идентификатор типа поля "Набор файлов"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleFileTypeId();

		/**
		 * Возвращает тип поля "Набор изображений"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleImageType();

		/**
		 * Возвращает идентификатор типа поля "Набор изображений"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleImageTypeId();

		/**
		 * Возвращает тип поля "Ссылка на торговое предложение"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOfferIdType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на торговое предложение"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOfferIdTypeId();

		/**
		 * Возвращает тип поля "Ссылка на список торговых предложений"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleOfferIdType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на список торговых предложений"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleOfferIdTypeId();

		/**
		 * Возвращает тип поля "Составное"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleOptionType();

		/**
		 * Возвращает идентификатор типа поля "Составное"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleOptionTypeId();

		/**
		 * Возвращает тип поля "Пароль"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getPasswordType();

		/**
		 * Возвращает идентификатор типа поля "Пароль"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getPasswordTypeId();

		/**
		 * Возвращает тип поля "Цена"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getPriceType();

		/**
		 * Возвращает идентификатор типа поля "Цена"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getPriceTypeId();

		/**
		 * Возвращает тип поля "Выпадающий список"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getObjectIdType();

		/**
		 * Возвращает идентификатор типа поля "Выпадающий список"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getObjectIdTypeId();

		/**
		 * Возвращает тип поля "Выпадающий список со множественным выбором"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleObjectIdType();

		/**
		 * Возвращает идентификатор типа поля "Выпадающий список со множественным выбором"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleObjectIdTypeId();

		/**
		 * Возвращает тип поля "Строка"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getStringType();

		/**
		 * Возвращает идентификатор типа поля "Строка"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getStringTypeId();

		/**
		 * Возвращает тип поля "Флеш-ролик"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSwfFieldType();

		/**
		 * Возвращает идентификатор типа поля "Флеш-ролик"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSwfFieldTypeId();

		/**
		 * Возвращает тип поля "Ссылка на дерево"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultiplePageType();

		/**
		 * Возвращает идентификатор типа поля "Ссылка на дерево"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultiplePageTypeId();

		/**
		 * Возвращает тип поля "Теги"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleTagType();

		/**
		 * Возвращает идентификатор типа поля "Теги"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleTagTypeId();

		/**
		 * Возвращает тип поля "Простой текст"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSimpleTextType();

		/**
		 * Возвращает идентификатор типа поля "Простой текст"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSimpleTextTypeId();

		/**
		 * Возвращает тип поля "Видео"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getVideoType();

		/**
		 * Возвращает идентификатор типа поля "Видео"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getVideoTypeId();

		/**
		 * Возвращает тип поля "HTML-текст"
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getHtmlTextType();

		/**
		 * Возвращает идентификатор типа поля "HTML-текст"
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getHtmlTextTypeId();

		/**
		 * Алиас self::getMultipleDomainIdType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDomainIdListType();

		/**
		 * Алиас self::getMultipleDomainIdTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getDomainIdListTypeId();

		/**
		 * Алиас self::getObjectTypeIdType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getLinkToObjectTypeType();

		/**
		 * Алиас self::getObjectTypeIdTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getLinkToObjectTypeTypeId();

		/**
		 * Алиас self::getMultipleOfferIdType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOfferIdListType();

		/**
		 * Алиас self::getMultipleOfferIdTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOfferIdListTypeId();

		/**
		 * Алиас self::getMultipleOptionType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOptionedType();

		/**
		 * Алиас self::getMultipleOptionTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getOptionedTypeId();

		/**
		 * Алиас self::getObjectIdType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getRelationType();

		/**
		 * Алиас self::getObjectIdTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getRelationTypeId();

		/**
		 * Алиас self::getMultipleObjectIdType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleRelationType();

		/**
		 * Алиас self::getMultipleObjectIdTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getMultipleRelationTypeId();

		/**
		 * Алиас self::getMultipleHierarchyElementType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSymlinkType();

		/**
		 * Алиас self::getMultipleHierarchyElementTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getSymlinkTypeId();

		/**
		 * Алиас self::getMultipleTagType()
		 * @return iUmiFieldType
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getTagsType();

		/**
		 * Алиас self::getMultipleTagTypeId()
		 * @return int
		 * @throws Exception
		 * @throws ExpectFieldTypeException
		 */
		public function getTagsTypeId();

		/**
		 * Возвращает список всех типов полей
		 * @return iUmiFieldType[]
		 * @throws databaseException
		 */
		public function getFieldTypesList();

		/**
		 * Очищает кэш класса и заново загружает типы полей
		 * @throws databaseException
		 */
		public function clearCache();
	}
