<?php

	namespace UmiCms\Classes\System\Utils\Mail\Settings\Smtp;

	use UmiCms\Mail\Engine\smtp;
	use UmiCms\Classes\System\Utils\Settings\Custom as SettingsCustom;

	/**
	 * Класс для работы с настройками SMTP, специфическими для конкретного сайта
	 * @package UmiCms\Classes\System\Utils\Mail\Settings\Smtp
	 */
	class Custom extends SettingsCustom implements iSettings {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getTimeout() {
			return (int) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::TIMEOUT);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setTimeout($timeout) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::TIMEOUT, $timeout);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getHost() {
			return (string) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::HOST);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setHost($host) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::HOST, $host);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getPort() {
			return (int) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::PORT);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setPort($port) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::PORT, $port);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getEncryption() {
			return (string) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::ENCRYPTION);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setEncryption($encryption) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::ENCRYPTION, $encryption);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isAuth() {
			return (bool) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::AUTH);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setAuth($isAuth) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::AUTH, $isAuth);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getUserName() {
			return (string) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::USER);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setUserName($username) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::USER, $username);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getPassword() {
			return (string) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::PASSWORD);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setPassword($password) {
			if ($password === null) {
				return $this;
			}

			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::PASSWORD, $password);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isDebug() {
			return (bool) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::DEBUG);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDebug($isDebug) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::DEBUG, $isDebug);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isUseVerp() {
			return (bool) $this->getRegistry()->get($this->getPrefix() . '/' . smtp::USE_VERP);
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setUseVerp($isVerp) {
			$this->getRegistry()->set($this->getPrefix() . '/' . smtp::USE_VERP, $isVerp);
			return $this;
		}

		/** @inheritDoc */
		protected function getPrefix() {
			return "//settings/mail/{$this->domainId}/{$this->langId}";
		}

	}