<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use UmiCms\System\Utils\iUrl;
	use \iConfiguration as iConfig;
	use UmiCms\System\Utils\Url\iFactory as iUrlFactory;

	/**
	 * Класс агента пагинации по-умолчанию
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	class Common implements iCommon {

		/** @var iConfig $config конфигурация */
		protected $config;

		/** @var iUrlFactory $urlFactory фабрика адресов */
		protected $urlFactory;

		/** @var string $parameterKey ключ номера страницы */
		protected $parameterKey;

		/** @inheritDoc */
		public function __construct(iConfig $config, iUrlFactory $urlFactory) {
			$this->config = $config;
			$this->urlFactory = $urlFactory;
			$this->defineParameterKey();
		}

		/** @inheritDoc */
		public function resolve(string $url) : int {
			$urlContainer = $this->urlFactory->create($url);
			$number = $this->getPageNumber($urlContainer);
			return $this->normalisePageNumber($number);
		}

		/** @inheritDoc */
		public function issetPageNumber(string $url) : bool {
			$urlContainer = $this->urlFactory->create($url);
			$query = $urlContainer->getQueryAsList();
			return array_key_exists($this->parameterKey, $query);
		}

		/** @inheritDoc */
		public function cleanUrl(string $url) : string {
			$urlContainer = $this->urlFactory->create($url);
			$query = $urlContainer->getQueryAsList();

			if (array_key_exists($this->parameterKey, $query)) {
				unset($query[$this->parameterKey]);
			}

			$urlContainer->setQueryAsList($query);
			return $urlContainer->__toString();
		}

		/** @inheritDoc */
		public function generateUri(string $url, int $number) : string {
			$url = $this->cleanUrl($url);
			$urlContainer = $this->urlFactory->create($url);
			$query = $urlContainer->getQueryAsList();
			$query[$this->parameterKey] = $number;
			$urlContainer->setQueryAsList($query);
			return $urlContainer->__toString();
		}

		/**
		* Возвращает номер страницы из адреса
		* @param iUrl $url адрес
		* @return int
		*/
		protected function getPageNumber(iUrl $url) : int {
			$query = $url->getQueryAsList();
			return isset($query[$this->parameterKey]) ? (int) $query[$this->parameterKey] : 0;
		}

		/**
		* Исправляет номер страницы
		* @param int $number номер страницы
		* @return int
		*/
		protected function normalisePageNumber(int $number) : int {
			return ($number < 0) ? 0 : $number;
		}

		/** Определяет ключ параметра номера страницы */
		protected function defineParameterKey() : void {
			$key = (string) $this->config->get('page-navigation', 'parameter-key') ?: self::DEFAULT_KEY;
			$this->parameterKey = $key;
		}
	}