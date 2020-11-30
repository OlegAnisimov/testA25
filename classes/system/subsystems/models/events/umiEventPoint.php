<?php

	use UmiCms\Service;

	/** Класс события */
	class umiEventPoint implements iUmiEventPoint {

		/** @var string $id идентификатор */
		private $id;

		/** @var string $mode режим вызова */
		private $mode;

		/**
		 * @var array $moduleList список модулей, обработчики которых поддерживаются событием.
		 * Если список пуст - значит событие поддерживает обработчики всех модулей.
		 * @example: [
		 *      # => 'news'
		 * ]
		 */
		private $moduleList = [];

		/**
		 * @var array $methodList список обработчиков, которые поддерживаются событием
		 * Если список пуст - значит событие поддерживает все обработчики.
		 * @example: [
		 *      # => 'news::setNewsItemPublishTime'
		 * ]
		 */
		private $methodList = [];

		/**
		 * @var array $paramList список параметров
		 *
		 * [
		 *      name => value
		 * ]
		 */
		private $paramList = [];

		/**
		 * @var array $refList список ссылок
		 *
		 * [
		 *      name => &value
		 * ]
		 */
		private $refList = [];

		/** @var array $correctModeList список корректных режимов вызова событий */
		private static $correctModeList = [
			'before',
			'process',
			'after'
		];

		/** @inheritDoc */
		public function __construct($id) {
			$this->setId($id)->setMode();
		}

		/** @inheritDoc */
		public function getId() {
			return $this->id;
		}

		/** @inheritDoc */
		public function setMode($mode = 'process') {
			if (!is_string($mode) || empty($mode)) {
				throw new coreException('Incorrect mode given');
			}

			$mode = mb_strtolower($mode);
			$mode = trim($mode);

			if (!in_array($mode, self::$correctModeList)) {
				throw new coreException("Unknown mode given \"{$mode}\"");
			}

			$this->mode = $mode;
			return $this;
		}

		/** @inheritDoc */
		public function getMode() {
			return $this->mode;
		}

		/** @inheritDoc */
		public function setModules(array $moduleList = []) : iUmiEventPoint {
			$this->moduleList = $moduleList;
			return $this;
		}

		/** @inheritDoc */
		public function getModules() {
			return $this->moduleList;
		}

		/** @inheritDoc */
		public function setMethods(array $methodList = []) : iUmiEventPoint {
			$this->methodList = $methodList;
			return $this;
		}

		/** @inheritDoc */
		public function getMethods() {
			return $this->methodList;
		}

		/** @inheritDoc */
		public function setParam($name, $value = null) {
			if (!is_string($name) || empty($name)) {
				throw new coreException('Incorrect param name given');
			}

			$this->paramList[$name] = $value;
			return $this;
		}

		/** @inheritDoc */
		public function getParam($name) {
			if (!is_string($name) || empty($name)) {
				return null;
			}

			if (array_key_exists($name, $this->paramList)) {
				return $this->paramList[$name];
			}

			return null;
		}

		/** @inheritDoc */
		public function addRef($name, &$value) {
			if (!is_string($name) || empty($name)) {
				throw new coreException('Incorrect ref name given');
			}

			$this->refList[$name] = &$value;
			return $this;
		}

		/** @inheritDoc */
		public function &getRef($name) {
			$reference = null;

			if (!is_string($name) || empty($name)) {
				return $reference;
			}

			if (array_key_exists($name, $this->refList)) {
				$reference = &$this->refList[$name];
			}

			return $reference;
		}

		/**
		 * Устанавливает идентификатор события
		 * @param string $id идентификатор
		 * @return iUmiEventPoint
		 * @throws coreException
		 */
		private function setId($id) {
			if (!is_string($id) || empty($id)) {
				throw new coreException('Incorrect id given');
			}

			$this->id = $id;
			return $this;
		}

		/**
		 * Возвращает идентификатор события
		 * @return string
		 */
		public function getEventId() {
			return $this->getId();
		}

		/**
		 * Вызывает событие
		 * @return array|array[]
		 * @throws Exception
		 */
		public function call() {
			return Service::EventController()
				->callEvent($this, $this->moduleList, $this->methodList);
		}
	}
