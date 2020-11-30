<?php

	namespace UmiCms\Classes\System\Utils\Seo\Settings;

	use UmiCms\Classes\System\Utils\Settings\Common as SettingsCommon;

	/**
	 * Класс SEO настроек, общих для всех сайтов
	 * @package UmiCms\Classes\System\Utils\Seo\Settings
	 */
	class Common extends SettingsCommon implements iSettings {

		use \tUmiRegistryInjector;

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getTitlePrefix() {
			return (string) $this->getRegistry()
				->get("{$this->getPrefix()}/title_prefix");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setTitlePrefix($prefix) {
			$this->getRegistry()->set("{$this->getPrefix()}/title_prefix", $prefix);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultTitle() {
			return (string) $this->getRegistry()
				->get("{$this->getPrefix()}/default_title");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultTitle($title) {
			$this->getRegistry()->set("{$this->getPrefix()}/default_title", $title);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultKeywords() {
			return (string) $this->getRegistry()
				->get("{$this->getPrefix()}/meta_keywords");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultKeywords($keywords) {
			$this->getRegistry()->set("{$this->getPrefix()}/meta_keywords", $keywords);
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function getDefaultDescription() {
			return (string) $this->getRegistry()
				->get("{$this->getPrefix()}/meta_description");
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function setDefaultDescription($description) {
			$this->getRegistry()->set("{$this->getPrefix()}/meta_description", $description);
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
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}/case-sensitive-status");
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
			return (int) $this->getRegistry()->get("{$this->getPrefix()}/process-slashes-status");
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

		/**
		 * Определяет нужно ли учитывать поле "h1" в списке страниц с незаполненным meta тегами
		 * @return bool
		 * @throws \Exception
		 */
		public function isAllowEmptyH1() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}allow-empty-h1");
		}

		/**
		 * Устанавливает нужно ли учитывать поле "h1" в списке страниц с незаполненным meta тегами
		 * @param bool $value
		 * @return $this
		 * @throws \Exception
		 */
		public function setAllowEmptyH1($value) {
			$this->getRegistry()->set("{$this->getPrefix()}allow-empty-h1", $value);
			return $this;
		}

		/**
		 * Определяет нужно ли учитывать поле "title" в списке страниц с незаполненным meta тегами
		 * @return bool
		 * @throws \Exception
		 */
		public function isAllowEmptyTitle() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}allow-empty-title");
		}

		/**
		 * Устанавливает нужно ли учитывать поле "title" в списке страниц с незаполненным meta тегами
		 * @param bool $value
		 * @return $this
		 * @throws \Exception
		 */
		public function setAllowEmptyTitle($value) {
			$this->getRegistry()->set("{$this->getPrefix()}allow-empty-title", $value);
			return $this;
		}

		/**
		 * Определяет нужно ли учитывать поле "description" в списке страниц с незаполненным meta тегами
		 * @return bool
		 * @throws \Exception
		 */
		public function isAllowEmptyDescription() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}allow-empty-description");
		}

		/**
		 * Устанавливает нужно ли учитывать поле "description" в списке страниц с незаполненным meta тегами
		 * @param bool $value
		 * @return $this
		 * @throws \Exception
		 */
		public function setAllowEmptyDescription($value) {
			$this->getRegistry()->set("{$this->getPrefix()}allow-empty-description", $value);
			return $this;
		}

		/**
		 * Определяет нужно ли учитывать поле "keywords" в списке страниц с незаполненным meta тегами
		 * @return bool
		 * @throws \Exception
		 */
		public function isAllowEmptyKeywords() {
			return (bool) $this->getRegistry()->get("{$this->getPrefix()}allow-empty-keywords");
		}

		/**
		 * Устанавливает нужно ли учитывать поле "keywords" в списке страниц с незаполненным meta тегами
		 * @param bool $value
		 * @return $this
		 * @throws \Exception
		 */
		public function setAllowEmptyKeywords($value) {
			$this->getRegistry()->set("{$this->getPrefix()}allow-empty-keywords", $value);
			return $this;
		}

		/** @inheritDoc */
		protected function getPrefix() {
			return "//settings/seo/";
		}
	}