<?php

	namespace UmiCms\Classes\System\Utils\Exchange\Settings;

	/**
	 * Интерфейс настроек модуля "Обмен данными"
	 * @package UmiCms\Classes\System\Utils\Exchange\Settings
	 */
	interface iSettings {

		/**
		 * Определяет, нужно ли использовать торговые предложения при обмене данными в формате CommerceML
		 * @return int
		 * @throws \Exception
		 */
		public function getTradeOffersUsedInCMl() : int;

		/**
		 * Устанавливает, нужно ли использовать торговые предложения при обмене данными в формате CommerceML
		 * @param bool $status использовать или нет
		 * @return iSettings
		 * @throws \Exception
		 */
		public function setTradeOffersUsedInCMl(bool $status) : iSettings;

		/**
		 * Определяет, нужно ли восстанавливать из модуля "Корзины" товары и разделы каталога при обмене
		 * @return int
		 * @throws \Exception
		 */
		public function getNeedToRestoreCatalogItemsFromTrashInCML() : int;

		/**
		 * Устанавливает, нужно ли восстанавливать из модуля "Корзины" товары и разделы каталога при обмене
		 * данными в формате CommerceML
		 * @param bool $status восстанавливать или нет
		 * @return iSettings
		 * @throws \Exception
		 */
		public function setNeedToRestoreCatalogItemsFromTrashInCML(bool $status) : iSettings;

		/**
		 * Определяет, нужно ли изменять поле "h1" товаров и разделов каталога при обмене данными в формате CommerceML
		 * @return int
		 * @throws \Exception
		 */
		public function getChangeCatalogItemH1InCML() : int;

		/**
		 * Устанавливает, нужно ли изменять поле "h1" товаров и разделов каталога при обмене данными в формате CommerceML
		 * @param bool $status использовать или нет
		 * @return iSettings
		 * @throws \Exception
		 */
		public function setChangeCatalogItemH1InCML(bool $status) : iSettings;

		/**
		 * Определяет, изменять ли поле "title" товаров и разделов каталога при обмене данными в формате CommerceML
		 * @return int
		 * @throws \Exception
		 */
		public function getChangeCatalogItemTitleInCML() : int;

		/**
		 * Устанавливает, нужно ли изменять поле "title" товаров и разделов каталога при обмене данными в формате CommerceML
		 * @param bool $status изменять или нет
		 * @return iSettings
		 * @throws \Exception
		 */
		public function setChangeCatalogItemTitleInCML(bool $status) : iSettings;

		/**
		 * Определяет, нужно ли записывать лог импорта в файл
		 * @return int
		 * @throws \Exception
		 */
		public function getWriteImportLog() : int;

		/**
		 * Устанавливает, нужно ли записывать лог импорта в файл
		 * @param bool $status записывать или нет
		 * @return iSettings
		 * @throws \Exception
		 */
		public function setWriteImportLog(bool $status) : iSettings;

	}
