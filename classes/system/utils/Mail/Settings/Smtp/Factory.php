<?php

	namespace UmiCms\Classes\System\Utils\Mail\Settings\Smtp;

	use UmiCms\Classes\System\Utils\Settings\Factory as SettingsFactory;

	/**
	 * Класс фабрики настроек SMTP
	 * @package UmiCms\Classes\System\Utils\Mail\Settings\Smtp
	 */
	class Factory extends SettingsFactory implements iFactory {

		/** @inheritDoc */
		protected function getCommon() {
			return new Common($this->getRegistry());
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		protected function getCustom($domainId = null, $langId = null) {
			return new Custom($domainId, $langId, $this->getRegistry());
		}
	}