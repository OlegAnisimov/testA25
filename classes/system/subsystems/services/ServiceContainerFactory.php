<?php
	use \umiEventsController as EventController;
	use \iUmiEventsController as iEventController;
	use UmiCms\System\Protection\Jwt\Token\Factory;
	use UmiCms\System\Protection\Jwt\Token\iFactory;
	use UmiCms\System\Protection\PrivateKeys\Bunch;
	use UmiCms\System\Protection\PrivateKeys\iBunch;
	use UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client\OAuth;
	use UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client\iOAuth;
	use UmiCms\Classes\System\Utils\Html\Parser;
	use UmiCms\Classes\System\Utils\Html\iParser;
	use UmiCms\System\Events\Handler\Factory as EventHandlerFactory;
	use UmiCms\System\Events\Handler\iFactory as iEventHandlerFactory;
	use UmiCms\System\Events\Executor\Factory as EventHandlerExecutorFactory;
	use UmiCms\System\Events\Executor\iFactory as iEventHandlerExecutorFactory;
	use UmiCms\System\Events\Executor\Module as ModuleEventHandlerExecutor;
	use UmiCms\System\Events\Executor\iModule as iModuleEventHandlerExecutor;
	use UmiCms\System\Selector\Order\Factory as SelectorOrderFactory;
	use UmiCms\System\Selector\Order\iFactory as iSelectorOrderFactory;
	use UmiCms\System\Selector\Order\Attribute\Property\GlobalOrd as SelectorOrderPropertyGlobalOrd;
	use UmiCms\System\Selector\Order\Attribute\Property\iGlobalOrd as iSelectorOrderPropertyGlobalOrd;
	use UmiCms\System\Cookies\Options as CookieOptions;
	use UmiCms\System\Cookies\iOptions as iCookieOptions;

	/**
	 * Фабрика контейнеров сервисов
	 * Через него следует получать желаемый контейнер,
	 * @example $ServiceContainer = ServiceContainerFactory::create();
	 */
	class ServiceContainerFactory implements iServiceContainerFactory {

		/** @var ServiceContainer[] $serviceContainerList список контейнеров сервисов */
		private static $serviceContainerList = [];

		/** @inheritDoc */
		public static function create($type = self::DEFAULT_CONTAINER_TYPE, array $rules = [], array $parameters = []) {
			if (isset(self::$serviceContainerList[$type])) {
				return self::$serviceContainerList[$type];
			}

			$defaultRules = self::getDefaultRules();
			$defaultParameters = self::getDefaultParameters();

			if ($type !== self::DEFAULT_CONTAINER_TYPE) {
				$rules = array_merge($defaultRules, $rules);
				$parameters = array_merge($defaultParameters, $parameters);
			} else {
				$rules = $defaultRules;
				$parameters = $defaultParameters;
			}

			return self::$serviceContainerList[$type] = new ServiceContainer($rules, $parameters);
		}

		/**
		 * Возвращает список параметров по умолчанию для контейнера сервисов
		 * @return array
		 * @throws Exception
		 * @throws coreException
		 */
		protected static function getDefaultParameters() {
			return [
				'connection' => ConnectionPool::getInstance()->getConnection(),
				'ImageProcessor' => imageUtils::getImageProcessor(),
				'imageFileHandler' => new umiImageFile(__FILE__),
				'baseUmiCollectionConstantMap' => new baseUmiCollectionConstantMap(),
				'directoriesHandler' => new umiDirectory(__FILE__),
				'umiRedirectsCollection' => 'Redirects',
				'MailTemplatesCollection' => 'MailTemplates',
				'MailNotificationsCollection' => 'MailNotifications',
				'MailVariablesCollection' => 'MailVariables'
			];
		}

		/**
		 * Возвращает список правил инстанцирования сервисов по умолчанию для контейнера сервисов
		 * @return array
		 */
		protected static function getDefaultRules() {
			return [
				'Redirects' => [
					'class' => 'umiRedirectsCollection',
					'arguments' => [
						new ParameterReference('umiRedirectsCollection'),
					],
					'calls' => [
						[
							'method' => 'setConnection',
							'arguments' => [
								new ParameterReference('connection')
							]
						],
						[
							'method' => 'setConfiguration',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						],
						[
							'method' => 'setMap',
							'arguments' => [
								new InstantiableReference('umiRedirectsConstantMap')
							]
						],
						[
							'method' => 'setResponse',
							'arguments' => [
								new \ServiceReference('Response')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new \ServiceReference('DomainDetector')
							]
						],
						[
							'method' => 'setDomainCollection',
							'arguments' => [
								new \ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setLanguageCollection',
							'arguments' => [
								new \ServiceReference('LanguageCollection')
							]
						]
					]
				],

				'UrlFactory' => [
					'class' => 'UmiCms\System\Utils\Url\Factory',
				],

				'ExceptionHandlerFactory' => [
					'class' => 'UmiCms\Classes\System\Exception\Handler\Factory',
					'arguments' => [
						new ServiceReference('Configuration')
					]
				],

				'SystemExceptionHandler' => [
					'class' => 'UmiCms\Classes\System\Exception\Handler\System',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('Response')
					]
				],

				'MailVariables' => [
					'class' => 'MailVariablesCollection',
					'arguments' => [
						new ParameterReference('MailVariablesCollection'),
					],
					'calls' => [
						[
							'method' => 'setConnection',
							'arguments' => [
								new ParameterReference('connection')
							]
						],
						[
							'method' => 'setMap',
							'arguments' => [
								new InstantiableReference('mailVariablesConstantMap')
							]
						],
						[
							'method' => 'setSourceIdBinderFactory',
							'arguments' => [
								new ServiceReference('ImportEntitySourceIdBinderFactory')
							]
						]
					]
				],

				'MailTemplates' => [
					'class' => 'MailTemplatesCollection',
					'arguments' => [
						new ParameterReference('MailTemplatesCollection'),
					],
					'calls' => [
						[
							'method' => 'setConnection',
							'arguments' => [
								new ParameterReference('connection')
							]
						],
						[
							'method' => 'setMap',
							'arguments' => [
								new InstantiableReference('mailTemplatesConstantMap')
							]
						],
						[
							'method' => 'setSourceIdBinderFactory',
							'arguments' => [
								new ServiceReference('ImportEntitySourceIdBinderFactory')
							]
						]
					]
				],

				'MailNotifications' => [
					'class' => 'MailNotificationsCollection',
					'arguments' => [
						new ParameterReference('MailNotificationsCollection'),
					],
					'calls' => [
						[
							'method' => 'setConnection',
							'arguments' => [
								new ParameterReference('connection')
							]
						],
						[
							'method' => 'setMap',
							'arguments' => [
								new InstantiableReference('mailNotificationsConstantMap')
							]
						],
						[
							'method' => 'setDomainCollection',
							'arguments' => [
								new \ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setLanguageCollection',
							'arguments' => [
								new \ServiceReference('LanguageCollection')
							]
						],
						[
							'method' => 'setLanguageDetector',
							'arguments' => [
								new \ServiceReference('LanguageDetector')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new \ServiceReference('DomainDetector')
							]
						],
					]
				],

				'AuthenticationRulesFactory' => [
					'class' => 'UmiCms\System\Auth\AuthenticationRules\Factory',
					'arguments' => [
						new ServiceReference('PasswordHashAlgorithm'),
						new ServiceReference('SelectorFactory'),
						new ServiceReference('HashComparator')
					]
				],

				'PasswordHashAlgorithm' => [
					'class' => 'UmiCms\System\Auth\PasswordHash\Algorithm'
				],

				'Authentication' => [
					'class' => 'UmiCms\System\Auth\Authentication',
					'arguments' => [
						new ServiceReference('AuthenticationRulesFactory'),
						new ServiceReference('Session'),
						new ServiceReference('CookieJar')
					]
				],

				'Authorization' => [
					'class' => 'UmiCms\System\Auth\Authorization',
					'arguments' => [
						new ServiceReference('Session'),
						new ServiceReference('CsrfProtection'),
						new ServiceReference('permissionsCollection'),
						new ServiceReference('CookieJar'),
						new ServiceReference('objects'),
						new ServiceReference('Configuration'),
						new ServiceReference('PasswordHashAlgorithm')
					]
				],

				'SystemUsersPermissions' => [
					'class' => 'UmiCms\System\Permissions\SystemUsersPermissions',
					'arguments' => [
						new ServiceReference('objects')
					]
				],

				'Auth' => [
					'class' => 'UmiCms\System\Auth\Auth',
					'arguments' => [
						new ServiceReference('Authentication'),
						new ServiceReference('Authorization'),
						new ServiceReference('SystemUsersPermissions'),
						new ServiceReference('objects'),
					]
				],

				'CsrfProtection' => [
					'class' => '\UmiCms\System\Protection\CsrfProtection',
					'arguments' => [
						new ServiceReference('Session'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('IdnConverter'),
						new ServiceReference('DomainCollection'),
						new ServiceReference('EventPointFactory'),
						new ServiceReference('HashComparator')
					],
				],

				'Request' => [
					'class' => '\UmiCms\System\Request\Facade',
					'arguments' => [
						new ServiceReference('RequestHttp'),
						new ServiceReference('BrowserDetector'),
						new ServiceReference('RequestModeDetector'),
						new ServiceReference('RequestPathResolver'),
						new ServiceReference('PageNumAgentFacade')
					]
				],

				'CookieJar' => [
					'class' => 'UmiCms\System\Cookies\CookieJar',
					'arguments' => [
						new ServiceReference('CookiesFactory'),
						new ServiceReference('CookiesResponsePool'),
						new ServiceReference('RequestHttpCookies'),
						new ServiceReference('Encrypter'),
						new ServiceReference(iCookieOptions::SERVICE_NAME),
					]
				],

				iCookieOptions::SERVICE_NAME => [
					'class' => CookieOptions::class,
					'arguments' => [
						new ServiceReference('DomainDetector')
					]
				],

				'Session' => [
					'class' => 'UmiCms\System\Session\Session',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('CookieJar')
					]
				],

				'templates' => [
					'class' => 'templatesCollection',
				],

				'pages' => [
					'class' => 'umiHierarchy',
				],

				'cmsController' => [
					'class' => 'cmsController',
				],

				'objects' => [
					'class' => 'umiObjectsCollection',
				],

				'permissionsCollection' => [
					'class' => 'permissionsCollection',
				],

				'connectionPool' => [
					'class' => 'ConnectionPool',
				],

				'objectTypes' => [
					'class' => 'umiObjectTypesCollection'
				],

				'hierarchyTypes' => [
					'class' => 'umiHierarchyTypesCollection'
				],

				'typesHelper' => [
					'class' => 'umiTypesHelper'
				],

				'fields' => [
					'class' => 'umiFieldsCollection'
				],

				'FieldTypeFacade' => [
					'class' => 'umiFieldTypesCollection'
				],

				'objectPropertyFactory' => [
					'class' => 'UmiCms\System\Data\Object\Property\Factory',
					'arguments' => [
						new ServiceReference('fields'),
						new ServiceReference('objects')
					],
				],

				'ActionFactory' => [
					'class' => 'ActionFactory',
					'calls' => [
						[
							'method' => 'setConfiguration',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						]
					]
				],

				'BaseXmlConfigFactory' => [
					'class' => 'BaseXmlConfigFactory'
				],

				'AtomicOperationCallbackFactory' => [
					'class' => 'AtomicOperationCallbackFactory'
				],

				'TransactionFactory' => [
					'class' => 'TransactionFactory',
					'calls' => [
						[
							'method' => 'setConfiguration',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						]
					]
				],

				'ManifestSourceFactory' => [
					'class' => 'ManifestSourceFactory'
				],

				'ManifestFactory' => [
					'class' => 'ManifestFactory',
					'arguments' => [
						new ServiceReference('BaseXmlConfigFactory'),
						new ServiceReference('AtomicOperationCallbackFactory'),
						new ServiceReference('ManifestSourceFactory')
					],
					'calls' => [
						[
							'method' => 'setConfiguration',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						]
					]
				],

				'EventPointFactory' => [
					'class' => '\UmiCms\System\Events\EventPointFactory'
				],

				'SiteMapUpdater' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Updater',
					'arguments' => [
						new ServiceReference('SiteMapLocationFacade'),
						new ServiceReference('pages'),
						new ServiceReference('EventPointFactory'),
						new ServiceReference('SiteMapImageFacade'),
						new ServiceReference('Configuration')
					]
				],

				'CacheKeyGenerator' => [
					'class' => '\UmiCms\System\Cache\Key\Generator',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'CacheEngineFactory' => [
					'class' => '\UmiCms\System\Cache\EngineFactory'
				],

				'CountriesFactory' => [
					'class' => '\UmiCms\Classes\System\Entities\Country\CountriesFactory'
				],

				'CitiesFactory' => [
					'class' => '\UmiCms\Classes\System\Entities\City\CitiesFactory'
				],

				'DirectoryFactory' => [
					'class' => '\UmiCms\Classes\System\Entities\Directory\Factory'
				],

				'FileFactory' => [
					'class' => '\UmiCms\Classes\System\Entities\File\Factory'
				],

				'ImageFactory' => [
					'class' => '\UmiCms\Classes\System\Entities\Image\Factory'
				],

				'FileUploader' => [
					'class' => '\UmiCms\Classes\System\Entities\File\Uploader',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('Configuration'),
						new ServiceReference('DirectoryFactory'),
						new ServiceReference('FileFactory'),
					]
				],

				'ImageUploader' => [
					'class' => '\UmiCms\Classes\System\Entities\Image\Uploader',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('Configuration'),
						new ServiceReference('DirectoryFactory'),
						new ServiceReference('FileFactory'),
						new ServiceReference('ImageFactory'),
						new ServiceReference('ImageProcessorFactory')
					]
				],

				'ImageProcessorFactory' => [
					'class' => '\UmiCms\System\Utils\Image\Processor\Factory'
				],

				'UmiDumpDirectoryDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Directory',
					'arguments' => [
						new ServiceReference('DirectoryFactory')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						],
						[
							'method' => 'setFilePathConverter',
							'arguments' => [
								new ServiceReference('UmiDumpFilePathConverter')
							]
						]
					]
				],

				'UmiDumpFileDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\File',
					'arguments' => [
						new ServiceReference('FileFactory'),
						new ServiceReference('DirectoryFactory')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						],
						[
							'method' => 'setFilePathConverter',
							'arguments' => [
								new ServiceReference('UmiDumpFilePathConverter')
							]
						]
					]
				],

				'Registry' => [
					'class' => 'regedit',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('CacheEngineFactory')
					]
				],

				'UmiDumpRegistryDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Registry',
					'arguments' => [
						new ServiceReference('Registry')
					]
				],

				'ImportSourceIdBinder' => [
					'class' => 'umiImportRelations'
				],

				'EntitySourceIdBinder' => [
					'class' => 'entityImportRelations',
					'arguments' => [
						new ServiceReference('ImportSourceIdBinder'),
						new ParameterReference('connection')
					]
				],

				'UmiDumpDomainDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Domain',
					'arguments' => [
						new ServiceReference('DomainCollection')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpLanguageDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Language',
					'arguments' => [
						new ServiceReference('LanguageCollection')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpObjectDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Objects',
					'arguments' => [
						new ServiceReference('objects')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpTemplateDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Template',
					'arguments' => [
						new ServiceReference('templates')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpObjectTypeDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\ObjectType',
					'arguments' => [
						new ServiceReference('objectTypes')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpPageDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Page',
					'arguments' => [
						new ServiceReference('pages')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'RestrictionCollection' => [
					'class' => '\UmiCms\System\Data\Field\Restriction\Collection',
					'arguments' => [
						new ParameterReference('connection')
					]
				],

				'UmiDumpRestrictionDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Restriction',
					'arguments' => [
						new ServiceReference('RestrictionCollection'),
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpFieldGroupDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\FieldGroup',
					'arguments' => [
						new ServiceReference('objectTypes')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpFieldDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Field',
					'arguments' => [
						new ServiceReference('fields'),
						new ServiceReference('objectTypes')
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpPermissionDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Permission',
					'arguments' => [
						new ServiceReference('permissionsCollection'),
						new ServiceReference('SystemUsersPermissions'),
						new ServiceReference('objects'),
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'ImportEntitySourceIdBinderFactory' => [
					'class' => '\UmiCms\System\Import\UmiDump\Entity\Helper\SourceIdBinder\Factory',
					'arguments' => [
						new ServiceReference('EntitySourceIdBinder')
					],
				],

				'UmiDumpEntityDemolisher' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Entity',
					'arguments' => [
						new ServiceReference('ImportEntitySourceIdBinderFactory'),
						new ServiceContainerReference(),
						new ServiceReference('cmsController'),
					],
					'calls' => [
						[
							'method' => 'setSourceIdBinder',
							'arguments' => [
								new ServiceReference('ImportSourceIdBinder')
							]
						]
					]
				],

				'UmiDumpDemolisherTypeFactory' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Type\Factory',
					'arguments' => [
						new ServiceContainerReference()
					]
				],

				'UmiDumpDemolisherExecutor' => [
					'class' => '\UmiCms\System\Import\UmiDump\Demolisher\Executor',
					'arguments' => [
						new ServiceReference('UmiDumpDemolisherTypeFactory')
					]
				],

				'RegistryPart' => [
					'class' => '\UmiCms\System\Registry\Part',
					'arguments' => [
						new ServiceReference('Registry')
					]
				],

				'ExtensionRegistry' => [
					'class' => '\UmiCms\System\Extension\Registry',
					'arguments' => [
						new ServiceReference('Registry')
					]
				],

				'ExtensionLoader' => [
					'class' => '\UmiCms\System\Extension\Loader',
					'arguments' => [
						new ServiceReference('DirectoryFactory'),
						new ServiceReference('FileFactory')
					]
				],

				'ModulePermissionLoader' => [
					'class' => '\UmiCms\System\Module\Permissions\Loader',
					'arguments' => [
						new ServiceReference('cmsController'),
						new ServiceReference('DirectoryFactory'),
						new ServiceReference('FileFactory')
					]
				],

				'CacheFrontend' => [
					'class' => 'cacheFrontend',
					'arguments' => [
						new ServiceReference('CacheEngineFactory'),
						new ServiceReference('CacheKeyGenerator'),
						new ServiceReference('Configuration'),
						new ServiceReference('CacheKeyValidatorFactory'),
						new ServiceReference('RequestModeDetector')
					]
				],

				'CacheKeyValidatorFactory' => [
					'class' => '\UmiCms\System\Cache\Key\Validator\Factory',
					'arguments' => [
						new ServiceReference('Configuration')
					]
				],

				'BrowserDetector' => [
					'class' => 'BrowserDetect',
					'arguments' => [
						new ServiceReference('CacheEngineFactory')
					]
				],

				'StaticCacheStorage' => [
					'class' => '\UmiCms\System\Cache\Statical\Storage',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('FileFactory'),
						new ServiceReference('DirectoryFactory')
					]
				],

				'StaticCacheKeyGenerator' => [
					'class' => '\UmiCms\System\Cache\Statical\Key\Generator',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('pages'),
						new ServiceReference('Configuration'),
						new ServiceReference('DomainCollection')
					]
				],

				'CacheStateValidator' => [
					'class' => 'UmiCms\System\Cache\State\Validator',
					'arguments' => [
						new ServiceReference('Auth'),
						new ServiceReference('Request'),
						new ServiceReference('cmsController'),
						new ServiceReference('Response'),
					]
				],

				'StaticCacheKeyValidatorFactory' => [
					'class' => '\UmiCms\System\Cache\Statical\Key\Validator\Factory',
					'arguments' => [
						new ServiceReference('Configuration')
					]
				],

				'StaticCache' => [
					'class' => 'UmiCms\System\Cache\Statical\Facade',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('CacheStateValidator'),
						new ServiceReference('StaticCacheKeyValidatorFactory'),
						new ServiceReference('StaticCacheKeyGenerator'),
						new ServiceReference('StaticCacheStorage')
					]
				],

				'ResponseBufferDetector' => [
					'class' => 'UmiCms\System\Response\Buffer\Detector',
					'arguments' => [
						new ServiceReference('RequestModeDetector')
					]
				],

				'ResponseBufferFactory' => [
					'class' => 'UmiCms\System\Response\Buffer\Factory'
				],

				'ResponseBufferCollection' => [
					'class' => 'UmiCms\System\Response\Buffer\Collection'
				],

				'Response' => [
					'class' => 'UmiCms\System\Response\Facade',
					'arguments' => [
						new ServiceReference('ResponseBufferFactory'),
						new ServiceReference('ResponseBufferDetector'),
						new ServiceReference('ResponseBufferCollection')
					]
				],

				'ResponseUpdateTimeCalculator' => [
					'class' => 'UmiCms\System\Response\UpdateTime\Calculator',
					'arguments' => [
						new ServiceReference('pages'),
						new ServiceReference('objects'),
						new ServiceReference('StaticCache')
					]
				],

				'Configuration' => [
					'class' => 'mainConfiguration',
				],

				'BrowserCacheEngineFactory' => [
					'class' => 'UmiCms\System\Cache\Browser\Engine\Factory',
					'arguments' => [
						new ServiceContainerReference()
					]
				],

				'BrowserCache' => [
					'class' => 'UmiCms\System\Cache\Browser\Facade',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('BrowserCacheEngineFactory'),
						new ServiceReference('CacheStateValidator')
					]
				],

				'LoggerFactory' => [
					'class' => 'UmiCms\Utils\Logger\Factory',
					'arguments' => [
						new ServiceReference('DirectoryFactory')
					]
				],

				'SelectorFactory' => [
					'class' => 'UmiCms\System\Selector\Factory'
				],

				'QuickExchangeSourceDetector' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\Source\Detector',
					'arguments' => [
						new ServiceReference('cmsController')
					]
				],

				'QuickExchangeFileDownloader' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\File\Downloader',
					'arguments' => [
						new ServiceReference('QuickExchangeSourceDetector'),
						new ServiceReference('FileFactory'),
						new ServiceReference('Response'),
						new ServiceReference('Configuration')
					]
				],

				'QuickExchangeFileUploader' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\File\Uploader',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Configuration')
					]
				],

				'QuickExchangeCsvExporter' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\Csv\Exporter',
					'arguments' => [
						new ServiceReference('QuickExchangeSourceDetector'),
						new ServiceReference('Request')
					]
				],

				'QuickExchangeCsvImporter' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\Csv\Importer',
					'arguments' => [
						new ServiceReference('QuickExchangeSourceDetector'),
						new ServiceReference('Request'),
						new ServiceReference('FileFactory'),
						new ServiceReference('Configuration'),
						new ServiceReference('Session')
					]
				],

				'QuickExchange' => [
					'class' => 'UmiCms\Classes\System\Utils\QuickExchange\Facade',
					'arguments' => [
						new ServiceReference('QuickExchangeCsvExporter'),
						new ServiceReference('QuickExchangeCsvImporter'),
						new ServiceReference('QuickExchangeFileDownloader'),
						new ServiceReference('QuickExchangeFileUploader'),
						new ServiceReference('Configuration'),
						new ServiceReference('Response')
					]
				],

				'DataObjectFactory' => [
					'class' => 'UmiCms\System\Data\Object\Factory'
				],

				'HierarchyElementFactory' => [
					'class' => 'UmiCms\System\Hierarchy\Element\Factory'
				],

				'CookiesFactory' => [
					'class' => 'UmiCms\System\Cookies\Factory'
				],

				'CookiesResponsePool' => [
					'class' => 'UmiCms\System\Cookies\ResponsePool'
				],

				'RequestHttpCookies' => [
					'class' => 'UmiCms\System\Request\Http\Cookies'
				],

				'RequestHttpFiles' => [
					'class' => 'UmiCms\System\Request\Http\Files'
				],

				'RequestHttpGet' => [
					'class' => 'UmiCms\System\Request\Http\Get'
				],

				'RequestHttpPost' => [
					'class' => 'UmiCms\System\Request\Http\Post'
				],

				'RequestHttpServer' => [
					'class' => 'UmiCms\System\Request\Http\Server'
				],

				'RequestHttp' => [
					'class' => 'UmiCms\System\Request\Http\Request',
					'arguments' => [
						new ServiceReference('RequestHttpCookies'),
						new ServiceReference('RequestHttpServer'),
						new ServiceReference('RequestHttpPost'),
						new ServiceReference('RequestHttpGet'),
						new ServiceReference('RequestHttpFiles')
					]
				],

				'RequestModeDetector' => [
					'class' => 'UmiCms\System\Request\Mode\Detector',
					'arguments' => [
						new ServiceReference('RequestPathResolver')
					]
				],

				'RequestPathResolver' => [
					'class' => 'UmiCms\System\Request\Path\Resolver',
					'arguments' => [
						new ServiceReference('RequestHttpGet'),
						new ServiceReference('Configuration')
					]
				],

				'RegistrySettings' => [
					'class' => 'UmiCms\System\Registry\Settings',
					'arguments' => [
						new ServiceReference('Registry'),
					]
				],

				'DateFactory' => [
					'class' => 'UmiCms\Classes\System\Entities\Date\Factory'
				],

				'IdnConverter' => [
					'class' => 'idna_convert'
				],

				'DomainCollection' => [
					'class' => 'domainsCollection',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('IdnConverter')
					]
				],

				'DomainDetector' => [
					'class' => 'UmiCms\System\Hierarchy\Domain\Detector',
					'arguments' => [
						new ServiceReference('DomainCollection'),
						new ServiceReference('RequestHttp')
					]
				],

				'CaptchaSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Captcha\Settings\Factory',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'WatermarkSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Watermark\Settings\Factory',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'StubSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Stub\Settings\Factory',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'SeoSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Seo\Settings\Factory',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'MailSettings' => [
					'class' => 'UmiCms\Classes\System\Utils\Mail\Settings',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'MailSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Mail\Settings\Factory',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'SmtpMailSettingsFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Mail\Settings\Smtp\Factory',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector')
					]
				],

				'LanguageCollection' => [
					'class' => 'langsCollection',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('DomainCollection')
					]
				],

				'LanguageDetector' => [
					'class' => 'UmiCms\System\Hierarchy\Language\Detector',
					'arguments' => [
						new ServiceReference('LanguageCollection'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('Request'),
						new ServiceReference('pages')
					]
				],

				'YandexOAuthClient' => [
					'class' => 'UmiCms\Classes\System\Utils\Api\Http\Json\Yandex\Client\OAuth',
					'arguments' => [
						new ServiceReference('LoggerFactory'),
						new ServiceReference('Configuration'),
					]
				],

				'ObjectTypeHierarchyRelationFactory' => [
					'class' => 'UmiCms\System\Data\Object\Type\Hierarchy\Relation\Factory'
				],

				'ObjectTypeHierarchyRelationRepository' => [
					'class' => 'UmiCms\System\Data\Object\Type\Hierarchy\Relation\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('ObjectTypeHierarchyRelationFactory')
					]
				],

				'ObjectTypeHierarchyRelationMigration' => [
					'class' => 'UmiCms\System\Data\Object\Type\Hierarchy\Relation\Migration',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('ObjectTypeHierarchyRelationRepository')
					]
				],

				'FieldTypeCollection' => [
					'class' => 'umiFieldTypesCollection'
				],

				'FieldTypeMigration' => [
					'class' => 'UmiCms\System\Data\Field\Type\Migration',
					'arguments' => [
						new ServiceReference('objectTypes'),
						new ServiceReference('fields'),
						new ServiceReference('FieldTypeCollection')
					]
				],

				'ObjectPropertyValueTableSchema' => [
					'class' => 'UmiCms\System\Data\Object\Property\Value\Table\Schema'
				],

				'ObjectPropertyRepository' => [
					'class' => 'UmiCms\System\Data\Object\Property\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('objectPropertyFactory'),
						new ServiceReference('fields'),
						new ServiceReference('ObjectPropertyValueTableSchema')
					],
				],

				'ObjectPropertyValueDomainIdMigration' => [
					'class' => 'UmiCms\System\Data\Object\Property\Value\DomainId\Migration',
					'arguments' => [
						new ServiceReference('ObjectPropertyValueTableSchema'),
						new ParameterReference('connection')
					]
				],

				'ObjectPropertyValueLangIdMigration' => [
					'class' => 'UmiCms\System\Data\Object\Property\Value\LangId\Migration',
					'arguments' => [
						new ServiceReference('ObjectPropertyValueTableSchema'),
						new ParameterReference('connection')
					]
				],

				'ObjectPropertyValueImgFileMigration' => [
					'class' => 'UmiCms\System\Data\Object\Property\Value\ImgFile\Migration',
					'arguments' => [
						new ServiceReference('ObjectPropertyValueTableSchema'),
						new ParameterReference('connection')
					]
				],

				'ObjectPropertyValueFileMigration' => [
					'class' => 'UmiCms\System\Data\Object\Property\Value\File\Migration',
					'arguments' => [
						new ServiceReference('ObjectPropertyValueTableSchema'),
						new ParameterReference('connection')
					]
				],

				'SolutionRegistry' => [
					'class' => '\UmiCms\System\Solution\Registry',
					'arguments' => [
						new ServiceReference('Registry')
					]
				],

				'UmiDumpSolutionPostfixBuilder' => [
					'class' => '\UmiCms\System\Import\UmiDump\Helper\Solution\Postfix\Builder',
				],

				'UmiDumpFilePathConverter' => [
					'class' => '\UmiCms\System\Import\UmiDump\Helper\File\Path\Converter',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UmiDumpSolutionPostfixFilter')
					]
				],

				'UmiDumpSolutionPostfixFilter' => [
					'class' => '\UmiCms\System\Import\UmiDump\Helper\Solution\Postfix\Filter',
				],

				'Protection' => [
					'class' => '\UmiCms\System\Protection\Security',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Configuration'),
						new ServiceReference('CsrfProtection'),
						new ServiceReference('HashComparator')
					]
				],

				'Encrypter' => [
					'class' => '\UmiCms\System\Protection\Encrypter',
					'arguments' => [
						new ServiceReference('Configuration')
					]
				],

				'HashComparator' => [
					'class' => '\UmiCms\System\Protection\HashComparator',
				],

				'SystemInfo' => [
					'class' => 'systemInfo',
					'arguments' => [
						new ServiceReference('Registry'),
						new ParameterReference('connection'),
						new ServiceReference('DomainCollection'),
						new ServiceReference('Request'),
						new ServiceReference('Configuration'),
						new ServiceReference('FileFactory'),
					]
				],

				'TradeOfferFactory' => [
					'class' => '\UmiCms\System\Trade\Offer\Factory',
					'arguments' => [
						new ServiceReference('TradeOffer')
					]
				],

				'TradeOfferVendorCodeGenerator' => [
					'class' => '\UmiCms\System\Trade\Offer\Vendor\Code\Generator'
				],

				'TradeOfferMapper' => [
					'class' => '\UmiCms\System\Trade\Offer\Mapper'
				],

				'TradeOfferRepository' => [
					'class' => '\UmiCms\System\Trade\Offer\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('TradeOfferSchema'),
						new ServiceReference('TradeOfferAttributeAccessor'),
						new ServiceReference('TradeOfferFactory'),
						new ServiceReference('TradeOfferBuilder')
					]
				],

				'TradeOfferCollection' => [
					'class' => '\UmiCms\System\Trade\Offer\Collection',
					'arguments' => [
						new ServiceReference('TradeOfferAttributeAccessor')
					]
				],

				'TradeOfferFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Facade',
					'arguments' => [
						new ServiceReference('TradeOfferCollection'),
						new ServiceReference('TradeOfferRepository'),
						new ServiceReference('TradeOfferFactory'),
						new ServiceReference('TradeOfferAttributeAccessor'),
						new ServiceReference('TradeOfferRelationAccessor'),
						new ServiceReference('TradeOfferBuilder')
					],
					'calls' => [
						[
							'method' => 'setDataObjectFacade',
							'arguments' => [
								new ServiceReference('TradeOfferDataObjectFacade')
							]
						],
						[
							'method' => 'setOfferPriceFacade',
							'arguments' => [
								new ServiceReference('TradeOfferPriceFacade')
							]
						],
						[
							'method' => 'setVendorCoderGenerator',
							'arguments' => [
								new ServiceReference('TradeOfferVendorCodeGenerator')
							]
						],
						[
							'method' => 'setTypeFacade',
							'arguments' => [
								new ServiceReference('TradeOfferDataObjectTypeFacade')
							]
						],
						[
							'method' => 'setStockBalanceFacade',
							'arguments' => [
								new ServiceReference('TradeStockBalanceFacade')
							]
						],
						[
							'method' => 'setRegistry',
							'arguments' => [
								new ServiceReference('Registry')
							]
						]
					]
				],

				'TradeOfferDataObjectTypeFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Data\Object\Type\Facade',
					'arguments' => [
						new ServiceReference('objectTypes'),
					]
				],

				'TradeOfferDataObjectFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Data\Object\Facade',
					'arguments' => [
						new ServiceReference('objects'),
						new ServiceReference('TradeOfferDataObjectTypeFacade')
					]
				],

				'TradeStockBalanceCollection' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Collection',
					'arguments' => [
						new ServiceReference('TradeStockBalanceAttributeAccessor')
					]
				],

				'TradeStockBalanceFactory' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Factory',
					'arguments' => [
						new ServiceReference('TradeStockBalance')
					]
				],

				'TradeStockBalanceRepository' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('TradeStockBalanceSchema'),
						new ServiceReference('TradeStockBalanceAttributeAccessor'),
						new ServiceReference('TradeStockBalanceFactory'),
						new ServiceReference('TradeStockBalanceBuilder')
					],
				],

				'TradeStockBalanceFacade' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Facade',
					'arguments' => [
						new ServiceReference('TradeStockBalanceCollection'),
						new ServiceReference('TradeStockBalanceRepository'),
						new ServiceReference('TradeStockBalanceFactory'),
						new ServiceReference('TradeStockBalanceAttributeAccessor'),
						new ServiceReference('TradeStockBalanceRelationAccessor'),
						new ServiceReference('TradeStockBalanceBuilder'),
					],
				],

				'TradeStockBalanceMapper' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Mapper'
				],

				'OrmEntityRepositoryHistory' => [
					'class' => '\UmiCms\System\Orm\Entity\Repository\History'
				],

				'TradeStockFactory' => [
					'class' => '\UmiCms\System\Trade\Stock\Factory'
				],

				'TradeStockFacade' => [
					'class' => '\UmiCms\System\Trade\Stock\Facade',
					'arguments' => [
						new ServiceReference('TradeStockFactory'),
						new ServiceReference('objects'),
						new ServiceReference('objectTypes'),
					]
				],

				'TradeOfferPriceFactory' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Factory',
					'arguments' => [
						new ServiceReference('TradeOfferPrice')
					]
				],

				'TradeOfferPriceMapper' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Mapper'
				],

				'TradeOfferPriceCollection' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Collection',
					'arguments' => [
						new ServiceReference('TradeOfferPriceAttributeAccessor')
					]
				],

				'TradeOfferPriceRepository' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('TradeOfferPriceSchema'),
						new ServiceReference('TradeOfferPriceAttributeAccessor'),
						new ServiceReference('TradeOfferPriceFactory'),
						new ServiceReference('TradeOfferPriceBuilder')
					],
				],

				'TradeOfferPriceFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Facade',
					'arguments' => [
						new ServiceReference('TradeOfferPriceCollection'),
						new ServiceReference('TradeOfferPriceRepository'),
						new ServiceReference('TradeOfferPriceFactory'),
						new ServiceReference('TradeOfferPriceAttributeAccessor'),
						new ServiceReference('TradeOfferPriceRelationAccessor'),
						new ServiceReference('TradeOfferPriceBuilder')
					],
					'calls' => [
						[
							'method' => 'setCurrencyFacade',
							'arguments' => [
								new ServiceReference('Currencies')
							]
						],
						[
							'method' => 'setTypeFacade',
							'arguments' => [
								new ServiceReference('TradeOfferPriceTypeFacade')
							],
						]
					]
				],

				'CurrencyCollection' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Currency\Collection'
				],

				'CurrencyFactory' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Currency\Factory'
				],

				'CurrencyRepository' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Currency\Repository',
					'arguments' => [
						new ServiceReference('CurrencyFactory'),
						new ServiceReference('SelectorFactory'),
					]
				],

				'Currencies' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Currency\Facade',
					'arguments' => [
						new ServiceReference('CurrencyRepository'),
						new ServiceReference('CurrencyCollection'),
						new ServiceReference('Configuration'),
						new ServiceReference('CurrencyCalculator'),
						new ServiceReference('FavoriteCurrencyFacade')
					]
				],

				'CurrencyCalculator' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Currency\Calculator'
				],

				'TradeOfferPriceTypeCollection' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Collection',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeAttributeAccessor')
					]
				],

				'TradeOfferPriceTypeMapper' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Mapper'
				],

				'TradeOfferPriceTypeFactory' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Factory',
					'arguments' => [
						new ServiceReference('TradeOfferPriceType')
					]
				],

				'TradeOfferPriceTypeRepository' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('TradeOfferPriceTypeSchema'),
						new ServiceReference('TradeOfferPriceTypeAttributeAccessor'),
						new ServiceReference('TradeOfferPriceTypeFactory'),
						new ServiceReference('TradeOfferPriceTypeBuilder')
					],
				],

				'TradeOfferPriceTypeFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Facade',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeCollection'),
						new ServiceReference('TradeOfferPriceTypeRepository'),
						new ServiceReference('TradeOfferPriceTypeFactory'),
						new ServiceReference('TradeOfferPriceTypeAttributeAccessor'),
						new ServiceReference('TradeOfferPriceTypeRelationAccessor'),
						new ServiceReference('TradeOfferPriceTypeBuilder')
					]
				],

				'TradeOfferPriceTypeExporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Exporter',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeFacade'),
						new ServiceReference('TradeOfferPriceTypeSchema'),
					]
				],

				'TradeOfferPriceTypeImporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Importer',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeFacade'),
						new ServiceReference('TradeOfferPriceTypeSchema'),
					]
				],

				'TradeOfferPriceExporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Exporter',
					'arguments' => [
						new ServiceReference('TradeOfferPriceFacade'),
						new ServiceReference('TradeOfferPriceSchema'),
					]
				],

				'TradeOfferPriceImporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Importer',
					'arguments' => [
						new ServiceReference('TradeOfferPriceFacade'),
						new ServiceReference('TradeOfferPriceSchema'),
					]
				],

				'TradeStockBalanceExporter' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Exporter',
					'arguments' => [
						new ServiceReference('TradeStockBalanceFacade'),
						new ServiceReference('TradeStockBalanceSchema'),
					]
				],

				'TradeStockBalanceImporter' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Importer',
					'arguments' => [
						new ServiceReference('TradeStockBalanceFacade'),
						new ServiceReference('TradeStockBalanceSchema'),
					]
				],

				'TradeOfferExporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Exporter',
					'arguments' => [
						new ServiceReference('TradeOfferFacade'),
						new ServiceReference('TradeOfferSchema'),
					]
				],

				'TradeOfferImporter' => [
					'class' => '\UmiCms\System\Trade\Offer\Importer',
					'arguments' => [
						new ServiceReference('TradeOfferFacade'),
						new ServiceReference('TradeOfferSchema'),
					]
				],

				'TradeOfferCharacteristicFactory' => [
					'class' => '\UmiCms\System\Trade\Offer\Characteristic\Factory',
					'arguments' => [
						new ServiceReference('objects')
					]
				],

				'TradeOfferCharacteristicMapper' => [
					'class' => '\UmiCms\System\Trade\Offer\Characteristic\Mapper'
				],

				'TradeOfferCharacteristicCollection' => [
					'class' => '\UmiCms\System\Trade\Offer\Characteristic\Collection',
					'arguments' => [
						new ServiceReference('TradeOfferCharacteristicAttributeAccessor')
					]
				],

				'TradeOfferCharacteristicFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Characteristic\Facade',
					'arguments' => [
						new ServiceReference('TradeOfferCharacteristicMapper'),
						new ServiceReference('TradeOfferCharacteristicFactory'),
						new ServiceReference('TradeOfferCharacteristicCollection'),
						new ServiceReference('TradeOfferDataObjectFacade'),
						new ServiceReference('TradeOfferDataObjectTypeFacade'),
						new ServiceReference('fields')
					]
				],

				'FavoriteCurrencyUser' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Currency\Favorite\User',
					'arguments' => [
						new ServiceReference('Auth'),
						new ServiceReference('objects')
					]
				],

				'FavoriteCurrencyCustomer' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Currency\Favorite\Customer',
					'arguments' => [
						new ServiceReference('CookieJar')
					]
				],

				'FavoriteCurrencyFacade' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Currency\Favorite\Facade',
					'arguments' => [
						new ServiceReference('FavoriteCurrencyUser'),
						new ServiceReference('FavoriteCurrencyCustomer')
					]
				],

				'UmiDumpEntityBaseImporterFactory' => [
					'class' => '\UmiCms\System\Import\UmiDump\Entity\BaseImporter\Factory',
				],

				'TradeOfferSchema' => [
					'class' => '\UmiCms\System\Trade\Offer\Schema',
					'arguments' => [
						new ServiceReference('TradeOfferRelationAccessor')
					]
				],

				'TradeOfferPriceSchema' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Schema',
					'arguments' => [
						new ServiceReference('TradeOfferPriceRelationAccessor')
					]
				],

				'TradeOfferPriceTypeSchema' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Schema',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeRelationAccessor')
					]
				],

				'TradeStockBalanceSchema' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Schema',
					'arguments' => [
						new ServiceReference('TradeStockBalanceRelationAccessor')
					]
				],

				'TradeOfferBuilder' => [
					'class' => '\UmiCms\System\Trade\Offer\Builder',
					'arguments' => [
						new ServiceReference('TradeOfferMapper'),
						new ServiceContainerReference(),
						new ServiceReference('TradeOfferRelationMutator'),
						new ServiceReference('TradeOfferAttributeAccessor'),
						new ServiceReference('TradeOfferAttributeMutator')
					]
				],

				'TradeOfferPriceBuilder' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Builder',
					'arguments' => [
						new ServiceReference('TradeOfferPriceMapper'),
						new ServiceContainerReference(),
						new ServiceReference('TradeOfferPriceRelationMutator'),
						new ServiceReference('TradeOfferPriceAttributeAccessor'),
						new ServiceReference('TradeOfferPriceAttributeMutator')
					]
				],

				'TradeOfferPriceTypeBuilder' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Builder',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeMapper'),
						new ServiceContainerReference(),
						new ServiceReference('TradeOfferPriceTypeRelationMutator'),
						new ServiceReference('TradeOfferPriceTypeAttributeAccessor'),
						new ServiceReference('TradeOfferPriceTypeAttributeMutator')
					]
				],

				'TradeStockBalanceBuilder' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Builder',
					'arguments' => [
						new ServiceReference('TradeStockBalanceMapper'),
						new ServiceContainerReference(),
						new ServiceReference('TradeStockBalanceRelationMutator'),
						new ServiceReference('TradeStockBalanceAttributeAccessor'),
						new ServiceReference('TradeStockBalanceAttributeMutator'),
					]
				],

				'TradeOfferDemolisher' => [
					'class' => '\UmiCms\System\Trade\Offer\Demolisher',
					'arguments' => [
						new ServiceReference('TradeOfferFacade'),
						new ServiceReference('TradeOfferSchema')
					]
				],

				'TradeOfferExchange' => [
					'class' => '\UmiCms\System\Trade\Offer\Exchange',
					'arguments' => [
						new ServiceReference('TradeOfferImporter'),
						new ServiceReference('TradeOfferExporter'),
						new ServiceReference('TradeOfferFacade'),
						new ServiceReference('TradeOfferBuilder'),
						new ServiceReference('TradeOfferRelationAccessor'),
						new ServiceReference('TradeOfferDemolisher'),
					]
				],

				'TradeOfferPriceDemolisher' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Demolisher',
					'arguments' => [
						new ServiceReference('TradeOfferPriceFacade'),
						new ServiceReference('TradeOfferPriceSchema')
					]
				],

				'TradeOfferPriceExchange' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Exchange',
					'arguments' => [
						new ServiceReference('TradeOfferPriceImporter'),
						new ServiceReference('TradeOfferPriceExporter'),
						new ServiceReference('TradeOfferPriceFacade'),
						new ServiceReference('TradeOfferPriceBuilder'),
						new ServiceReference('TradeOfferPriceRelationAccessor'),
						new ServiceReference('TradeOfferPriceDemolisher')
					]
				],

				'TradeOfferPriceTypeDemolisher' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Demolisher',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeFacade'),
						new ServiceReference('TradeOfferPriceTypeSchema')
					]
				],

				'TradeOfferPriceTypeExchange' => [
					'class' => '\UmiCms\System\Trade\Offer\Price\Type\Exchange',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeImporter'),
						new ServiceReference('TradeOfferPriceTypeExporter'),
						new ServiceReference('TradeOfferPriceTypeFacade'),
						new ServiceReference('TradeOfferPriceTypeBuilder'),
						new ServiceReference('TradeOfferPriceTypeRelationAccessor'),
						new ServiceReference('TradeOfferPriceTypeDemolisher'),
					]
				],

				'TradeStockBalanceDemolisher' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Demolisher',
					'arguments' => [
						new ServiceReference('TradeStockBalanceFacade'),
						new ServiceReference('TradeStockBalanceSchema')
					]
				],

				'TradeStockBalanceExchange' => [
					'class' => '\UmiCms\System\Trade\Stock\Balance\Exchange',
					'arguments' => [
						new ServiceReference('TradeStockBalanceImporter'),
						new ServiceReference('TradeStockBalanceExporter'),
						new ServiceReference('TradeStockBalanceFacade'),
						new ServiceReference('TradeStockBalanceBuilder'),
						new ServiceReference('TradeStockBalanceRelationAccessor'),
						new ServiceReference('TradeStockBalanceDemolisher')
					]
				],

				'TradeOfferAttributeAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferMapper'),
					]
				],

				'TradeOfferAttributeMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferMapper'),
					]
				],

				'TradeOfferRelationAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Relation\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferMapper'),
					]
				],

				'TradeOfferRelationMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Relation\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferMapper'),
					]
				],

				'TradeOfferPriceAttributeAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferPriceMapper'),
					]
				],

				'TradeOfferPriceAttributeMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferPriceMapper'),
					]
				],

				'TradeOfferPriceRelationAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Relation\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferPriceMapper'),
					]
				],

				'TradeOfferPriceRelationMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Relation\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferPriceMapper'),
					]
				],

				'TradeOfferPriceTypeAttributeAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Type\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeMapper'),
					]
				],

				'TradeOfferPriceTypeAttributeMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Type\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeMapper'),
					]
				],

				'TradeOfferPriceTypeRelationAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Type\Relation\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeMapper'),
					]
				],

				'TradeOfferPriceTypeRelationMutator' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Type\Relation\Mutator',
					'arguments' => [
						new ServiceReference('TradeOfferPriceTypeMapper'),
					]
				],

				'TradeStockBalanceAttributeAccessor' => [
					'class' => 'UmiCms\System\Trade\Stock\Balance\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('TradeStockBalanceMapper'),
					]
				],

				'TradeStockBalanceAttributeMutator' => [
					'class' => 'UmiCms\System\Trade\Stock\Balance\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('TradeStockBalanceMapper'),
					]
				],

				'TradeStockBalanceRelationAccessor' => [
					'class' => 'UmiCms\System\Trade\Stock\Balance\Relation\Accessor',
					'arguments' => [
						new ServiceReference('TradeStockBalanceMapper'),
					]
				],

				'TradeStockBalanceRelationMutator' => [
					'class' => 'UmiCms\System\Trade\Stock\Balance\Relation\Mutator',
					'arguments' => [
						new ServiceReference('TradeStockBalanceMapper'),
					]
				],

				'TradeOfferCharacteristicAttributeAccessor' => [
					'class' => 'UmiCms\System\Trade\Offer\Characteristic\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('TradeOfferCharacteristicMapper'),
					]
				],

				'TradeOffer' => [
					'class' => 'UmiCms\System\Trade\Offer',
					'arguments' => [
						new ServiceReference('TradeOfferBuilder')
					]
				],

				'TradeOfferPrice' => [
					'class' => 'UmiCms\System\Trade\Offer\Price',
					'arguments' => [
						new ServiceReference('TradeOfferPriceBuilder')
					],
					'calls' => [
						[
							'method' => 'setCurrencyFacade',
							'arguments' => [
								new ServiceReference('Currencies')
							]
						]
					]
				],

				'TradeOfferPriceType' => [
					'class' => 'UmiCms\System\Trade\Offer\Price\Type'
				],

				'TradeStockBalance' => [
					'class' => 'UmiCms\System\Trade\Stock\Balance',
					'arguments' => [
						new ServiceReference('TradeStockBalanceBuilder')
					]
				],

				'EmojiTranslator' => [
					'class' => 'UmiCms\System\Utils\Emoji\Translator',
				],

				'DataSetConfigXmlTranslator' => [
					'class' => 'UmiCms\Classes\System\Utils\DataSetConfig\XmlTranslator',
					'arguments' => [
						new ServiceReference('objectTypes'),
						new ServiceReference('cmsController'),
					]
				],

				'HierarchyElementChildrenIdGetter' => [
					'class' => 'UmiCms\System\Hierarchy\Element\ChildrenId\Getter',
					'arguments' => [
						new ParameterReference('connection')
					]
				],

				'AdminSkin' => [
					'class' => UmiCms\System\Admin\Skin::class,
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('cmsController'),
						new ServiceReference('CookieJar'),
						new ServiceReference('RequestHttpGet'),
						new ServiceReference('RequestHttpPost'),
					]
				],

				'AdminTemplater' => [
					'class' => UmiCms\System\Templater\AdminTemplater::class,
					'arguments' => [
						new ServiceReference('AdminSkin'),
						new ServiceReference('Configuration'),
						new ServiceReference('permissionsCollection'),
						new ServiceReference('Auth'),
						new ServiceReference('cmsController'),
					]
				],

				'TemplateFactory' => [
					'class' => 'UmiCms\System\Hierarchy\Template\Factory',
					'calls' => [
						[
							'method' => 'setServiceContainer',
							'arguments' => [
								new ServiceContainerReference()
							]
						]
					]
				],

				'DummyTemplate' => [
					'class' => 'UmiCms\System\Hierarchy\Template\Dummy',
					'calls' => [
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						],
						[
							'method' => 'setLanguageDetector',
							'arguments' => [
								new ServiceReference('LanguageDetector')
							]
						]
					]
				],

				'ResponseErrorEntry' => [
					'class' => 'UmiCms\System\Response\Error\Entry',
					'arguments' => [
						new ServiceReference('DomainCollection')
					]
				],

				'ResponseErrorEntryBuilder' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Builder',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryMapper'),
						new ServiceContainerReference(),
						new ServiceReference('ResponseErrorEntryRelationMutator'),
						new ServiceReference('ResponseErrorEntryAttributeAccessor'),
						new ServiceReference('ResponseErrorEntryAttributeMutator')
					]
				],

				'ResponseErrorEntryCollection' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Collection',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryAttributeAccessor')
					]
				],

				'ResponseErrorEntryFacade' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Facade',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryCollection'),
						new ServiceReference('ResponseErrorEntryRepository'),
						new ServiceReference('ResponseErrorEntryFactory'),
						new ServiceReference('ResponseErrorEntryAttributeAccessor'),
						new ServiceReference('ResponseErrorEntryRelationAccessor'),
						new ServiceReference('ResponseErrorEntryBuilder')
					],
					'calls' => [
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						]
					]
				],

				'ResponseErrorEntryFactory' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Factory',
					'arguments' => [
						new ServiceReference('ResponseErrorEntry')
					]
				],

				'ResponseErrorEntryMapper' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Mapper',
				],

				'ResponseErrorEntryRepository' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('ResponseErrorEntrySchema'),
						new ServiceReference('ResponseErrorEntryAttributeAccessor'),
						new ServiceReference('ResponseErrorEntryFactory'),
						new ServiceReference('ResponseErrorEntryBuilder')
					],
				],

				'ResponseErrorEntrySchema' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Schema',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryRelationAccessor')
					]
				],

				'ResponseErrorEntryAttributeAccessor' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryMapper'),
					]
				],

				'ResponseErrorEntryAttributeMutator' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryMapper'),
					]
				],

				'ResponseErrorEntryRelationAccessor' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Relation\Accessor',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryMapper'),
					]
				],

				'ResponseErrorEntryRelationMutator' => [
					'class' => 'UmiCms\System\Response\Error\Entry\Relation\Mutator',
					'arguments' => [
						new ServiceReference('ResponseErrorEntryMapper'),
					]
				],

				'TestsRunnerCollector' => [
					'class' => 'UmiCms\Tests\Runner\Collector',
					'arguments' => [
						new ServiceReference('FileFactory'),
						new ServiceReference('DomainCollection'),
						new ServiceReference('DirectoryFactory'),
					]
				],

				'TestsRunnerExecutor' => [
					'class' => 'UmiCms\Tests\Runner\Executor'
				],

				'TestsRunnerParser' => [
					'class' => 'UmiCms\Tests\Runner\Parser'
				],

				'TestsRunnerAggregator' => [
					'class' => 'UmiCms\Tests\Runner\Aggregator'
				],

				'TestsRunnerVisualizer' => [
					'class' => 'UmiCms\Tests\Runner\Visualizer',
					'arguments' => [
						new ServiceReference('Request'),
					]
				],

				'TestsRunner' => [
					'class' => 'UmiCms\Tests\Runner',
					'arguments' => [
						new ServiceReference('TestsRunnerCollector'),
						new ServiceReference('TestsRunnerExecutor'),
						new ServiceReference('TestsRunnerParser'),
						new ServiceReference('TestsRunnerAggregator'),
						new ServiceReference('TestsRunnerVisualizer'),
					]
				],

				'Router' => [
					'class' => 'UmiCms\Classes\System\Routing\Router',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('EventPointFactory')
					]
				],

				'ControllerFactory' => [
					'class' => 'UmiCms\Classes\System\Controllers\Factory',
					'arguments' => [
						new ServiceContainerReference(),
					]
				],

				'TinyUrlController' => [
					'class' => 'UmiCms\Classes\System\Controllers\TinyUrlController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setPageFacade',
							'arguments' => [
								new ServiceReference('pages')
							]
						]
					]
				],

				'RobotsGenerator' => [
					'class' => 'UmiCms\Classes\System\Utils\Robots\Generator',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('Registry'),
						new ServiceReference('EventPointFactory'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('SelectorFactory'),
						new ServiceReference('LanguageDetector'),
					],
				],

				'RobotsController' => [
					'class' => 'UmiCms\Classes\System\Controllers\RobotsController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setGenerator',
							'arguments' => [
								new ServiceReference('RobotsGenerator')
							]
						]
					]
				],

				'FaviconController' => [
					'class' => 'UmiCms\Classes\System\Controllers\FaviconController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setEventFactory',
							'arguments' => [
								new ServiceReference('EventPointFactory')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						]
					]
				],

				'SiteMapGenerator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Generator',
					'arguments' => [
						new ServiceReference('SiteMapLocationFacade'),
						new ServiceReference('DOMDocumentFactory')
					],
				],

				'SiteMapController' => [
					'class' => 'UmiCms\Classes\System\Controllers\SiteMapController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setGenerator',
							'arguments' => [
								new ServiceReference('SiteMapGenerator')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						]
					]
				],

				'AutoThumbGenerator' => [
					'class' => 'UmiCms\Utils\AutoThumb\Generator',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('ImageFactory'),
						new ParameterReference('ImageProcessor'),
					],
				],

				'AutoThumbController' => [
					'class' => 'UmiCms\Classes\System\Controllers\AutoThumbController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setGenerator',
							'arguments' => [
								new ServiceReference('AutoThumbGenerator')
							]
						]
					]
				],

				'CaptchaFacade' => [
					'class' => 'umiCaptcha'
				],

				'CaptchaController' => [
					'class' => 'UmiCms\Classes\System\Controllers\CaptchaController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setSession',
							'arguments' => [
								new ServiceReference('Session')
							]
						],
						[
							'method' => 'setGenerator',
							'arguments' => [
								new ServiceReference('CaptchaFacade')
							]
						]
					]
				],

				'DispatchesCounter' => [
					'class' => 'UmiCms\Utils\Dispatches\Counter',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('Configuration'),
					],
				],

				'DispatchesCounterController' => [
					'class' => 'UmiCms\Classes\System\Controllers\DispatchesCounterController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setCounter',
							'arguments' => [
								new ServiceReference('DispatchesCounter')
							]
						]
					]
				],

				'CronExecutor' => [
					'class' => 'umiCron',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('EventPointFactory')
					],
				],

				'CronController' => [
					'class' => 'UmiCms\Classes\System\Controllers\CronController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						],
						[
							'method' => 'setExecutor',
							'arguments' => [
								new ServiceReference('CronExecutor')
							]
						],
						[
							'method' => 'setPermissions',
							'arguments' => [
								new ServiceReference('permissionsCollection')
							]
						]
					]
				],

				'GoOutController' => [
					'class' => 'UmiCms\Classes\System\Controllers\GoOutController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setBrowserDetector',
							'arguments' => [
								new ServiceReference('BrowserDetector')
							]
						],
					]
				],

				'TemplateEngineFactory' => [
					'class' => 'UmiCms\Classes\System\Template\Engine\Factory',
				],

				'LicenseCheckController' => [
					'class' => 'UmiCms\Classes\System\Controllers\LicenseCheckController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setRegistry',
							'arguments' => [
								new ServiceReference('Registry')
							]
						],
						[
							'method' => 'setDomainFacade',
							'arguments' => [
								new ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setPhpTemplateEngine',
							'arguments' => [
								new ServiceReference('TemplateEngineFactory')
							]
						]
					]
				],

				'RemoteFileLoader' => [
					'class' => 'umiRemoteFileGetter'
				],

				'DomainKeyCodeSaver' => [
					'class' => 'UmiCms\Utils\DomainKeyCode\Saver',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('Request'),
						new ServiceReference('Configuration'),
						new ServiceReference('DomainCollection'),
						new ServiceReference('cmsController'),
						new ServiceReference('TemplateEngineFactory'),
						new ServiceReference('RemoteFileLoader'),
					],
				],

				'SaveDomainKeyCodeController' => [
					'class' => 'UmiCms\Classes\System\Controllers\SaveDomainKeyCodeController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setRegistry',
							'arguments' => [
								new ServiceReference('Registry')
							]
						],
						[
							'method' => 'setSaver',
							'arguments' => [
								new ServiceReference('DomainKeyCodeSaver')
							]
						],
						[
							'method' => 'setDomDocumentFactory',
							'arguments' => [
								new ServiceReference('DOMDocumentFactory')
							]
						]
					]
				],

				'SessionCheckController' => [
					'class' => 'UmiCms\Classes\System\Controllers\SessionCheckController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response'),
					],
					'calls' => [
						[
							'method' => 'setSession',
							'arguments' => [
								new ServiceReference('Session')
							]
						],
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						]
					]
				],

				'StaticBannerController' => [
					'class' => 'UmiCms\Classes\System\Controllers\StaticBannerController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setModuleLoader',
							'arguments' => [
								new ServiceReference('cmsController')
							]
						],
						[
							'method' => 'setPhpTemplateEngine',
							'arguments' => [
								new ServiceReference('TemplateEngineFactory')
							]
						]
					]
				],

				'TranslatorFactory' => [
					'class' => 'UmiCms\Classes\System\Translators\TranslatorFactory',
				],

				'DOMDocumentFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\DOM\Document\Factory',
					'arguments' => [
						new ServiceReference('Configuration')
					],
				],

				'StreamsPermissions' => [
					'class' => 'UmiCms\System\Streams\Permissions',
					'arguments' => [
						new ServiceReference('Auth'),
						new ServiceReference('Configuration'),
						new ServiceReference('objects'),
						new ServiceReference('Request'),
						new ServiceReference('permissionsCollection')
					],
				],

				'Streams' => [
					'class' => 'UmiCms\System\Streams\Facade',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('Request'),
						new ServiceReference('cmsController'),
						new ServiceReference('StreamsPermissions')
					],
				],

				'StreamsController' => [
					'class' => 'UmiCms\Classes\System\Controllers\StreamsController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response'),
					],
					'calls' => [
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						],
						[
							'method' => 'setChecker',
							'arguments' => [
								new ServiceReference('UmiManagerChecker')
							]
						],
						[
							'method' => 'setStreams',
							'arguments' => [
								new ServiceReference('Streams')
							]
						],
						[
							'method' => 'setTranslator',
							'arguments' => [
								new ServiceReference('Translators')
							]
						]
					]
				],

				'Translators' => [
					'class' => 'UmiCms\Classes\System\Translators\Facade',
					'arguments' => [
						new ServiceReference('TranslatorFactory'),
						new ServiceReference('DOMDocumentFactory'),
					],
				],

				'UmiManagerChecker' => [
					'class' => 'UmiCms\Classes\System\MobileApp\UmiManager\Checker',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Registry'),
						new ServiceReference('Response'),
						new ServiceReference('Translators')
					],
				],

				'UpdaterController' => [
					'class' => 'UmiCms\Classes\System\Controllers\UpdaterController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					]
				],

				'StubResolver' => [
					'class' => 'UmiCms\Classes\System\Utils\Stub\Resolver',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('SelectorFactory')
					]
				],

				'StubContent' => [
					'class' => 'UmiCms\Classes\System\Utils\Stub\Content',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('StubSettingsFactory'),
						new ServiceReference('DOMDocumentFactory')
					]
				],

				'Stub' => [
					'class' => 'UmiCms\Classes\System\Utils\Stub\Facade',
					'arguments' => [
						new ServiceReference('Registry'),
						new ServiceReference('StubContent'),
						new ServiceReference('StubResolver'),
						new ServiceReference('DomainDetector'),
						new ServiceReference('LanguageDetector'),
					]
				],

				'PropertiesHelper' => [
					'class' => 'umiPropertiesHelper'
				],

				'UmapFactory' => [
					'class' => 'UmiCms\System\Streams\Umap\Factory'
				],

				'UmapExecutor' => [
					'class' => 'UmiCms\System\Streams\Umap\Executor',
					'arguments' => [
						new ServiceReference('UmapFactory')
					]
				],

				'Umap' => [
					'class' => 'UmiCms\System\Streams\Umap\Facade',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UmapExecutor')
					]
				],

				'IndexController' => [
					'class' => 'UmiCms\Classes\System\Controllers\IndexController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response'),
					],
					'calls' => [
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						],
						[
							'method' => 'setChecker',
							'arguments' => [
								new ServiceReference('UmiManagerChecker')
							]
						],
						[
							'method' => 'setPages',
							'arguments' => [
								new ServiceReference('pages')
							]
						],
						[
							'method' => 'setConfig',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						],
						[
							'method' => 'setRegistry',
							'arguments' => [
								new ServiceReference('Registry')
							]
						],
						[
							'method' => 'setSession',
							'arguments' => [
								new ServiceReference('Session')
							]
						],
						[
							'method' => 'setDomains',
							'arguments' => [
								new ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setStubFacade',
							'arguments' => [
								new ServiceReference('Stub')
							]
						],
						[
							'method' => 'setUmapFacade',
							'arguments' => [
								new ServiceReference('Umap')
							]
						],
						[
							'method' => 'setModuleRouter',
							'arguments' => [
								new ServiceReference('cmsController')
							]
						],
						[
							'method' => 'setStaticCache',
							'arguments' => [
								new ServiceReference('StaticCache')
							]
						],
						[
							'method' => 'setPropertyHelper',
							'arguments' => [
								new ServiceReference('PropertiesHelper')
							]
						],
						[
							'method' => 'setEventPointFactory',
							'arguments' => [
								new ServiceReference('EventPointFactory')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						],
						[
							'method' => 'setLanguageDetector',
							'arguments' => [
								new ServiceReference('LanguageDetector')
							]
						]
					]
				],

				'XmlForceController' => [
					'class' => 'UmiCms\Classes\System\Controllers\XmlForceController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response'),
					],
					'calls' => [
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						],
						[
							'method' => 'setChecker',
							'arguments' => [
								new ServiceReference('UmiManagerChecker')
							]
						],
						[
							'method' => 'setConfig',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						],
						[
							'method' => 'setTranslator',
							'arguments' => [
								new ServiceReference('Translators')
							]
						],
						[
							'method' => 'setStubFacade',
							'arguments' => [
								new ServiceReference('Stub')
							]
						],
						[
							'method' => 'setUmapFacade',
							'arguments' => [
								new ServiceReference('Umap')
							]
						],
						[
							'method' => 'setModuleRouter',
							'arguments' => [
								new ServiceReference('cmsController')
							]
						],
						[
							'method' => 'setEventPointFactory',
							'arguments' => [
								new ServiceReference('EventPointFactory')
							]
						],
						[
							'method' => 'setDomains',
							'arguments' => [
								new ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						],
					]
				],

				'JsonForceController' => [
					'class' => 'UmiCms\Classes\System\Controllers\JsonForceController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response'),
					],
					'calls' => [
						[
							'method' => 'setAuth',
							'arguments' => [
								new ServiceReference('Auth')
							]
						],
						[
							'method' => 'setChecker',
							'arguments' => [
								new ServiceReference('UmiManagerChecker')
							]
						],
						[
							'method' => 'setConfig',
							'arguments' => [
								new ServiceReference('Configuration')
							]
						],
						[
							'method' => 'setTranslator',
							'arguments' => [
								new ServiceReference('Translators')
							]
						],
						[
							'method' => 'setStubFacade',
							'arguments' => [
								new ServiceReference('Stub')
							]
						],
						[
							'method' => 'setUmapFacade',
							'arguments' => [
								new ServiceReference('Umap')
							]
						],
						[
							'method' => 'setModuleRouter',
							'arguments' => [
								new ServiceReference('cmsController')
							]
						],
						[
							'method' => 'setEventPointFactory',
							'arguments' => [
								new ServiceReference('EventPointFactory')
							]
						],
						[
							'method' => 'setDomains',
							'arguments' => [
								new ServiceReference('DomainCollection')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						],
					]
				],

				'PageNumAgentCommon' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Common',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UrlFactory'),
					]
				],

				'PageNumAgentSite' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Site',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UrlFactory'),
					]
				],

				'PageNumAgentStream' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Stream',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UrlFactory'),
					]
				],

				'PageNumAgentAdmin' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Admin',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('UrlFactory'),
					],
					'calls' => [
						[
							'method' => 'setSessionContainer',
							'arguments' => [
								new ServiceReference('Session')
							]
						],
					]
				],

				'PageNumAgentFactory' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Factory',
					'arguments' => [
						new ServiceContainerReference()
					],
				],

				'PageNumAgentFacade' => [
					'class' => 'UmiCms\Classes\System\PageNum\Agent\Facade',
					'arguments' => [
						new ServiceReference('Configuration'),
						new ServiceReference('PageNumAgentFactory')
					],
				],

				'SiteMapLocation' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location',
					'arguments' => [
						new ServiceReference('SiteMapLocationBuilder')
					]
				],

				'SiteMapLocationCollection' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Collection',
					'arguments' => [
						new ServiceReference('SiteMapLocationAttributeAccessor')
					]
				],

				'SiteMapLocationFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Factory',
					'arguments' => [
						new ServiceReference('SiteMapLocation')
					]
				],

				'SiteMapLocationRepository' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('SiteMapLocationSchema'),
						new ServiceReference('SiteMapLocationAttributeAccessor'),
						new ServiceReference('SiteMapLocationFactory'),
						new ServiceReference('SiteMapLocationBuilder')
					],
				],

				'SiteMapLocationFacade' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Facade',
					'arguments' => [
						new ServiceReference('SiteMapLocationCollection'),
						new ServiceReference('SiteMapLocationRepository'),
						new ServiceReference('SiteMapLocationFactory'),
						new ServiceReference('SiteMapLocationAttributeAccessor'),
						new ServiceReference('SiteMapLocationRelationAccessor'),
						new ServiceReference('SiteMapLocationBuilder'),
					],
					'calls' => [
						[
							'method' => 'setImageFacade',
							'arguments' => [
								new ServiceReference('SiteMapImageFacade')
							]
						]
					]
				],

				'SiteMapLocationMapper' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Mapper'
				],

				'SiteMapLocationBuilder' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Builder',
					'arguments' => [
						new ServiceReference('SiteMapLocationMapper'),
						new ServiceContainerReference(),
						new ServiceReference('SiteMapLocationRelationMutator'),
						new ServiceReference('SiteMapLocationAttributeAccessor'),
						new ServiceReference('SiteMapLocationAttributeMutator'),
					]
				],

				'SiteMapLocationAttributeAccessor' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('SiteMapLocationMapper'),
					]
				],

				'SiteMapLocationAttributeMutator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('SiteMapLocationMapper'),
					]
				],

				'SiteMapLocationRelationAccessor' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Relation\Accessor',
					'arguments' => [
						new ServiceReference('SiteMapLocationMapper'),
					]
				],

				'SiteMapLocationRelationMutator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Relation\Mutator',
					'arguments' => [
						new ServiceReference('SiteMapLocationMapper'),
					]
				],

				'SiteMapLocationSchema' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Location\Schema',
					'arguments' => [
						new ServiceReference('SiteMapLocationRelationAccessor')
					]
				],

				'SiteMapImage' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image'
				],

				'SiteMapImageCollection' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Collection',
					'arguments' => [
						new ServiceReference('SiteMapImageAttributeAccessor')
					]
				],

				'SiteMapImageFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Factory',
					'arguments' => [
						new ServiceReference('SiteMapImage')
					]
				],

				'SiteMapImageRepository' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Repository',
					'arguments' => [
						new ParameterReference('connection'),
						new ServiceReference('OrmEntityRepositoryHistory'),
						new ServiceReference('SiteMapImageSchema'),
						new ServiceReference('SiteMapImageAttributeAccessor'),
						new ServiceReference('SiteMapImageFactory'),
						new ServiceReference('SiteMapImageBuilder')
					],
				],

				'SiteMapImageFacade' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Facade',
					'arguments' => [
						new ServiceReference('SiteMapImageCollection'),
						new ServiceReference('SiteMapImageRepository'),
						new ServiceReference('SiteMapImageFactory'),
						new ServiceReference('SiteMapImageAttributeAccessor'),
						new ServiceReference('SiteMapImageRelationAccessor'),
						new ServiceReference('SiteMapImageBuilder'),
					],
					'calls' => [
						[
							'method' => 'setImageExtractor',
							'arguments' => [
								new ServiceReference('SiteMapImageExtractor')
							]
						]
					]
				],

				'SiteMapImageMapper' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Mapper'
				],

				'SiteMapImageBuilder' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Builder',
					'arguments' => [
						new ServiceReference('SiteMapImageMapper'),
						new ServiceContainerReference(),
						new ServiceReference('SiteMapImageRelationMutator'),
						new ServiceReference('SiteMapImageAttributeAccessor'),
						new ServiceReference('SiteMapImageAttributeMutator'),
					]
				],

				'SiteMapImageAttributeAccessor' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Attribute\Accessor',
					'arguments' => [
						new ServiceReference('SiteMapImageMapper'),
					]
				],

				'SiteMapImageAttributeMutator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Attribute\Mutator',
					'arguments' => [
						new ServiceReference('SiteMapImageMapper'),
					]
				],

				'SiteMapImageRelationAccessor' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Relation\Accessor',
					'arguments' => [
						new ServiceReference('SiteMapImageMapper'),
					]
				],

				'SiteMapImageRelationMutator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Relation\Mutator',
					'arguments' => [
						new ServiceReference('SiteMapImageMapper'),
					]
				],

				'SiteMapImageSchema' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Schema',
					'arguments' => [
						new ServiceReference('SiteMapImageRelationAccessor')
					]
				],

				'LiteHttpClientFactory' => [
					'class' => 'UmiCms\Classes\System\Utils\Api\Http\Lite\Client\Factory',
				],

				'SiteMapImageExtractor' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Extractor',
					'arguments' => [
						new ServiceReference('UrlFactory'),
						new ServiceReference('ImageFactory'),
						new ServiceReference(iParser::SERVICE_NAME),
						new ServiceReference('LiteHttpClientFactory'),
						new ServiceReference('Configuration')
					]
				],

				'SiteMapImageGenerator' => [
					'class' => 'UmiCms\Classes\System\Utils\SiteMap\Image\Generator',
					'arguments' => [
						new ServiceReference('SiteMapLocationFacade'),
						new ServiceReference('DOMDocumentFactory')
					],
				],

				'SiteMapImagesController' => [
					'class' => 'UmiCms\Classes\System\Controllers\SiteMapImagesController',
					'arguments' => [
						new ServiceReference('Request'),
						new ServiceReference('Response')
					],
					'calls' => [
						[
							'method' => 'setGenerator',
							'arguments' => [
								new ServiceReference('SiteMapImageGenerator')
							]
						],
						[
							'method' => 'setDomainDetector',
							'arguments' => [
								new ServiceReference('DomainDetector')
							]
						]
					]
				],

				iOAuth::SERVICE_NAME => [
					'class' => OAuth::class,
					'arguments' => [
						new ServiceReference('LoggerFactory'),
						new ServiceReference('Configuration'),
						new ServiceReference(iFactory::SERVICE_NAME),
					]
				],

				iFactory::SERVICE_NAME => [
					'class' => Factory::class,
					'arguments' => [
						new ServiceReference(iBunch::SERVICE_NAME)
					]
				],

				iBunch::SERVICE_NAME => [
					'class' => Bunch::class,
					'arguments' => [
						new ServiceReference('Encrypter'),
						new ServiceReference('Configuration'),
						new ServiceReference('FileFactory'),
					]
				],

				iParser::SERVICE_NAME => [
					'class' => Parser::class,
					'arguments' => [
						new ServiceReference('DOMDocumentFactory')
					]
				],

				iEventController::SERVICE_NAME => [
					'class' => EventController::class,
				],

				iEventHandlerFactory::SERVICE_NAME => [
					'class' => EventHandlerFactory::class,
					'arguments' => [
						new ServiceReference(iEventController::SERVICE_NAME)
					]
				],

				iEventHandlerExecutorFactory::SERVICE_NAME => [
					'class' => EventHandlerExecutorFactory::class,
					'arguments' => [
						new ServiceContainerReference()
					]
				],

				iModuleEventHandlerExecutor::SERVICE_NAME => [
					'class' => ModuleEventHandlerExecutor::class,
					'calls' => [
						[
							'method' => 'setModuleLoader',
							'arguments' => [
								new ServiceReference('cmsController')
							]
						]
					]
				],

				iSelectorOrderFactory::SERVICE_NAME => [
					'class' => SelectorOrderFactory::class,
					'arguments' => [
						new ServiceContainerReference()
					]
				],

				iSelectorOrderPropertyGlobalOrd::SERVICE_NAME => [
					'class' => SelectorOrderPropertyGlobalOrd::class,
					'calls' => [
						[
							'method' => 'setConnection',
							'arguments' => [
								new ParameterReference('connection')
							]
						]
					]
				]
			];
		}
	}
