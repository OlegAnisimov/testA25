<?php

	/** Интерфейс коллекции полей */
	interface iUmiFieldsCollection extends iSingleton {

		/**
		 * Создает поле и возвращает его.
		 * @param string $name строковой идентификатор поля (GUID)
		 * @param string $title наименование поля
		 * @param int $typeId идентификатор типа поля
		 * @return iUmiField|bool
		 * @throws Exception
		 */
		public function add($name, $title, $typeId);

		/**
		 * Создает поле и возвращает его.
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $typeId идентификатор типа данных
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 */
		public function addStrict($name, $title, $typeId);

		/**
		 * Создает поле типа "Кнопка-флажок"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addBoolean($name, $title);

		/**
		 * Создает поле типа "Цвет"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addColor($name, $title);

		/**
		 * Создает поле типа "Счетчик"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addCounter($name, $title);

		/**
		 * Создает поле типа "Дата"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addDate($name, $title);

		/**
		 * Создает поле типа "Ссылка на домен"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addDomainId($name, $title);

		/**
		 * Создает поле типа "Ссылка на список доменов"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultipleDomainId($name, $title);

		/**
		 * Создает поле типа "Файл"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addFile($name, $title);

		/**
		 * Создает поле типа "Число с точкой"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addFloat($name, $title) ;

		/**
		 * Создает поле типа "Изображение"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addImage($name, $title);

		/**
		 * Создает поле типа "Число"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addInt($name, $title);

		/**
		 * Создает поле типа "Ссылка на объектный тип данных"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addObjectTypeId($name, $title);

		/**
		 * Создает поле типа "Набор файлов"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultipleFile($name, $title);

		/**
		 * Создает поле типа "Набор изображений"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultipleImage($name, $title);

		/**
		 * Создает поле типа "Ссылка на торговое предложение"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addOfferId($name, $title);

		/**
		 * Создает поле типа "Ссылка на список торговых предложений"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultipleOfferId($name, $title);

		/**
		 * Создает поле типа "Составное"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addMultipleOption($name, $title, $guideId);

		/**
		 * Создает поле типа "Пароль"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addPassword($name, $title);

		/**
		 * Создает поле типа "Цена"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addPrice($name, $title);

		/**
		 * Создает поле типа "Выпадающий список"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addObjectId($name, $title, $guideId);

		/**
		 * Создает поле типа "Выпадающий список со множественным выбором"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addMultipleObjectId($name, $title, $guideId);

		/**
		 * Создает поле типа "Строка"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addString($name, $title);

		/**
		 * Создает поле типа "Флеш-ролик"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addSwf($name, $title);

		/**
		 * Создает поле типа "Ссылка на дерево"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultiplePageId($name, $title);

		/**
		 * Создает поле типа "Теги"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addMultipleTag($name, $title);

		/**
		 * Создает поле типа "Простой текст"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addSimpleText($name, $title);

		/**
		 * Создает поле типа "Видео"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addVideo($name, $title);

		/**
		 * Создает поле типа "HTML-текст"
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addHtmlText($name, $title);

		/**
		 * Алиас self::addMultipleDomainId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addDomainIdList($name, $title);

		/**
		 * Алиас self::addObjectTypeId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addLinkToObjectType($name, $title);

		/**
		 * Алиас self::addMultipleOfferId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addOfferIdList($name, $title);

		/**
		 * Алиас self::addMultipleOption()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addOptioned($name, $title, $guideId);

		/**
		 * Алиас self::addMultipleObjectId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addRelation($name, $title, $guideId);

		/**
		 * Алиас self::addMultipleObjectId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @param int $guideId идентификатор справочника
		 * @return iUmiField
		 * @throws Exception
		 * @throws databaseException
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 * @throws expectObjectTypeException
		 */
		public function addMultipleRelation($name, $title, $guideId);

		/**
		 * Алиас self::addMultiplePageId()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addSymlink($name, $title);

		/**
		 * Алиас self::addMultipleTag()
		 * @param string $name гуид
		 * @param string $title заголовок
		 * @return iUmiField
		 * @throws Exception
		 * @throws ExpectFieldException
		 * @throws ExpectFieldTypeException
		 */
		public function addTags($name, $title);

		/**
		 * Возвращает поле по его идентификатору
		 * @param int $id идентификатор поля
		 * @return iUmiField|bool
		 */
		public function getById($id);

		/**
		 * Удаляет поле по его идентификатору
		 * @param int $id идентификатор поля
		 * @return bool было ли удалено поле
		 * @throws databaseException
		 */
		public function delById($id);

		/**
		 * Проверяет существует ли поле с заданным идентификатором
		 * @param int $id идентификатор поля
		 * @return bool
		 * @throws databaseException
		 */
		public function isExists($id);

		/**
		 * Возвращает список идентификаторов полей с заданным типом
		 * @param iUmiFieldType $type тип поля
		 * @return array
		 * @throws Exception
		 */
		public function getFieldIdListByType(iUmiFieldType $type);

		/**
		 * Возвращает список полей
		 * @param int[] $idList список идентификаторов полей
		 * @return iUmiField[]
		 * @throws databaseException
		 */
		public function getFieldList(array $idList);

		/**
		 * Создает поле и возвращает его id
		 * @param string $name строковой идентификатор поля (GUID)
		 * @param string $title наименование поля
		 * @param int $fieldTypeId идентификатор типа поля
		 * @param bool $isVisible значения флага "Видимое"
		 * @param bool $isLocked значение флага "Заблокированное"
		 * @param bool $isInheritable значение флага "Наследуемое"
		 * @return int
		 * @throws Exception
		 */
		public function addField(
			$name,
			$title,
			$fieldTypeId,
			$isVisible = true,
			$isLocked = false,
			$isInheritable = false
		);

		/**
		 * Возвращает поле по его идентификатору
		 * @param int $id идентификатор поля
		 * @param array|bool $data данные поля или false
		 * @return iUmiField|bool
		 */
		public function getField($id, $data = false);

		/**
		 * Удаляет поле по его идентификатору
		 * @param int $id идентификатор поля
		 * @return bool было ли удалено поле
		 * @throws databaseException
		 */
		public function delField($id);

		/** Очищает внутренний кеш */
		public function clearCache();

		/**
		 * Фильтрует список полей от полей с заданными именами
		 * @param iUmiField[] &$fieldList список полей
		 * @param string[] $blackList список имен
		 * @return $this;
		 */
		public function filterListByNameBlackList(array &$fieldList, array $blackList);

		/**
		 * Фильтрует список полей от полей c неразрешенными типами
		 * @param iUmiField[] &$fieldList список полей
		 * @param string[] $whiteList список разрешеных типов полей
		 * @return $this;
		 */
		public function filterListByTypeWhiteList(array &$fieldList, array $whiteList);
	}
