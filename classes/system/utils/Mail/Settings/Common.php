<?php

	namespace UmiCms\Classes\System\Utils\Mail\Settings;

	use UmiCms\Service;
	use UmiCms\Classes\System\Utils\Settings\Common as SettingsCommon;

	/**
	 * Класс для работы с настройками отправки почты, общими для всех сайтов
	 * @package UmiCms\Classes\System\Utils\Mail\Settings
	 */
	class Common extends SettingsCommon implements iSettings {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getAdminEmail() {
			return (string) $this->getRegistry()->get("{$this->getPrefix()}/admin_email");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setAdminEmail($email) {
			$this->getRegistry()->set("{$this->getPrefix()}/admin_email", $email);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getSenderEmail() {
			return (string) $this->getRegistry()->get("{$this->getPrefix()}/email_from");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setSenderEmail($email) {
			$this->getRegistry()->set("{$this->getPrefix()}/email_from", $email);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getSenderName() {
			return (string) $this->getRegistry()->get("{$this->getPrefix()}/fio_from");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setSenderName($name) {
			$this->getRegistry()->set("{$this->getPrefix()}/fio_from", $name);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getEngine() {
			return (string) $this->getConfig()->get('mail', 'engine');
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setEngine($engine) {
			$this->setMailConfig('engine', $engine);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isDisableParseContent() {
			return (bool) !$this->getConfig()->get('mail', 'default.parse.content');
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDisableParseContent($isDisable) {
			$this->setMailConfig('default.parse.content', (int) !$isDisable);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getXMailer() {
			return (string) $this->getConfig()->get('mail', 'x-mailer');
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setXMailer($value) {
			$this->setMailConfig('x-mailer', $value);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function Smtp() {
			return Service::get('SmtpMailSettingsFactory')->createCommon();
		}

		/** @inheritDoc */
		protected function getPrefix() {
			return "//settings";
		}

		/**
		 * Возвращает объект конфигурации
		 * @return \iConfiguration
		 */
		private function getConfig() {
			return \mainConfiguration::getInstance();
		}

		/**
		 * Изменяет директиву настроек отправки почты в config.ini
		 * @param string $name имя директивы
		 * @param mixed $value значение директивы
		 */
		private function setMailConfig($name, $value) {
			$config = $this->getConfig();
			$config->set('mail', $name, (string) $value);
			$config->save();
		}
	}