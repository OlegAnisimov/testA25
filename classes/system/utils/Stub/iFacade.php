<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iRegedit as iRegistry;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as iLanguageDetector;

	/**
	 * Интерфейс фасада заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	interface iFacade {

		/**
		 * Конструктор
		 * @param iRegistry $registry реестр
		 * @param iContent $content контент заглушки
		 * @param iResolver $resolver разрешитель заглушки
		 * @param iDomainDetector $domainDetector определитель домена
		 * @param iLanguageDetector $languageDetector определитель языка
		 */
		public function __construct(iRegistry $registry, iContent $content, iResolver $resolver, iDomainDetector $domainDetector, iLanguageDetector $languageDetector);

		/**
		 * Определяет включена ли заглушка
		 * @param string $ip ip адрес
		 * @return bool
		 * @throws \coreException
		 * @throws \selectorException
		 */
		public function isEnabled($ip);

		/**
		 * Возвращает контент заглушки
		 * @return bool|false|string
		 * @throws \coreException
		 */
		public function getContent();
	}