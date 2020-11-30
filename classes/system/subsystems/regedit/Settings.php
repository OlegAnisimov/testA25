<?php

	namespace UmiCms\System\Registry;

	/**
	 * Класс реестра общих настроек системы
	 * @package UmiCms\System\Registry
	 */
	class Settings extends Part implements iSettings {

		/** @const string PATH_PREFIX префикс пути для ключей */
		const PATH_PREFIX = '//settings';

		/** @inheritDoc */
		public function __construct(\iRegedit $storage) {
			parent::__construct($storage);
			parent::setPathPrefix(self::PATH_PREFIX);
		}

		/** @inheritDoc */
		public function setPathPrefix($prefix) {
			return $this;
		}

		/** @inheritDoc */
		public function getLicense() {
			return (string) $this->get('keycode');
		}

		/** @inheritDoc */
		public function getVersion() {
			return (string) $this->get('system_version');
		}

		/** @inheritDoc */
		public function getRevision() {
			return (string) $this->get('system_build');
		}

		/** @inheritDoc */
		public function setRevision($revision) {
			$this->set('system_build', $revision);
			return $this;
		}

		/** @inheritDoc */
		public function getEdition() {
			return (string) $this->get('system_edition');
		}

		/** @inheritDoc */
		public function getUpdateTime() {
			return (int) $this->get('last_updated');
		}

		/** @inheritDoc */
		public function getStatus() {
			return (string) $this->get('status');
		}
	}
