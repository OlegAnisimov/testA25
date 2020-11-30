<?php
	namespace UmiCms\Classes\System\Translators;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDOMDocumentFactory;

	/**
	 * Класс фасада трансляторов
	 * @package UmiCms\Classes\System\Translators
	 */
	class Facade implements iFacade {

		/** @var iTranslatorFactory $translatorFactory фабрика трансляторов */
		private $translatorFactory;

		/** @var iDOMDocumentFactory $domDocumentFactory фабрика xml документов */
		private $domDocumentFactory;

		/** @inheritDoc */
		public function __construct(iTranslatorFactory $translatorFactory, iDOMDocumentFactory $domDocumentFactory) {
			$this->translatorFactory = $translatorFactory;
			$this->domDocumentFactory = $domDocumentFactory;
		}

		/** @inheritDoc */
		public function translateToJson(array $data) {
			return $this->translatorFactory->create($this->translatorFactory::JSON)
				->translateToJson($data);
		}

		/** @inheritDoc */
		public function translateToXml(array $data, $rootNodeName = 'result') {
			$document = $this->translateToDomDocument($data, $rootNodeName);
			return $document->saveXML();
		}

		/** @inheritDoc */
		public function translateToDomDocument(array $data, $rootNodeName = 'result') {
			$document = $this->domDocumentFactory->create();
			$rootNode = $document->createElement($rootNodeName);
			$document->appendChild($rootNode);
			$rootNode->setAttribute('xmlns:xlink', 'http://www.w3.org/TR/xlink');

			$this->translatorFactory->create($this->translatorFactory::XML)
				->setDocument($document)
				->translateToXml($rootNode, $data);
			return $document;
		}
	}