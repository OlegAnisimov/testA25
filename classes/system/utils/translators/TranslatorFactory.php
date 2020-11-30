<?php

	namespace UmiCms\Classes\System\Translators;

	/**
	 * Фабрика трансляторов
	 * В данный момент классы трансляторов не могут иметь один интерфейс,
	 * так как у них отличаются методы непосредственной трансляции данных.
	 * @package UmiCms\Classes\System\Translators
	 */
	class TranslatorFactory implements iTranslatorFactory {

		/** @inheritDoc */
		public static function create($class) {
			if (!class_exists($class)) {
				throw new \ErrorException(sprintf('Translator class %s does not exist', $class));
			}

			return self::createInstance($class);
		}

		/**
		 * Создает экземпляр класса транслятора
		 * @param string $class имя класса создаваемого экземпляра
		 * @return \jsonTranslator|\xmlTranslator|PhpTranslator
		 */
		private static function createInstance($class) {
			return new $class();
		}
	}
