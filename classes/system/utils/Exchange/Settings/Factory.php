<?php

	namespace UmiCms\Classes\System\Utils\Exchange\Settings;

	use UmiCms\System\Hierarchy\Domain\iDetector as DomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as LanguageDetector;

	/**
	 * Класс фабрики настроек модуля "Обмен данными"
	 * @package UmiCms\Classes\System\Utils\Exchange\Settings
	 */
	class Factory implements iFactory {

		/** @var \iRegedit $registry реестр */
		private $registry;

		/** @var DomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @var LanguageDetector $languageDetector определитель языка */
		private $languageDetector;

		/** @inheritDoc */
		public function __construct(
			\iRegedit $registry,
			DomainDetector $domainDetector,
			LanguageDetector $languageDetector
		) {
			$this->registry = $registry;
			$this->domainDetector = $domainDetector;
			$this->languageDetector = $languageDetector;
		}

		/** @inheritDoc */
		public function getCommonSettings(): Common {
			return new Common($this->getRegistry());
		}

		/** @inheritDoc */
		public function getSiteSettings(int $domainId = null, int $langId = null): Site {
			$domainId = $domainId ?: $this->getDomainDetector()->detectId();
			$langId = $langId ?: $this->getLanguageDetector()->detectId();
			return new Site($domainId, $langId, $this->getRegistry());
		}

		/** @inheritDoc */
		public function getCurrentSettings(int $domainId = null, int $langId = null): iSettings {
			$siteSettings = $this->getSiteSettings($domainId, $langId);

			if ($siteSettings->getUseSiteSettings()) {
				return $siteSettings;
			}

			return $this->getCommonSettings();
		}

		/**
		 * Возвращает реестр
		 * @return \iRegedit
		 */
		private function getRegistry() : \iRegedit {
			return $this->registry;
		}

		/**
		 * Возвращает определитель домена
		 * @return DomainDetector
		 */
		private function getDomainDetector() : DomainDetector {
			return $this->domainDetector;
		}

		/**
		 * Возвращает определитель языка
		 * @return LanguageDetector
		 */
		private function getLanguageDetector() : LanguageDetector {
			return $this->languageDetector;
		}
	}
