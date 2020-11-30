<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\MiddleWares;
	use UmiCms\System\Streams\iFacade as iStreamsFacade;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Класс контроллера протоколов|потоков
	 * @package UmiCms\Classes\System\Controllers
	 */
	class StreamsController extends AbstractController implements iStreamsController {

		/** @var iStreamsFacade $streams фасад потоков|протоколов */
		private $streams;

		/** @var iTranslator $translator транслятор */
		private $translator;

		use MiddleWares\tAuth;
		use MiddleWares\tUmiManager;

		/** @inheritDoc */
		public function setStreams(iStreamsFacade $streams) {
			$this->streams = $streams;
			return $this;
		}

		/** @inheritDoc */
		public function setTranslator(iTranslator $translator) {
			$this->translator = $translator;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!isset($this->parameters['stream'])) {
				throw new \ErrorException('Incorrect router parameters given, stream expected');
			}

			define('STAT_DISABLE', true);
			define('VIA_HTTP_SCHEME', true);

			$stream = $this->parameters['stream'];
			$this->request->Get()->set('scheme', $stream);

			$this->loginByEnvironment();
			$this->validateUmiManagerRequest();

			try {
				$this->buffer->contentType($this->getContentType($stream));
				$this->buffer->charset('utf-8');
				$this->buffer->setHeader('X-Robots-Tag', 'none');
				$this->buffer->option('generation-time', !$this->request->isJson());

				$result = $this->streams->execute($stream);

				$this->buffer->push($result);
				$this->buffer->end();

			} catch (\Exception $exception) {
				$data = $this->getErrorData($stream, $exception);

				if ($data instanceof \DOMDocument) {
					$this->response->printXml($data);
				} else {
					$this->response->printJson($data);
				}
			}
		}

		/**
		 * Возвращает тип контента ответа
		 * @param string $stream имя потока|протокола
		 * @return string
		 */
		private function getContentType($stream) {
			if ($stream == 'ulang') {
				return 'text/plain';
			}

			return $this->request->isJson() ? 'application/json' : 'text/xml';
		}

		/**
		 * Возвращает ответ об ошибке выполнения потока|протокола
		 * @param string|bool $stream имя потока|протокола
		 * @param \Exception $exception ошибка
		 * @return array|\DOMDocument
		 * @throws \ErrorException
		 */
		private function getErrorData($stream, \Exception $exception) {
			$stream = $stream ?: 'unknown';

			$data = [
				$stream => [
					'error' => $exception->getMessage()
				]
			];

			if ($this->request->isJson()) {
				return $data;
			}

			return $this->translator->translateToDomDocument($data[$stream], $stream);
		}
	}