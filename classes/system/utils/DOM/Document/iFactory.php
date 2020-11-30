<?php
	namespace UmiCms\Classes\System\Utils\DOM\Document;

	use \iConfiguration as iConfig;

	/**
	 * Интерфейс фабрики xml документов
	 * @package UmiCms\Classes\System\Utils\DOM\Document
	 */
	interface iFactory {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 */
		public function __construct(iConfig $config);

		/**
		 * Создает dom документ
		 * @return \DOMDocument
		 */
		public function create();

		/**
		 * Создает парсер dom документов
		 * @param \DOMDocument $document
		 * @return \DOMXPath
		 */
		public function createParser(\DOMDocument $document) : \DOMXPath;
	}