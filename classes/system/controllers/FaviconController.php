<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Класс контролера фавикона
	 * @package UmiCms\Classes\System\Controllers
	 */
	class FaviconController extends AbstractController implements iFaviconController {

		/** @var iEventFactory $eventFactory фабрика событий */
		private $eventFactory;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @inheritDoc */
		public function setEventFactory(iEventFactory $eventFactory) {
			$this->eventFactory = $eventFactory;
			return $this;
		}

		/** @inheritDoc */
		public function setDomainDetector(iDomainDetector $detector) {
			$this->domainDetector = $detector;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$domain = $this->domainDetector->detect();
			$favicon = $domain->getFavicon();

			$event = $this->eventFactory->create('request-favicon', 'before');
			$event->addRef('favicon', $favicon)
				->setParam('buffer', $this->response)
				->setParam('domain', $domain)
				->call();

			if (!$favicon instanceof \iUmiImageFile || $favicon->getIsBroken()) {
				$this->buffer->status(404);
				$this->buffer->end();
			}

			$event->setMode('after')
				->call();

			$this->response->pushImage($favicon);
		}
	}