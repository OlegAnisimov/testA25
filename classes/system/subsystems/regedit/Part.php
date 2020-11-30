<?php

	namespace UmiCms\System\Registry;

	/**
	 * Класс абстрактного реестра, являющегося частью стандартного реестра UMI.CMS
	 * @package UmiCms\System\Regedit
	 */
	class Part implements iPart {

		/** @var \iRegedit $storage хранилище */
		private $storage;

		/** @var string $pathPrefix префикс пути для ключей */
		private $pathPrefix = '//';

		/** @inheritDoc */
		public function __construct(\iRegedit $storage) {
			$this->storage = $storage;
		}

		/**
		 * Устанавливает префикс пути для ключей
		 * @param string $prefix префикс пути для ключей
		 * @return $this
		 */
		public function setPathPrefix($prefix) {
			$this->pathPrefix = (string) $prefix;
			return $this;
		}

		/** @inheritDoc */
		public function get($key) {
			$path = $this->buildPath($key);
			$value = $this->getStorage()
				->get($path);
			return ($value === false) ? null : (string) $value;
		}

		/** @inheritDoc */
		public function set($key, $value) {
			$path = $this->buildPath($key);
			$value = (string) $value;
			$this->getStorage()
				->set($path, $value);
			return $this;
		}

		/** @inheritDoc */
		public function getList() {
			$path = $this->getPathPrefix();
			$list = $this->getStorage()
				->getList($path);

			if (!is_array($list)) {
				return [];
			}

			return getDeepArrayUniqueValues($list);
		}

		/** @inheritDoc */
		public function contains($key) {
			$path = $this->buildPath($key);
			return $this->getStorage()
				->contains($path);
		}

		/** @inheritDoc */
		public function delete($key) {
			$path = $this->buildPath($key);
			$this->getStorage()
				->delete($path);
			return $this;
		}

		/**
		 * Возвращает префикс пути для ключей
		 * @return string
		 */
		private function getPathPrefix() {
			return $this->pathPrefix;
		}

		/**
		 * Формирует путь для хранилища
		 * @param string $key ключ
		 * @return string
		 */
		private function buildPath($key) {
			return sprintf('%s/%s', rtrim($this->getPathPrefix(), '/'), ltrim($key, '/'));
		}

		/**
		 * Возвращает хранилище
		 * @return \iRegedit
		 */
		private function getStorage() {
			return $this->storage;
		}
	}
