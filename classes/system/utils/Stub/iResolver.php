<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iDomain as iDomain;
	use \iConfiguration as iConfig;
	use UmiCms\System\Selector\iFactory as iSelectorFactory;

	/**
	 * Интерфейс разрешителя заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	interface iResolver {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iSelectorFactory $selectorFactory фабрика селекторов
		 */
		public function __construct(iConfig $config, iSelectorFactory $selectorFactory);

		/**
		 * Определяет включена ли заглушка
		 * @param string $ip ip адрес
		 * @return bool
		 * @throws \selectorException
		 */
		public function isEnabled($ip);

		/**
		 * Определяет включена ли заглушка для домена
		 * @param string $ip ip адрес
		 * @param iDomain $domain домен
		 * @return bool
		 * @throws \selectorException
		 */
		public function isEnabledForDomain($ip, iDomain $domain);
	}