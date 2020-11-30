<?php

	namespace UmiCms\Classes\System\Utils\Exchange\Settings;

	use UmiCms\System\Hierarchy\Domain\iDetector as DomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as LanguageDetector;

	/**
	 * Интерфейс фабрики настроек модуля "Обмен данными"
	 * @package UmiCms\Classes\System\Utils\Exchange\Settings
	 */
	interface iFactory {

		/**
		 * Конструктор.
		 * @param \iRegedit $registry реестр
		 * @param DomainDetector $domainDetector определитель домена
		 * @param LanguageDetector $languageDetector определитель языка
		 */
		public function __construct(
			\iRegedit $registry,
			DomainDetector $domainDetector,
			LanguageDetector $languageDetector
		);

		/**
		 * Возвращает настройки модуля, общие для всех сайтов
		 * @return Common
		 */
		public function getCommonSettings() : Common;

		/**
		 * Возвращает настройки модуля, специфические для конкретного сайта
		 * @param int|null $domainId ИД домена
		 * @param int|null $langId ИД языка
		 * @return Site
		 */
		public function getSiteSettings(int $domainId = null, int $langId = null) : Site;

		/**
		 * Возвращает настройки модуля для текущего сайта
		 * @param int|null $domainId ИД домена
		 * @param int|null $langId ИД языка
		 * @return iSettings
		 */
		public function getCurrentSettings(int $domainId = null, int $langId = null) : iSettings;
	}
