<?php

	namespace UmiCms\System\Response;

	use UmiCms\System\Response\Buffer\iCollection;
	use UmiCms\System\Response\Buffer\iDetector;
	use UmiCms\System\Response\Buffer\iFactory;

	/**
	 * Класс фасада для работы с буферами вывода
	 * @package UmiCms\System\Response\Buffer
	 */
	class Facade implements iFacade {

		/** @var iFactory $factory фабрика буферов */
		private $factory;

		/** @var iDetector $detector определитель текущего буфера */
		private $detector;

		/** @var iCollection $collection коллекция буферов */
		private $collection;

		/** @inheritDoc */
		public function __construct(
			iFactory $factory,
			iDetector $detector,
			iCollection $collection
		) {
			$this->factory = $factory;
			$this->detector = $detector;
			$this->collection = $collection;
		}

		/** @inheritDoc */
		public function getCurrentBuffer() {
			$class = $this->getDetector()
				->detect();

			return $this->getBufferByClass($class);
		}

		/** @inheritDoc */
		public function getBuffer($name) {
			if (!is_string($name) || $name === '') {
				throw new \coreException('Incorrect buffer name given');
			}

			$class = $this->getClass($name);
			return $this->getBufferByClass($class);
		}

		/** @inheritDoc */
		public function getBufferByClass($class) {
			$collection = $this->getCollection();

			if (!$collection->exists($class)) {
				$buffer = $this->getFactory()
					->create($class);
				$collection->set($buffer);
			}

			return $collection->get($class);
		}

		/** @inheritDoc */
		public function getHttpBuffer() {
			return $this->getBuffer(self::HTTP);
		}

		/** @inheritDoc */
		public function getCliBuffer() {
			return $this->getBuffer(self::CLI);
		}

		/** @inheritDoc */
		public function getHttpDocBuffer() {
			return $this->getBuffer(self::HTTP_DOC);
		}

		/** @inheritDoc */
		public function printJson($data, $status = '200 OK') {
			$buffer = $this->getCurrentBuffer();
			$buffer->calltime();
			$buffer->status($status);
			$buffer->contentType('application/json');
			$buffer->charset('utf-8');
			$buffer->option('generation-time', false);
			$buffer->push(json_encode($data));
			$buffer->end();
		}

		/** @inheritDoc */
		public function printXml(\DOMDocument $document, $status = '200 OK') {
			$this->printXmlAsString($document->saveXML());
		}

		/** @inheritDoc */
		public function printXmlAsString($xml, $status = '200 OK') {
			$buffer = $this->getCurrentBuffer();
			$buffer->calltime();
			$buffer->status($status);
			$buffer->contentType('text/xml');
			$buffer->charset('utf-8');
			$buffer->option('generation-time', false);
			$buffer->push($xml);
			$buffer->end();
		}

		/** @inheritDoc */
		public function printHtml($html, $status = '200 OK') {
			$buffer = $this->getCurrentBuffer();
			$buffer->calltime();
			$buffer->status($status);
			$buffer->contentType('text/html');
			$buffer->charset('utf-8');
			$buffer->option('generation-time', true);
			$buffer->push($html);
			$buffer->end();
		}

		/** @inheritDoc */
		public function pushImage(\iUmiFile $image, $status = '200 OK') {
			$buffer = $this->getCurrentBuffer();
			$buffer->status($status);
			$buffer->setHeader('Content-Length', (string) $image->getSize());
			$buffer->contentType($image->getMimeType());
			$buffer->option('generation-time', false);
			$buffer->push($image->getContent());
			$buffer->end();
		}

		/** @inheritDoc */
		public function download(\iUmiFile $file) {
			$this->validateFile($file);
			$file->download();
		}

		/** @inheritDoc */
		public function downloadAndDelete(\iUmiFile $file) {
			$this->validateFile($file);
			$file->download(true);
		}

		/** @inheritDoc */
		public function isCorrect() {
			return $this->getCurrentBuffer()->getStatusCode() == 200;
		}

		/**
		 * Валидирует скачиваемый файл
		 * @param \iUmiFile $file файл
		 * @throws \InvalidArgumentException
		 */
		private function validateFile(\iUmiFile $file) {
			if ($file->getIsBroken()) {
				throw new \InvalidArgumentException(sprintf('Broken file given: "%s"', $file->getFilePath(true)));
			}
		}

		/**
		 * Возвращает имя класса по имени буфера
		 * @param string $name имя буфера
		 * @return string
		 */
		private function getClass($name) {
			return sprintf('%sOutputBuffer', $name);
		}

		/**
		 * Возвращает фабрику буферов
		 * @return iFactory
		 */
		private function getFactory() {
			return $this->factory;
		}

		/**
		 * Возвращает определитель текущего буфера
		 * @return iDetector
		 */
		private function getDetector() {
			return $this->detector;
		}

		/**
		 * Возвращает коллекцию буферов
		 * @return iCollection
		 */
		private function getCollection() {
			return $this->collection;
		}

		/** @deprecated */
		public function getUpdateTime() {
			return 0;
		}
	}
