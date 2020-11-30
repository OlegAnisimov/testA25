<?php
	namespace UmiCms\Classes\System\Utils\Robots;

	use \iRegedit as iRegistry;
	use \iConfiguration as iConfig;
	use UmiCms\System\Selector\iFactory as iSelectorFactory;
	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as iLanguageDetector;

	/**
	 * Интерфейс генератора robots.txt
	 * @package UmiCms\Classes\System\Utils\Robots
	 */
	interface iGenerator {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iRegistry $registry реестр
		 * @param iEventFactory $eventFactory фабрика событий
		 * @param iDomainDetector $domainDetector определитель домена
		 * @param iSelectorFactory $selectorFactory фабрика селекторов
		 * @param iLanguageDetector $languageDetector определитель языка
		 */
		public function __construct(
			iConfig $config, iRegistry $registry, iEventFactory $eventFactory,
			iDomainDetector $domainDetector, iSelectorFactory $selectorFactory, iLanguageDetector $languageDetector
		);

		/**
		 * Возвращает содержимое robots.txt
		 * @return string[]
		 * @throws \coreException
		 */
		public function execute();
	}