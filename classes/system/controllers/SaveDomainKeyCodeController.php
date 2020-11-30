<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iRegedit as iRegistry;
	use UmiCms\Utils\DomainKeyCode\iSaver;
	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDOMDocumentFactory;

	/**
	 * Класс контроллера сохранения доменного ключа
	 * @package UmiCms\Classes\System\Controllers
	 */
	class SaveDomainKeyCodeController extends AbstractController implements iSaveDomainKeyCodeController {

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iSaver $saver сохранитель доменного ключа */
		private $saver;

		/** @var iDOMDocumentFactory $domDocumentFactory фабрика xml документов */
		private $domDocumentFactory;

		/** @inheritDoc */
		public function setRegistry(iRegistry $registry) {
			$this->registry = $registry;
			return $this;
		}

		/** @inheritDoc */
		public function setSaver(iSaver $saver) {
			$this->saver = $saver;
			return $this;
		}

		/** @inheritDoc */
		public function setDomDocumentFactory(iDOMDocumentFactory $domDocumentFactory) {
			$this->domDocumentFactory = $domDocumentFactory;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			if ($this->registry->checkSelfKeycode()) {
				$this->finish();
			}

			$get = $this->request->Get();
			$keyCode = $get->get('keycode');
			$domainKeyCode = $get->get('domain_keycode');
			$edition = $get->get('license_codename');

			if (($domainKeyCode === null || $edition === null) && $keyCode !== null) {
				$this->saveLicenseToServer($keyCode);
			}

			$this->saveLicenseToClient($domainKeyCode, $edition);
		}

		/**
		 * Запрашивает сохранение ключа на сервер лицензий
		 * @param string $keyCode ключ
		 * @throws \Exception
		 */
		private function saveLicenseToServer($keyCode) {
			try {
				$simpleDocument = $this->saver->saveToServer($keyCode);
				$document = $this->domDocumentFactory->create();
				$document->loadXML($simpleDocument->saveXML());
				$this->response->printXml($document);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				$this->buffer->stop();
			}
		}

		/**
		 * Запрашивает сохранение ключа в систему
		 * @param string $keyCode ключ
		 * @param string $edition редакция
		 * @throws \Exception
		 */
		private function saveLicenseToClient($keyCode, $edition) {
			try {
				$this->saver->saveToCms($keyCode, $edition);
				$this->finish();
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				$this->buffer->stop();
			}
		}

		/** Выводит результат операции в буффер */
		private function finish() {
			$this->buffer->contentType('application/javascript');
			$this->buffer->charset('utf-8');
			$this->buffer->setHeader('X-Robots-Tag', 'none');
			$this->buffer->push('/** ok */');
			$this->buffer->end();
		}
	}