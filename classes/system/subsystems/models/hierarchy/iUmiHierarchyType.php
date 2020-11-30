<?php

	/** Базовый тип данных */
	interface iUmiHierarchyType extends iUmiEntinty {

		/**
		 * Возвращает название модуля, отвечающего за этот базовый тип
		 * @return string название модуля
		 */
		public function getName();

		/**
		 * Возвращает название метода, отвечающего за этот базовый тип
		 * @return string название метода
		 */
		public function getExt();

		/**
		 * Возвращает название базового типа
		 * @param bool $ignoreI18n игнорировать перевод
		 * @return string название типа
		 */
		public function getTitle($ignoreI18n = false);

		/**
		 * Устанавливает название модуля, отвечающего за этот базовый тип
		 * @param string $name название модуля
		 */
		public function setName($name);

		/**
		 * Устанавливает название базового типа
		 * @param string $title название типа
		 */
		public function setTitle($title);

		/**
		 * Устанавливает название метода, отвечающего за этот базовый тип
		 * @param string $ext название метода
		 */
		public function setExt($ext);

		/**
		 * Определяет нужно ли скрывать страницы типа
		 * @return bool
		 */
		public function hidePages() : bool;

		/**
		 * Устанавливает нужно ли скрывать станицы типа
		 * @param bool $status статус
		 * @return $this|iUmiHierarchyType
		 */
		public function setHidePages(bool $status = true) : iUmiHierarchyType;

		/**
		 * @see iUmiHierarchyType::getName()
		 * @return string
		 */
		public function getModule();

		/**
		 * @see iUmiHierarchyType::getExt()
		 * @return string
		 */
		public function getMethod();
	}
