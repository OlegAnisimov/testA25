<?php

	namespace UmiCms\Classes\System\Utils\Seo\Settings;

	use UmiCms\Classes\System\Utils\Settings\iFactory as iSettingsFactory;

	/**
	 * Интерфейс фабрики SEO настроек
	 * @package UmiCms\Classes\System\Utils\Seo\Settings
	 */
	interface iFactory extends iSettingsFactory {

		/**
		 * @inheritDoc
		 * @return Common
		 */
		public function createCommon();

		/**
		 * @inheritDoc
		 * @return Custom
		 */
		public function createCustom($domainId = null, $langId = null);
	}