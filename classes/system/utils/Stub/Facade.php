<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iRegedit as iRegistry;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;
	use UmiCms\System\Hierarchy\Language\iDetector as iLanguageDetector;

	/**
	 * Класс фасада заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	class Facade implements iFacade {

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iContent $content контент заглушки */
		private $content;

		/** @var iResolver $resolver разрешитель заглушки */
		private $resolver;

		/** @var iDomainDetector $domainDetector определитель домена */
		private $domainDetector;

		/** @var iLanguageDetector $languageDetector определитель языка */
		private $languageDetector;

		/**
		 * Конструктор
		 * @param iRegistry $registry реестр
		 * @param iContent $content контент заглушки
		 * @param iResolver $resolver разрешитель заглушки
		 * @param iDomainDetector $domainDetector определитель домена
		 * @param iLanguageDetector $languageDetector определитель языка
		 */
		public function __construct(iRegistry $registry, iContent $content, iResolver $resolver, iDomainDetector $domainDetector, iLanguageDetector $languageDetector) {
			$this->registry = $registry;
			$this->content = $content;
			$this->resolver = $resolver;
			$this->domainDetector = $domainDetector;
			$this->languageDetector = $languageDetector;
		}

		/**
		 * Определяет включена ли заглушка
		 * @param string $ip ip адрес
		 * @return bool
		 * @throws \coreException
		 * @throws \selectorException
		 */
		public function isEnabled($ip) {
			$isUseCustomSettings = $this->isUseCustomSettings();
			$domain = $this->domainDetector->detect();
			return ($this->resolver->isEnabled($ip) && !$isUseCustomSettings) || ($this->resolver->isEnabledForDomain($ip, $domain) && $isUseCustomSettings);
		}

		/**
		 * Возвращает контент заглушки
		 * @return bool|false|string
		 * @throws \coreException
		 */
		public function getContent() {
			return $this->isUseCustomSettings() ? $this->content->getCustom() : $this->content->getDefault();
		}

		/**
		 * Определяет нужно ли использовать пользовательские настройки заглушки
		 * @return bool
		 * @throws \coreException
		 */
		private function isUseCustomSettings() {
			$domainId = $this->domainDetector->detectId();
			$langId = $this->languageDetector->detectId();
			return (bool) $this->registry->get("//umiStub/$domainId/$langId/use-custom-settings");
		}
	}