<?php

	use UmiCms\Service;

	/**
	 * Абстрактный тип экспорта.
	 * @todo: вынести из этого класса фабрику и, возможно, коллекцию
	 * Также этот класс умеет загружать конкретные реализации
	 * типов экспорта, @see umiExporter::get()
	 */
	abstract class umiExporter implements iUmiExporter {

		/** @const int Количество экспортируемых сущностей за одну итерацию по умолчанию */
		const DEFAULT_EXPORT_LIMIT = 25;

		/** @const string наименование исходной кодировки данных */
		const SOURCE_ENCODING = 'utf-8';

		/** @var string Тип экспорта (префикс конкретного класса) */
		protected $type = '';

		/** @var bool|string Название источника экспорта */
		protected $source_name = false;

		/** @var bool Статус завершенности экспорта */
		protected $completed = true;

		/** @var array $serializeOptions опции сериализации */
		protected $serializeOptions = [];

		/** @var string[] список поддерживаемых кодировок */
		protected static $supportedEncodings = ['utf-8', 'windows-1251', 'cp1251'];

		/** @var string наименование кодировки, в которой будут экспортированы данные */
		protected $encoding = 'windows-1251';

		/** @var iUmiExporter[] Кэш загруженных конкретных типов экспорта */
		private static $exporters = [];

		/** @inheritDoc */
		abstract public function export($exportList, $ignoreList);

		/**
		 * Возвращает объект конкретного типа экспорта по названию его класса
		 * @param string $prefix префикс названия класса типа экспорта
		 * @return self
		 * @throws publicException
		 */
		final public static function get($prefix) {
			$exporter = self::loadExporter($prefix);
			if ($exporter instanceof self) {
				return $exporter;
			}
			throw new publicException("Can't load exporter for type \"{$prefix}\"");
		}

		/** @inheritDoc */
		public function __construct($type) {
			$this->type = $type;
		}

		/** @inheritDoc */
		public function setOutputBuffer() {
			return Service::Response()
				->getCurrentBuffer();
		}

		/** @inheritDoc */
		public function getType() {
			return $this->type;
		}

		/** @inheritDoc */
		public function getFileExt() {
			return 'xml';
		}

		/** @inheritDoc */
		public function getSourceName() {
			return $this->source_name ?: $this->type;
		}

		/** @inheritDoc */
		public function setSourceName($sourceName = false) {
			$this->source_name = $sourceName;
		}

		/** @inheritDoc */
		public function getIsCompleted() {
			return $this->completed;
		}

		/** @inheritDoc */
		public function setSerializeOptions(array $option) {
			$this->serializeOptions = $option;
			return $this;
		}

		/** @inheritDoc */
		public function setEncoding($encoding) {
			if (in_array(mb_strtolower($encoding), self::$supportedEncodings)) {
				$this->encoding = $encoding;
			} else {
				throw new InvalidArgumentException("Encoding '${encoding}' is not supported");
			}
		}

		/**
		 * Создает xml экспортер
		 * @param string $sourceName имя источника данных
		 * @param int|bool $limit ограничение на количество экспортируемых сущностей
		 * @return iXmlExporter
		 */
		protected function createXmlExporter($sourceName, $limit = false) {
			$exporter = new xmlExporter($sourceName, $limit);
			return $this->initXmlExporter($exporter);
		}

		/**
		 * Инициализирует xml экспортер
		 * @param iXmlExporter $exporter
		 * @return iXmlExporter
		 */
		protected function initXmlExporter(iXmlExporter $exporter) {
			foreach ($this->serializeOptions as $name => $value) {
				$exporter->setSerializeOption($name, $value);
			}

			return $exporter;
		}

		/**
		 * Загружает конкретный тип экспорта и возвращает его
		 * @param string $prefix префикс названия класса типа экспорта
		 * @return iUmiExporter
		 * @throws publicException
		 */
		private static function loadExporter($prefix) {
			if (isset(self::$exporters[$prefix])) {
				return self::$exporters[$prefix];
			}

			self::$exporters[$prefix] = false;
			$className = "{$prefix}Exporter";

			if (!class_exists($className)) {
				$filePath = mainConfiguration::getInstance()
						->includeParam('system.kernel') . "subsystems/export/exporters/{$className}.php";

				if (!is_file($filePath)) {
					throw new publicException("Can't load exporter \"{$filePath}\" for \"{$prefix}\" file type");
				}

				require $filePath;
			}

			if (!class_exists($className)) {
				throw new publicException("Exporter class \"{$className}\" not found");
			}

			$exporter = new $className($prefix);
			if (!$exporter instanceof self) {
				throw new publicException("Exporter class \"{$className}\" should be instance of umiExporter");
			}

			self::$exporters[$prefix] = $exporter;
			return $exporter;
		}

		/**
		 * Возвращает количество экспортируемых сущностей за одну итерацию
		 * @return int
		 */
		protected function getLimit() {
			$blockSize = (int) mainConfiguration::getInstance()
				->get('modules', 'exchange.export.limit');
			if ($blockSize <= 0) {
				$blockSize = self::DEFAULT_EXPORT_LIMIT;
			}
			return $blockSize;
		}

		/** Возвращает путь до директории экспорта */
		protected function getExportPath() {
			return SYS_TEMP_PATH . '/export/';
		}

		/**
		 * Возвращает экземпляр сценария
		 * @return bool|iUmiObject
		 */
		protected function getScenario() {
			$id = isset($_REQUEST['param0']) ? $_REQUEST['param0'] : null;
			return umiObjectsCollection::getInstance()
				->getObject($id);
		}

		/**
		 * Возвращает xsl шаблонизатор для перевода umiDump в другой xml формат
		 * @param string|null $templateName имя шаблона
		 * @return iUmiTemplater
		 * @throws coreException
		 * @throws publicException
		 */
		protected function getUmiDumpTemplateEngine($templateName = null) {
			$templatePath = $this->getTemplatePath($templateName);

			if (!is_file($templatePath)) {
				throw new publicException("Can't load template {$templatePath}");
			}

			return umiTemplater::create('XSLT', $templatePath);
		}

		/**
		 * Возвращает путь до шаблона
		 * @param string|null $templateName имя шаблона
		 * @return string
		 */
		protected function getTemplatePath($templateName = null) {
			$templateName = $templateName ?: $this->getType();
			return CURRENT_WORKING_DIR . '/styles/common/xsl/export/' . $templateName . '.xsl';
		}
	}
