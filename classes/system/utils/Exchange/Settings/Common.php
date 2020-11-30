<?php

	namespace UmiCms\Classes\System\Utils\Exchange\Settings;

	/**
	 * Класс для работы с настройками модуля "Обмен данными", общими для всех сайтов
	 * @package UmiCms\Classes\System\Utils\Exchange\Settings
	 */
	class Common implements iSettings, \iUmiRegistryInjector {

		use \tUmiRegistryInjector;

		/**
		 * Конструктор
		 * @param \iRegedit $registry реестр
		 */
		public function __construct(\iRegedit $registry) {
			$this->setRegistry($registry);
		}

		/** @inheritDoc */
		public function getTradeOffersUsedInCMl(): int {
			return (int) $this->getRegistry()->get('//modules/exchange/use_cml_trade_offers');
		}

		/** @inheritDoc */
		public function setTradeOffersUsedInCMl(bool $status): iSettings {
			$this->getRegistry()->set('//modules/exchange/use_cml_trade_offers', (int) $status);
			return $this;
		}

		/** @inheritDoc */
		public function getNeedToRestoreCatalogItemsFromTrashInCML(): int {
			return (int) $this->getRegistry()->get('//modules/exchange/restore_deleted_catalog_items_from_cml');
		}

		/** @inheritDoc */
		public function setNeedToRestoreCatalogItemsFromTrashInCML(bool $status): iSettings {
			$this->getRegistry()->set('//modules/exchange/restore_deleted_catalog_items_from_cml', (int) $status);
			return $this;
		}

		/** @inheritDoc */
		public function getChangeCatalogItemH1InCML(): int {
			return (int) $this->getRegistry()->get('//modules/exchange/is_change_catalog_item_h1_from_cml');
		}

		/** @inheritDoc */
		public function setChangeCatalogItemH1InCML(bool $status): iSettings {
			$this->getRegistry()->set('//modules/exchange/is_change_catalog_item_h1_from_cml', (int) $status);
			return $this;
		}

		/** @inheritDoc */
		public function getChangeCatalogItemTitleInCML(): int {
			return (int) $this->getRegistry()->get('//modules/exchange/is_change_catalog_item_title_from_cml');
		}

		/** @inheritDoc */
		public function setChangeCatalogItemTitleInCML(bool $status): iSettings {
			$this->getRegistry()->set('//modules/exchange/is_change_catalog_item_title_from_cml', (int) $status);
			return $this;
		}

		/** @inheritDoc */
		public function getWriteImportLog(): int {
			return (int) $this->getRegistry()->get('//modules/exchange/is_write_import_log');
		}

		/** @inheritDoc */
		public function setWriteImportLog(bool $status): iSettings {
			$this->getRegistry()->set('//modules/exchange/is_write_import_log', (int) $status);
			return $this;
		}
	}
