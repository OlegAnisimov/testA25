<?php

	namespace UmiCms\System\Hierarchy\Domain;

	use UmiCms\System\Request\Http\iRequest;

	/**
	 * Класс определителя запрошенного домена
	 * @package UmiCms\System\Hierarchy\Domain
	 */
	class Detector implements iDetector {

		/** @var \iDomainsCollection $domainCollection */
		private $domainCollection;

		/** @var iRequest $httpRequest */
		private $httpRequest;

		/** @inheritDoc */
		public function __construct(\iDomainsCollection $domainCollection, iRequest $httpRequest) {
			$this->domainCollection = $domainCollection;
			$this->httpRequest = $httpRequest;
		}

		/** @inheritDoc */
		public function detect() {
			$requestDomain = $this->getRequestDomain();
			$defaultDomain = $this->getDefaultDomain();

			switch (true) {
				case ($requestDomain instanceof \iDomain) : {
					return $requestDomain;
				}
				case ($defaultDomain instanceof \iDomain) : {
					return $defaultDomain;
				}
				default : {
					throw new \coreException('Cannot detect current domain');
				}
			}
		}

		/** @inheritDoc */
		public function detectId() {
			return $this->detect()
				->getId();
		}

		/** @inheritDoc */
		public function detectHost() {
			return $this->detect()
				->getHost();
		}

		/** @inheritDoc */
		public function detectUrl() {
			return $this->detect()
				->getUrl();
		}

		/** @inheritDoc */
		public function detectMirrorUrl() : string {
			return $this->detect()
				->getCurrentUrl();
		}

		/**
		 * Возвращает запрошенный домен
		 * @return bool|\iDomain
		 */
		private function getRequestDomain() {
			$request = $this->getHttpRequest();
			$getContainer = $request->Get();
			$postContainer = $request->Post();
			$domainCollection = $this->getDomainCollection();

			if ($getContainer->isExist('domain_id') || $postContainer->isExist('domain_id')) {
				$domainId = $getContainer->get('domain_id') ?: $postContainer->get('domain_id');
				$domainId = is_array($domainId) ? getFirstValue($domainId) : $domainId;
				return $domainCollection->getDomain($domainId);
			}

			$host = $request->host();
			return $domainCollection->getDomainByHost($host);
		}

		/**
		 * Возврашает домен по умолчанию
		 * @return bool|\iDomain
		 */
		private function getDefaultDomain() {
			return $this->getDomainCollection()
				->getDefaultDomain();
		}

		/**
		 * Возвращает коллекцию доменов
		 * @return \iDomainsCollection
		 */
		private function getDomainCollection() {
			return $this->domainCollection;
		}

		/**
		 * Возвращает http запрос
		 * @return iRequest
		 */
		private function getHttpRequest() {
			return $this->httpRequest;
		}
	}
