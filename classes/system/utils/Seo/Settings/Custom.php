<?php

	namespace UmiCms\Classes\System\Utils\Seo\Settings;

	use UmiCms\Classes\System\Utils\Settings\Custom as SettingsCustom;

	/**
	 * Класс SEO настроек отдельных для каждого сайта
	 * @package UmiCms\Classes\System\Utils\Seo\Settings
	 */
	class Custom extends SettingsCustom implements iSettings {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getTitlePrefix() {
			return (string) $this->getRegistry()
				->get("{$this->getMetaPrefix('title_prefix')}");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setTitlePrefix($prefix) {
			$this->getRegistry()->set("{$this->getMetaPrefix('title_prefix')}", $prefix);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultTitle() {
			return (string) $this->getRegistry()
				->get("{$this->getMetaPrefix('default_title')}");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultTitle($title) {
			$this->getRegistry()->set("{$this->getMetaPrefix('default_title')}", $title);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultKeywords() {
			return (string) $this->getRegistry()
				->get("{$this->getMetaPrefix('meta_keywords')}");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultKeywords($keywords) {
			$this->getRegistry()->set("{$this->getMetaPrefix('meta_keywords')}", $keywords);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultDescription() {
			return (string) $this->getRegistry()
				->get("{$this->getMetaPrefix('meta_description')}");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultDescription($description) {
			$this->getRegistry()->set("{$this->getMetaPrefix('meta_description')}", $description);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isCaseSensitive() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}/case-sensitive");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setCaseSensitive($value) {
			$this->getRegistry()->set("{$this->getPrefix()}/case-sensitive", $value);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getCaseSensitiveStatus() {
			return (string) $this->getRegistry()->get("{$this->getPrefix()}/case-sensitive-status");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setCaseSensitiveStatus($value) {
			$this->getRegistry()->set("{$this->getPrefix()}/case-sensitive-status", $value);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isProcessRepeatedSlashes() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}/process-slashes");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setProcessRepeatedSlashes($value) {
			$this->getRegistry()->set("{$this->getPrefix()}/process-slashes", $value);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getProcessRepeatedSlashesStatus() {
			return (string) $this->getRegistry()->get("{$this->getPrefix()}/process-slashes-status");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setProcessRepeatedSlashesStatus($status) {
			$this->getRegistry()->set("{$this->getPrefix()}/process-slashes-status", $status);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function isAddIdToDuplicateAltName() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}/add-id-to-alt-name");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setAddIdToDuplicateAltName($value) {
			$this->getRegistry()->set("{$this->getPrefix()}/add-id-to-alt-name", $value);
			return $this;
		}

		/** @inheritDoc */
		protected function getPrefix() {
			return "//settings/seo/{$this->domainId}/{$this->langId}";
		}

		/**
		 * Возвращает общий для мета-тэгов префикс в реестре
		 * @param string $name имя мета-тэга
		 * @return string
		 */
		private function getMetaPrefix($name) {
			return "//settings/$name/{$this->domainId}/{$this->langId}";
		}
	}