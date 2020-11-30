<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iRegedit as iRegistry;
	use \iDomainsCollection as iDomainFacade;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Интерфейс контроллера проверки лицензии
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iLicenseCheckController extends iController {

		/**
		 * Устанавливает реестр
		 * @param iRegistry $registry реестр
		 * @return $this
		 */
		public function setRegistry(iRegistry $registry);

		/**
		 * Устанавливает фасад доменов
		 * @param iDomainFacade $domainFacade фасад доменов
		 * @return $this
		 */
		public function setDomainFacade(iDomainFacade $domainFacade);

		/**
		 * Устанавливает php шаблонизатор
		 * @param iTemplateEngineFactory $factory фабрика шаблонизаторов
		 * @return $this
		 */
		public function setPhpTemplateEngine(iTemplateEngineFactory $factory);
	}