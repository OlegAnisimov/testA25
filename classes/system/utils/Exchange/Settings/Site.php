<?php

	namespace UmiCms\Classes\System\Utils\Exchange\Settings;

	/**
	 * Класс для работы с настройками модуля "Обмен данными", специфическими для конкретного сайта
	 * @package UmiCms\Classes\System\Utils\Exchange\Settings
	 */
	class Site implements iSettings, \iUmiRegistryInjector {

		use \tUmiRegistryInjector;

		/** @var int ИД домена сайта, для которого берутся настройки */
		private $domainId;

		/** @var int ИД языка сайта, для которого берутся настройки */
		private $langId;

		/**
		 * Конструктор
		 * @param int $domainId ИД домена
		 * @param int $langId ИД языка
		 * @param \iRegedit $registry реестр
		 * @throws \ErrorException
		 */
		public function __construct(int $domainId, int $langId, \iRegedit $registry) {
			if (!is_numeric($domainId) || !is_numeric($langId)) {
				throw new \ErrorException(getLabel('error-wrong-domain-and-lang-ids'));
			}

			$this->domainId = $domainId;
			$this->langId = $langId;
			$this->setRegistry($registry);
		}

		/**
		 * Возвращает значение настройки "Использовать настройки сайта"
		 * @return bool
		 */
		public function getUseSiteSettings() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}/use_site_settings");
		}

		/**
		 * Устанавливает значение настройки "Использовать настройки сайта"
		 * @param bool $status использовать или нет
		 * @return $this
		 * @throws \Exception
		 */
		public function setUseSiteSettings(bool $status) {
			$this->getRegistry()->set("{$this->getPrefix()}/use_site_settings", $status);
			return $this;
		}

		/** @inheritDoc */
		public function getTradeOffersUsedInCMl(): int {
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/use_cml_trade_offers");
		}

		/** @inheritDoc */
		public function setTradeOffersUsedInCMl(bool $status): iSettings {
			$this->getRegistry()->set("{$this->getPrefix()}/use_cml_trade_offers", $status);
			return $this;
		}

		/** @inheritDoc */
		public function getNeedToRestoreCatalogItemsFromTrashInCML(): int {
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/restore_deleted_catalog_items_from_cml");
		}

		/** @inheritDoc */
		public function setNeedToRestoreCatalogItemsFromTrashInCML(bool $status): iSettings {
			$this->getRegistry()->set("{$this->getPrefix()}/restore_deleted_catalog_items_from_cml", $status);
			return $this;
		}

		/** @inheritDoc */
		public function getChangeCatalogItemH1InCML(): int {
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/is_change_catalog_item_h1_from_cml");
		}

		/** @inheritDoc */
		public function setChangeCatalogItemH1InCML(bool $status): iSettings {
			$this->getRegistry()->set("{$this->getPrefix()}/is_change_catalog_item_h1_from_cml", $status);
			return $this;
		}

		/** @inheritDoc */
		public function getChangeCatalogItemTitleInCML(): int {
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/is_change_catalog_item_title_from_cml");
		}

		/** @inheritDoc */
		public function setChangeCatalogItemTitleInCML(bool $status): iSettings {
			$this->getRegistry()->set("{$this->getPrefix()}/is_change_catalog_item_title_from_cml", $status);
			return $this;
		}

		/** @inheritDoc */
		public function getWriteImportLog(): int {
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/is_write_import_log");
		}

		/** @inheritDoc */
		public function setWriteImportLog(bool $status): iSettings {
			$this->getRegistry()->set("{$this->getPrefix()}/is_write_import_log", $status);
			return $this;
		}

		/**
		 * Возвращает общий для настроек префикс в реестре
		 * @return string
		 */
		private function getPrefix() : string {
			return "//modules/exchange/{$this->domainId}/{$this->langId}";
		}
	}
