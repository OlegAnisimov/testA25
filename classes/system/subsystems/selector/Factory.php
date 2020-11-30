<?php

	namespace UmiCms\System\Selector;

	/**
	 * Класс фабрики селекторов
	 * @package UmiCms\System\Selector
	 */
	class Factory implements iFactory {

		/** @inheritDoc */
		public function create($mode) {
			return new \selector($mode);
		}

		/** @inheritDoc */
		public function createObject() {
			return $this->create('objects');
		}

		/** @inheritDoc */
		public function createPage() {
			return $this->create('pages');
		}

		/**
		 * @param string $guid
		 * @return \selector
		 * @throws \selectorException
		 */
		public function createPageTypeGuid(string $guid) : \selector {
			$selector = $this->createPage();
			$selector->types('object-type')->guid($guid);
			return $selector;
		}

		/** @inheritDoc */
		public function createPageTypeName($module, $method) {
			$selector = $this->createPage();
			$selector->types('object-type')->name($module, $method);
			return $selector;
		}

		/** @inheritDoc */
		public function createPageTypeId(int $id) : \selector {
			$selector = $this->createPage();
			$selector->types('object-type')->id($id);
			return $selector;
		}

		/** @inheritDoc */
		public function createObjectTypeGuid($guid) {
			$selector = $this->createObject();
			$selector->types('object-type')->guid($guid);
			return $selector;
		}

		/** @inheritDoc */
		public function createObjectTypeId($id) {
			$selector = $this->createObject();
			$selector->types('object-type')->id($id);
			return $selector;
		}

		/** @inheritDoc */
		public function createObjectTypeName($module, $method) {
			$selector = $this->createObject();
			$selector->types('object-type')->name($module, $method);
			return $selector;
		}
	}
