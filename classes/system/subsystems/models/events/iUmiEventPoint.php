<?php

	/** Интерфейс события */
	interface iUmiEventPoint {

		/**
		 * Конструктор
		 * @param string $id идентификатор события
		 * @throws coreException
		 */
		public function __construct($id);

		/**
		 * Возвращает идентификатор
		 * @return string
		 */
		public function getId();

		/**
		 * Устанавливает режим вызова события
		 * @param string $mode режим вызова
		 * @return iUmiEventPoint
		 * @throws coreException
		 */
		public function setMode($mode = 'process');

		/**
		 * Возвращает режим вызова события
		 * @return string
		 */
		public function getMode();

		/**
		 * Устанавливает список модулей, обработчики которых поддерживаются для события
		 * @param string[] $moduleList список имен модулей
		 * @example: [
		 *      # => 'news'
		 * ]
		 * @return iUmiEventPoint
		 */
		public function setModules(array $moduleList = []) : iUmiEventPoint;

		/**
		 * Возвращает список модулей, обработчики которых поддерживаются для события
		 * @return string[]
		 * @example: [
		 *      # => 'news'
		 * ]
		 */
		public function getModules();

		/**
		 * Устанавливает список обработчиков, которые поддерживаются событием
		 * @param string[] $methodList список обработчиков
		 * @example: [
		 *      # => 'news::setNewsItemPublishTime'
		 * ]
		 * @return iUmiEventPoint
		 */
		public function setMethods(array $methodList = []) : iUmiEventPoint;

		/**
		 * Возвращает список обработчиков, которые поддерживаются событием
		 * @return string[]
		 * @example: [
		 *      # => 'news::setNewsItemPublishTime'
		 * ]
		 */
		public function getMethods();

		/**
		 * Устанавливает параметр или перезаписывает его, если параметр был установлен ранее
		 * @param string $name название
		 * @param mixed $value значение
		 * @return iUmiEventPoint
		 * @throws coreException
		 */
		public function setParam($name, $value = null);

		/**
		 * Возвращает значение установленного параметра
		 * @param string $name название параметра
		 * @return mixed|null
		 */
		public function getParam($name);

		/**
		 * Устанавливает ссылку на значение
		 * @param string $name название
		 * @param &mixed $value значение
		 * @return iUmiEventPoint
		 * @throws coreException
		 */
		public function addRef($name, &$value);

		/**
		 * Возвращает ссылку на значение
		 * @param string $name название
		 * @return mixed|null
		 */
		public function &getRef($name);
	}
