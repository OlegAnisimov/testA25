<?php
	namespace UmiCms\Classes\System\Utils\DOM\Document;

	use \iConfiguration as iConfig;

	/**
	 * Класс фабрики xml документов
	 * @package UmiCms\Classes\System\Utils\DOM\Document
	 */
	class Factory implements iFactory {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @const string VERSION версия документа */
		const VERSION = '1.0';

		/** @const string CHARSET кодировка документа */
		const CHARSET = 'utf-8';

		/** @inheritDoc */
		public function __construct(iConfig $config) {
			$this->config = $config;
		}

		/** @inheritDoc */
		public function create() {
			$document = new \DOMDocument(self::VERSION, self::CHARSET);
			$document->formatOutput = $this->getFormatOutputMode();
			return $document;
		}

		/** @inheritDoc */
		public function createParser(\DOMDocument $document) : \DOMXPath {
			return new \DOMXPath($document);
		}

		/**
		 * Возвращает режим форматирования документа
		 * @return bool
		 */
		private function getFormatOutputMode() {
			return (bool) $this->config->get('kernel', 'xml-format-output');
		}
	}