<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Utils\Dispatches\iCounter;

	/**
	 * Класс контроллера счетчика открытия рассылок
	 * @package UmiCms\Classes\System\Controllers
	 */
	class DispatchesCounterController extends AbstractController implements iDispatchesCounterController {

		/** @var iCounter $counter счетчик открытия рассылок */
		private $counter;

		/** @inheritDoc */
		public function setCounter(iCounter $counter) {
			$this->counter = $counter;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$path = isset($this->parameters['path']) ? (string) $this->parameters['path'] : null;

			if ($path !== null) {
				$path = trim($path, '-');
				$path = preg_replace('/[^a-z0-9]/i', '', $path);
				$this->counter->countEntry($path);
			}

			$this->buffer->contentType('image/gif');
			$path = $this->counter->generateImage();
			$this->buffer->push(file_get_contents($path));
			$this->buffer->end();
		}
	}