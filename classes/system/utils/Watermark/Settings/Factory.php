<?php

	namespace UmiCms\Classes\System\Utils\Watermark\Settings;

	use UmiCms\Classes\System\Utils\Settings\Factory as SettingsFactory;

	/**
	 * Класс фабрики настроек водяного знака
	 * @package UmiCms\Classes\System\Utils\Watermark\Settings
	 */
	class Factory extends SettingsFactory implements iFactory {

		/** @inheritDoc */
		protected function getCommon() {
			return new Common($this->getRegistry());
		}

		/**
		 * @inheritDoc
		 * @throws \ErrorException
		 */
		protected function getCustom($domainId = null, $langId = null) {
			return new Custom($domainId, $langId, $this->getRegistry());
		}
	}
