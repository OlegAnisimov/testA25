<?php
	namespace UmiCms\Utils\DomainKeyCode;

	use \iRegedit as iRegistry;
	use \iConfiguration as iConfig;
	use \iDomainsCollection as iDomains;
	use \iCmsController as iModuleLoader;
	use UmiCms\System\Request\iFacade as iRequest;
	use \umiRemoteFileGetter as iLicenseServerClient;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Интерфейс сохранения доменного ключа
	 * @package UmiCms\Utils\DomainKeyCode
	 */
	interface iSaver {

		/**
		 * Конструктор
		 * @param iRegistry $registry реестр
		 * @param iRequest $request запрос
		 * @param iConfig $config конфигурация
		 * @param iDomains $domains фасад доменов
		 * @param iModuleLoader $moduleLoader загрузчик модулей
		 * @param iTemplateEngineFactory $templateEngineFactory фабрика шаблонизаторов
		 * @param iLicenseServerClient $client клиент сервера лицензий
		 */
		public function __construct(
			iRegistry $registry, iRequest $request, iConfig $config, iDomains $domains,
			iModuleLoader $moduleLoader, iTemplateEngineFactory $templateEngineFactory, iLicenseServerClient $client
		);

		/**
		 * Сохраняет ключ на сервере лицензий
		 * @param string $keyCode лицензионный ключ
		 * @return \SimpleXMLElement
		 * @throws \umiRemoteFileGetterException
		 */
		public function saveToServer($keyCode);

		/**
		 * Сохраняет ключ в системе
		 * @param string $keyCode доменный ключ
		 * @param string $edition системное имя редакции
		 * @throws \ErrorException
		 * @throws \coreException
		 * @throws \databaseException
		 * @throws \publicException
		 * @throws \wrongParamException
		 */
		public function saveToCms($keyCode, $edition);
	}