<?php

	/**
	 * Базовый класс для классов, которые реализуют ключевые сущности ядра системы.
	 * Реализует основные интерфейсы, которые должна поддерживать любая сущность.
	 */
	abstract class umiEntinty implements iUmiEntinty {

		/** @var int $id идентификатор сущности */
		protected $id;

		/** @var bool $is_updated флаг "обновлен" */
		protected $is_updated = false;

		/** @var string $store_type тип кешируемой сущности */
		protected $store_type = 'entity';

		/**
		 * Конструктор сущности, должен вызываться из коллекций
		 * @param int $id идентификатор сущности
		 * @param array|bool $row массив значений, который может быть передан для оптимизации
		 * @param bool $instantLoad нужно ли произвести немедленную загрузку данных
		 * @throws privateException
		 */
		public function __construct($id, $row = false, $instantLoad = true) {
			$this->setId($id);
			$this->is_updated = false;

			if ($instantLoad && $this->loadInfo($row) === false) {
				throw new privateException("Failed to load info for {$this->store_type} with id {$id}");
			}
		}

		/**
		 * Запрещает клонирование
		 * @throws coreException
		 */
		public function __clone() {
			throw new coreException('umiEntinty must not be cloned');
		}

		/** Деструктор */
		public function __destruct() {}

		/** @inheritDoc */
		public function getId() {
			return $this->id;
		}

		/**
		 * Изменяет id сущности
		 * @param int $id новый id сущности
		 * @return $this
		 */
		protected function setId($id) {
			$this->id = (int) $id;
			return $this;
		}

		/** @inheritDoc */
		public function getIsUpdated() {
			return $this->is_updated;
		}

		/** @inheritDoc */
		public function setIsUpdated($isUpdated = true) {
			$this->is_updated = (bool) $isUpdated;
		}

		/**
		 * Инициализирует сущность переданными данными или данными из БД
		 * @param array|bool $row полный набор свойств объекта или false
		 * @return bool
		 */
		abstract protected function loadInfo($row = false);

		/**
		 * Сохраняет в БД информацию о сущности
		 * @return bool
		 */
		abstract protected function save();

		/** @inheritDoc */
		public function commit() {
			if (!$this->getIsUpdated()) {
				return false;
			}

			$res = $this->save();
			$this->setIsUpdated(false);

			return $res;
		}

		/** @inheritDoc */
		public function update() {
			$res = $this->loadInfo();
			$this->setIsUpdated(false);
			return $res;
		}

		/** @inheritDoc */
		public static function filterInputString($string) {
			return ConnectionPool::getInstance()
				->getConnection()
				->escape($string);
		}

		/**
		 * Magic method
		 * @return string id объекта
		 */
		public function __toString() {
			return (string) $this->getId();
		}

		/** @inheritDoc */
		public function getStoreType() {
			return $this->store_type;
		}

		/** @inheritDoc */
		public function translateLabel($label) {
			$str = startsWith($label, 'i18n::') ? getLabel(mb_substr($label, 6)) : getLabel($label);
			return $str === null ? $label : $str;
		}

		/**
		 * Возвращает ключ строковой константы, если она определена, либо саму строку
		 * @param string $str строка, для которых нужно определить ключ
		 * @param string $pattern префикс ключа, используется внутри системы
		 * @return string ключ константы, либо параметр $str, если такого значение нет в списке констант
		 */
		protected function translateI18n($str, $pattern = '') {
			$label = ulangStream::getI18n($str, $pattern);
			return $label === null ? $str : $label;
		}

		/** @deprecated  */
		protected function getSavingInDestructor() {
			return false;
		}

		/** @deprecated */
		public function beforeSerialize() {}

		/** @deprecated */
		public function afterSerialize() {}

		/** @deprecated */
		public function afterUnSerialize() {}

		/** @deprecated  */
		public function setSavingInDestructor($flag = true) {
			return $this;
		}
	}
