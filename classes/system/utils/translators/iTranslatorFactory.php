<?php
	namespace UmiCms\Classes\System\Translators;

	/**
	 * Интерфейс фабрики трансляторов
	 * @package UmiCms\Classes\System\Translators
	 */
	interface iTranslatorFactory {

		/** @const класс транслятора данных в JSON формат */
		const JSON = 'jsonTranslator';

		/** @const класс транслятора данных в XML формат */
		const XML = 'xmlTranslator';

		/** @const класс транслятора данных в PHP формат */
		const PHP = 'UmiCms\Classes\System\Translators\PhpTranslator';

		/**
		 * Создает транслятор указанного типа
		 * @param string $class имя класса создаваемого транслятора
		 * @return \jsonTranslator|\xmlTranslator|PhpTranslator
		 * @throws \ErrorException если класс с переданным именем не существует
		 */
		public static function create($class);
	}