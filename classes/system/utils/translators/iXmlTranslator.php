<?php

	/** Интерфейс xml транслятора (сериализатора) */
	interface iXmlTranslator {

		/**
		 * Конструктор
		 * @param DOMDocument|null $dom документ, куда требуется добавить сериализованные данные
		 */
		public function __construct(DOMDocument $dom = null);

		/**
		 * Устанавливает документ, куда требуется добавить сериализованные данные
		 * @param DOMDocument $domDocument документ
		 * @return $this
		 */
		public function setDocument(DOMDocument $domDocument);

		/**
		 * Сериализует данные в xml
		 * @param DOMElement $rootNode узел, куда требуется добавить сериализованные данные
		 * @param mixed $userData данные, которые требуется сериализовать
		 */
		public function translateToXml(DOMElement $rootNode, $userData);

		/**
		 * Сериализует данные в xml
		 * @param DOMElement $rootNode узел, куда требуется добавить сериализованные данные
		 * @param mixed $userData данные, которые требуется сериализовать
		 * @param array $options опции сериализации
		 * @throws coreException
		 */
		public function chooseTranslator(DOMElement $rootNode, $userData, $options = []);

		/**
		 * Определяет разрешена ли обработка tpl макросов
		 * @return bool
		 */
		public static function isParseTPLMacrosesAllowed();

		/**
		 * Устанавливает разрешена ли обработка tpl макросов
		 * @param bool $status
		 * @return void
		 */
		public static function enableTplMacrosParsing(bool $status = true) : void;

		/**
		 * Возвращает список разрешенных для обработки макросов
		 * @return null|string[]
		 */
		public static function getAllowedTplMacroses();

		/**
		 * Устанавливает черный список макросов
		 * @param string[] $macrosList черный список макросов
		 * @example [
		 * 		'content/redirect'
		 * ]
		 * @return void
		 */
		public static function setMacrosBlackList(array $macrosList) : void ;

		/**
		 * Выполяет tpl макросы в строковых данных
		 * @param string $userData строковые данные
		 * @param bool $scopeElementId идентификатор страницы контекста
		 * @param bool $scopeObjectId идентификатор объекта контекста
		 * @return string
		 * @throws coreException
		 */
		public static function executeMacroses($userData, $scopeElementId = false, $scopeObjectId = false);

		/**
		 * Возвращает левую часть ключа
		 * @param string $key ключ данных
		 * @return string
		 */
		public static function getSubKey($key);

		/**
		 * Возвращает правую часть ключа
		 * @param string $key ключ данных
		 * @return string
		 */
		public static function getRealKey($key);

		/**
		 * Возвращает массив правой и левой частей ключа
		 * @param string $key ключ данных
		 * @return array
		 */
		public static function getKey($key);

		/** Очищает кэш у всех экземпляров класса */
		public static function clearCache();

		/**
		 * Устанавливает опцию сериализации
		 * @param string $name имя
		 * @param mixed $value значение
		 */
		public static function setOption($name, $value);

		/** 
		 * Возвращает массив опций сериализации 
		 * @param string $name имя
		 * @return array
		 */
		public static function getOption($name);
	}
