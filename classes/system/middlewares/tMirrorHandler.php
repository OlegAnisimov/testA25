<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use \iDomainsCollection as iDomains;
	use UmiCms\Classes\System\Controllers\AbstractController;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Трейт посредника в обработке запроса с зеркала
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tMirrorHandler {

		/** @var iDomains $domains фасад доменов */
		private $domains;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/**
		 * Устанавливает фасад доменов
		 * @param iDomains $domains фасад доменов
		 * @return $this
		 */
		public function setDomains(iDomains $domains) {
			$this->domains = $domains;
			return $this;
		}

		/**
		 * Устанавливает определитель домена
		 * @param iDomainDetector $domainDetector определитель домена
		 * @return $this
		 */
		public function setDomainDetector(iDomainDetector $domainDetector) {
			$this->domainDetector = $domainDetector;
			return $this;
		}

		/**
		 * Обрабатывает запрос, если он сделан с зеркала
		 * @param int $mode режим обработки запроса с зеркала
		 * @throws \coreException
		 */
		public function checkMirror($mode = iMirrorHandler::MODE_REDIRECT) {
			$currentDomain = $this->domainDetector->detect();
			/** @var AbstractController $this */
			$host = $this->getRequest()->host();

			if (!in_array($host, [$currentDomain->getHost(), $currentDomain->getHost(true)])) {
				$requestDomain = $this->domains->getDomainByHost($host);
				$this->processRequestFromMirror($currentDomain, $requestDomain, $mode);
			}
		}

		/**
		 * Обрабатывает запрос с зеркала домена
		 * @param \iDomain $currentDomain текущий домен
		 * @param \iDomain|bool $requestDomain запрошенный домен
		 * @param int $mode режим обработки запроса с зеркала
		 * @throws \coreException
		 */
		private function processRequestFromMirror(\iDomain $currentDomain, $requestDomain, $mode) {
			/** @var AbstractController $this */
			$request = $this->getRequest();

			if ($request->isCli()) {
				return;
			}

			$requestUnknownDomain = !$requestDomain instanceof \iDomain;

			if (!$requestUnknownDomain && $requestDomain->getId() !== $currentDomain->getId()) {
				return;
			}

			/** @var AbstractController $this */
			$buffer = $this->getBuffer();

			if ($mode == iMirrorHandler::MODE_REDIRECT) {
				$uri = $currentDomain->getUrl() . $request->uri();
				$buffer->redirect($uri);
			}

			if ($mode == iMirrorHandler::MODE_CRASH && $requestUnknownDomain) {
				$buffer->crash('invalid_domain');
			}

			if ($mode == iMirrorHandler::MODE_ADD_MIRROR && $requestUnknownDomain) {
				$currentDomain->addMirror($request->host());
			}

			if ($mode == iMirrorHandler::MODE_IGNORE) {
				//nothing
			}
		}
	}