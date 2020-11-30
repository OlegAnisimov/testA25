<?php
	namespace UmiCms\Classes\System\Translators;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDOMDocumentFactory;

	/**
	 * Интерфейс фасада трансляторов
	 * @package UmiCms\Classes\System\Translators
	 */
	interface iFacade {

		/**
		 * Конструктор
		 * @param iTranslatorFactory $translatorFactory фабрика трансляторов
		 * @param iDOMDocumentFactory $domDocumentFactory фабрика xml документов
		 */
		public function __construct(iTranslatorFactory $translatorFactory, iDOMDocumentFactory $domDocumentFactory);

		/**
		 * Переводит данные в json формат
		 * @param array $data данные
		 * @return string
		 * @throws \ErrorException
		 */
		public function translateToJson(array $data);

		/**
		 * Переводит данные в xml формат
		 * @param array $data данные
		 * @param string $rootNodeName имя корневого узла
		 * @return string
		 * @throws \ErrorException
		 */
		public function translateToXml(array $data, $rootNodeName = 'result');

		/**
		 * Переводит данные в xml документ
		 * @param array $data данные
		 * @param string $rootNodeName имя корневого узла
		 * @return \DOMDocument
		 * @throws \ErrorException
		 */
		public function translateToDomDocument(array $data, $rootNodeName = 'result');
	}