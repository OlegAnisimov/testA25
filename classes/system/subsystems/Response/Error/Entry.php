<?php
	namespace UmiCms\System\Response\Error;

	use UmiCms\System\Orm\Entity;

	/**
	 * Класс записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error
	 */
	class Entry extends Entity implements iEntry {

		/** @var string $url адрес запроса */
		protected $url;

		/** @var int $code код статуса ответа */
		protected $code;

		/** @var int $hitsCount количество обращений */
		protected $hitsCount;

		/** @var int $domainId идентификатор домена */
		protected $domainId;

		/** @var int $updateTime дата последнего обновления */
		protected $updateTime;

		/** @var \iDomainsCollection $domainCollection коллекция доменов */
		private $domainCollection;

		/** @inheritDoc */
		public function __construct(\iDomainsCollection $domainCollection) {
			$this->domainCollection = $domainCollection;
		}

		/** @inheritDoc */
		public function setUrl($url) {
			if (!is_string($url) || isEmptyString($url)) {
				throw new \ErrorException('Incorrect response error entry url given');
			}

			return $this->setDifferentValue('url', $this->clean($url));
		}

		/** @inheritDoc */
		public function getUrl() {
			return $this->url;
		}

		/** @inheritDoc */
		public function setCode($code) {
			if (!is_int($code) || $code <= 0 || !$this->isValidCode($code)) {
				throw new \ErrorException('Incorrect response error entry code given');
			}

			return $this->setDifferentValue('code', $code);
		}

		/** @inheritDoc */
		public function getCode() {
			return $this->code;
		}

		/** @inheritDoc */
		public function setHitsCount($count) {
			if (!is_int($count) || $count < 0) {
				throw new \ErrorException('Incorrect response error entry hist count given');
			}

			return $this->setDifferentValue('hitsCount', $count);
		}

		/** @inheritDoc */
		public function getHitsCount() {
			return $this->hitsCount;
		}

		/** @inheritDoc */
		public function incrementHitsCount() {
			$hitsCount = $this->getHitsCount();
			$hitsCount = ($hitsCount === null) ? 0 : $hitsCount;
			return $this->setHitsCount($hitsCount + 1);
		}

		/** @inheritDoc */
		public function getDomainId() {
			return $this->domainId;
		}

		/** @inheritDoc */
		public function setDomainId($id) {
			if (!is_int($id) || $id <= 0 || !$this->isValidDomainId($id)) {
				throw new \ErrorException('Incorrect response error entry domain id given');
			}

			return $this->setDifferentValue('domainId', $id);
		}

		/** @inheritDoc */
		public function getUpdateTime() {
			return $this->updateTime;
		}

		/** @inheritDoc */
		public function setUpdateTime($timestamp) {
			if (!is_int($timestamp) || $timestamp < 0) {
				throw new \ErrorException('Incorrect response error entry update time given');
			}

			return $this->setDifferentValue('updateTime', $timestamp);
		}

		/**
		 * Очищает адрес запроса от лишних данных
		 * @param string $url адрес запроса
		 * @return string
		 */
		private function clean($url) {
			$domain = $this->domainCollection->getDomain($this->domainId);

			if (!$domain instanceof \iDomain) {
				return $url;
			}

			return str_replace($domain->getCurrentUrl(), '', $url);
		}

		/**
		 * Определяет валиден ли код ошибки
		 * @param int $code код ошибки
		 * @return bool
		 */
		private function isValidCode($code) {
			return preg_match('|^[4-5]\d{2}$|', (string) $code);
		}

		/**
		 * Определяет валиден ли идентификатор домена
		 * @param int $id идентификатор домена
		 * @return bool
		 */
		private function isValidDomainId($id) {
			return $this->domainCollection->isExists($id);
		}
	}