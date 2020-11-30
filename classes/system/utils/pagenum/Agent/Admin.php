<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use UmiCms\System\Utils\iUrl;
	use UmiCms\System\Session\iSession;

	/**
	 * Класс агента пагинации административной панели
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	class Admin extends Common implements iAdmin {

		/** @var iSession $sessionContainer контейнер сессии */
		private $sessionContainer;

		/** @inheritDoc */
		public function setSessionContainer(iSession $sessionContainer) : iAdmin {
			$this->sessionContainer = $sessionContainer;
			return $this;
		}

		/** @inheritDoc */
		public function resolve(string $url) : int {
			$urlContainer = $this->urlFactory->create($url);

			if ($this->issetPageNumber($url)) {
				$this->storePageNumber($urlContainer, $this->getPageNumber($urlContainer));
			}

			$number = $this->getStoredPageNumber($urlContainer);
			return $this->normalisePageNumber($number);
		}

		/**
		* Возвращает сохраненный номер страницы
		* @param iUrl $url адрес
		* @return int
		*/
		protected function getStoredPageNumber(iUrl $url) : int {
			$paging = (array) $this->sessionContainer->get('paging');
			$path = $url->getPath();
			$relId = $this->getRelationId($url);
			return  isset($paging[$path][$relId]) ? $paging[$path][$relId] : 0;
		}

		/**
		 * Возвращает идентификатор связанной страницы
		 * @param iUrl $url адрес
		 * @return int
		 */
		protected function getRelationId(iUrl $url) : int {
			$query = $url->getQueryAsList();
			$relationIdList = isset($query['rel']) ? (array) $query['rel'] : [];
			return (int) getFirstValue($relationIdList);
		}

		/**
		 * Сохраняет номер страницы
		 * @param iUrl $url адрес
		 * @param int $number номер страницы
		 * @return Admin
		 */
		protected function storePageNumber(iUrl $url, int $number) : Admin {
			$paging = (array) $this->sessionContainer->get('paging');
			$path = $url->getPath();
			$relId = $this->getRelationId($url);
			$paging[$path][$relId] = $number;
			$this->sessionContainer->set('paging', $paging);
			return $this;
		}

		/** @inheritDoc */
		protected function defineParameterKey() : void {
			$this->parameterKey = self::DEFAULT_KEY;
		}
	}