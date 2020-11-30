<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iDomain as iDomain;
	use \iConfiguration as iConfig;
	use UmiCms\System\Selector\iFactory as iSelectorFactory;

	/**
	 * Класс разрешителя заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	class Resolver implements iResolver {

		/** @var iConfig $config конфигурация */
		private $config;

	    /** @var iSelectorFactory $selectorFactory фабрика селекторов */
		private $selectorFactory;

		/** @inheritDoc */
		public function __construct(iConfig $config, iSelectorFactory $selectorFactory) {
			$this->config = $config;
			$this->selectorFactory = $selectorFactory;
		}

		/** @inheritDoc */
		public function isEnabled($ip) {
			return (bool) $this->config->get('stub', 'enabled') && $this->isEnabledForIp($ip);
		}

		/** @inheritDoc */
		public function isEnabledForDomain($ip, iDomain $domain) {
			$stubDomainList = (array) $this->config->get('stub', 'enabled-for-domain');
			return in_array($domain->getHost(), $stubDomainList) && $this->isEnabledForIp($ip, $domain->getId());
		}

		/**
		 * Определяет доступна ли заглушка для заданного ip
		 * @param string $ip ip адрес
		 * @param int|null $domainId идентификатор домена
		 * @return bool
		 * @throws \selectorException
		 */
		private function isEnabledForIp($ip, $domainId = null) {
			$selector = $this->selectorFactory->createObjectTypeGuid('ip-whitelist');
			$selector->option('ignore-translate', true);

			if ($domainId) {
				$selector->where('domain_id')->equals($domainId);
			} else {
				$selector->where('domain_id')->isnull();
			}

			$selector->where('name')->equals($ip);
			$selector->limit(0, 1);
			$selector->result();

			$filterIpList = (array) $this->config->get('stub', 'filter.ip');
			return $selector->length() == 0 && !in_array($ip, $filterIpList);
		}
	}