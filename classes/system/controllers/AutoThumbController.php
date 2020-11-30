<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\AutoThumb\iGenerator;

	/**
	 * Класс контроллера автоматических миниатюр
	 * @package UmiCms\Classes\System\Controllers
	 */
	class AutoThumbController extends AbstractController implements iAutoThumbController {

		/** @var iGenerator $generator генератор миниатюр */
		private $generator;

		/** @inheritDoc */
		public function setGenerator(iGenerator $generator) {
			$this->generator = $generator;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!array_key_exists('file', $this->parameters)) {
				throw new \ErrorException('Incorrect router parameters given, file expected');
			}

			$file = $this->parameters['file'];

			try {
				$thumb = $this->generator->execute($file);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				$this->buffer->status(404);

				if (defined('DEBUG') && DEBUG) {
					$this->buffer->push($exception->getMessage());
				}

				$this->buffer->end();
			}

			$this->buffer->setHeader('X-Robots-Tag', 'none');
			$this->response->pushImage($thumb);
		}
	}