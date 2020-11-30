<?php

	/** Класс заглушки поля объекта. */
	class umiObjectPropertyNull extends umiObjectProperty {

		/** @var array $storage хранилище поля */
		private $storage = [];

		/** @inheritDoc */
		public function __construct($id, $row = false) {
			$this->setId($id);
			$this->value = $this->loadValue();
		}

		/**
		 * Возвращает содержимое хранилища поля
		 * @return array
		 */
		public function getStorage() {
			return $this->storage;
		}

		/** @inheritDoc */
		public function getName() {
			return __CLASS__;
		}

		/** @inheritDoc */
		public function getDataType() {
			return 'nullType';
		}

		/** @inheritDoc */
		public function setValue($value) {
			if ($this->value !== $value) {
				$this->value = $value;
				$this->setIsUpdated();
			}

			return true;
		}

		/** @inheritDoc */
		public function getValue(array $params = null) {
			return $this->value;
		}

		/** @inheritDoc */
		protected function loadValue() {
			return $this->storage;
		}

		/** @inheritDoc */
		protected function saveValue() {
			$this->storage = (array) $this->value;
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			return true;
		}
	}
