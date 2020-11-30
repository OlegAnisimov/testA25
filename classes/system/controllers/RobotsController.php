<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\Classes\System\Utils\Robots\iGenerator as iRobotsGenerator;

	/**
	 * Класс контроллера robots.txt
	 * @package UmiCms\Classes\System\Controllers
	 */
	class RobotsController extends AbstractController implements iRobotsController {

		/** @var iRobotsGenerator $generator генератор robots.txt */
		private $generator;

		/** @inheritDoc */
		public function setGenerator(iRobotsGenerator $generator) {
			$this->generator = $generator;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			$this->buffer->contentType('text/plain');
			$this->buffer->charset('utf-8');

			$content = $this->generator->execute();

			$this->buffer->push($content);
			$this->buffer->end();
		}
	}