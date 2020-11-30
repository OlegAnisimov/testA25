<?php

	namespace UmiCms;

	use UmiCms\Classes\System\Utils\Html\iParser;
	use \iUmiEventsController as iEventController;
	use UmiCms\Classes\System\Utils\Api\Http\Json\Google\Client\iOAuth;
	use UmiCms\System\Events\Handler\iFactory as iEventHandlerFactory;
	use UmiCms\System\Events\Executor\iFactory as iEventHandlerExecutorFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Lite\Client\iFactory as iLiteHttpClientFactory;

	/**
	 * Класс фасада для получения сервисов
	 * @package UmiCms\Service
	 */
	class Service implements iService {

		/** @inheritDoc */
		public static function get($serviceName) {
			return self::getServiceContainer()->get($serviceName);
		}

		/** @inheritDoc */
		public static function getNew($serviceName) {
			return self::getServiceContainer()->getNew($serviceName);
		}

		/** @inheritDoc */
		public static function Redirects() {
			return self::getServiceContainer()->get('Redirects');
		}

		/** @inheritDoc */
		public static function UrlFactory() {
			return self::getServiceContainer()->get('UrlFactory');
		}

		/** @inheritDoc */
		public static function MailVariables() {
			return self::getServiceContainer()->get('MailVariables');
		}

		/** @inheritDoc */
		public static function MailTemplates() {
			return self::getServiceContainer()->get('MailTemplates');
		}

		/** @inheritDoc */
		public static function MailNotifications() {
			return self::getServiceContainer()->get('MailNotifications');
		}

		/** @inheritDoc */
		public static function Auth() {
			return self::getServiceContainer()->get('Auth');
		}

		/** @inheritDoc */
		public static function CsrfProtection() {
			return self::getServiceContainer()->get('CsrfProtection');
		}

		/** @inheritDoc */
		public static function SystemUsersPermissions() {
			return self::getServiceContainer()->get('SystemUsersPermissions');
		}

		/** @inheritDoc */
		public static function PasswordHashAlgorithm() {
			return self::getServiceContainer()->get('PasswordHashAlgorithm');
		}

		/** @inheritDoc */
		public static function Request() {
			return self::getServiceContainer()->get('Request');
		}

		/** @inheritDoc */
		public static function CookieJar() {
			return self::getServiceContainer()->get('CookieJar');
		}

		/** @inheritDoc */
		public static function Session() {
			return self::getServiceContainer()->get('Session');
		}

		/** @inheritDoc */
		public static function ActionFactory() {
			return self::getServiceContainer()->get('ActionFactory');
		}

		/** @inheritDoc */
		public static function ManifestFactory() {
			return self::getServiceContainer()->get('ManifestFactory');
		}

		/** @inheritDoc */
		public static function TransactionFactory() {
			return self::getServiceContainer()->get('TransactionFactory');
		}

		/** @inheritDoc */
		public static function EventPointFactory() {
			return self::getServiceContainer()->get('EventPointFactory');
		}

		/** @inheritDoc */
		public static function SiteMapUpdater() {
			return self::getServiceContainer()->get('SiteMapUpdater');
		}

		/** @inheritDoc */
		public static function ObjectsCollection() {
			return self::getServiceContainer()->get('objects');
		}

		/** @inheritDoc */
		public static function ObjectsTypesCollection() {
			return self::getServiceContainer()->get('objectTypes');
		}

		/** @inheritDoc */
		public static function ConnectionPool() {
			return self::getServiceContainer()->get('connectionPool');
		}

		/** @inheritDoc */
		public static function TypesHelper() {
			return self::getServiceContainer()->get('typesHelper');
		}

		/** @inheritDoc */
		public static function HierarchyTypesCollection() {
			return self::getServiceContainer()->get('hierarchyTypes');
		}

		/** @inheritDoc */
		public static function ObjectPropertyFactory() {
			return self::getServiceContainer()->get('objectPropertyFactory');
		}

		/** @inheritDoc */
		public static function CacheKeyGenerator() {
			return self::getServiceContainer()->get('CacheKeyGenerator');
		}

		/** @inheritDoc */
		public static function CacheEngineFactory() {
			return self::getServiceContainer()->get('CacheEngineFactory');
		}

		/** @inheritDoc */
		public static function CountryFactory() {
			return self::getServiceContainer()->get('CountriesFactory');
		}

		/** @inheritDoc */
		public static function CityFactory() {
			return self::getServiceContainer()->get('CitiesFactory');
		}

		/** @inheritDoc */
		public static function RestrictionCollection() {
			return self::getServiceContainer()->get('RestrictionCollection');
		}

		/** @inheritDoc */
		public static function DirectoryFactory() {
			return self::getServiceContainer()->get('DirectoryFactory');
		}

		/** @inheritDoc */
		public static function FileFactory() {
			return self::getServiceContainer()->get('FileFactory');
		}

		/** @inheritDoc */
		public static function ImageFactory() {
			return self::getServiceContainer()->get('ImageFactory');
		}

		/** @inheritDoc */
		public static function FileUploader() {
			return self::getServiceContainer()->get('FileUploader');
		}

		/** @inheritDoc */
		public static function ImageUploader() {
			return self::getServiceContainer()->get('ImageUploader');
		}

		/** @inheritDoc */
		public static function ImportEntitySourceIdBinderFactory() {
			return self::getServiceContainer()->get('ImportEntitySourceIdBinderFactory');
		}

		/** @inheritDoc */
		public static function UmiDumpDemolisherExecutor() {
			return self::getServiceContainer()->get('UmiDumpDemolisherExecutor');
		}

		/** @inheritDoc */
		public static function ExtensionRegistry() {
			return self::getServiceContainer()->get('ExtensionRegistry');
		}

		/** @inheritDoc */
		public static function ExtensionLoader() {
			return self::getServiceContainer()->get('ExtensionLoader');
		}

		/** @inheritDoc */
		public static function ModulePermissionLoader() {
			return self::getServiceContainer()->get('ModulePermissionLoader');
		}

		/** @inheritDoc */
		public static function CacheFrontend() {
			return self::getServiceContainer()->get('CacheFrontend');
		}

		/** @inheritDoc */
		public static function CacheKeyValidatorFactory() {
			return self::getServiceContainer()->get('CacheKeyValidatorFactory');
		}

		/** @inheritDoc */
		public static function BrowserDetector() {
			return self::getServiceContainer()->get('BrowserDetector');
		}

		/** @inheritDoc */
		public static function StaticCache() {
			return self::getServiceContainer()->get('StaticCache');
		}

		/** @inheritDoc */
		public static function Response() {
			return self::getServiceContainer()->get('Response');
		}

		/** @inheritDoc */
		public static function Configuration() {
			return self::getServiceContainer()->get('Configuration');
		}

		/** @inheritDoc */
		public static function BrowserCache() {
			return self::getServiceContainer()->get('BrowserCache');
		}

		/** @inheritDoc */
		public static function QuickExchange() {
			return self::getServiceContainer()->get('QuickExchange');
		}

		/** @inheritDoc */
		public static function SelectorFactory() {
			return self::getServiceContainer()->get('SelectorFactory');
		}

		/** @inheritDoc */
		public static function DataObjectFactory() {
			return self::getServiceContainer()->get('DataObjectFactory');
		}

		/** @inheritDoc */
		public static function HierarchyElementFactory() {
			return self::getServiceContainer()->get('HierarchyElementFactory');
		}

		/** @inheritDoc */
		public static function Registry() {
			return self::getServiceContainer()->get('Registry');
		}

		/** @inheritDoc */
		public static function RegistrySettings() {
			return self::getServiceContainer()->get('RegistrySettings');
		}

		/** @inheritDoc */
		public static function DateFactory() {
			return self::getServiceContainer()->get('DateFactory');
		}

		/** @inheritDoc */
		public static function IdnConverter() {
			return self::getServiceContainer()->get('IdnConverter');
		}

		/** @inheritDoc */
		public static function DomainCollection() {
			return self::getServiceContainer()->get('DomainCollection');
		}

		/** @inheritDoc */
		public static function DomainDetector() {
			return self::getServiceContainer()->get('DomainDetector');
		}

		/** @inheritDoc */
		public static function CaptchaSettingsFactory() {
			return self::getServiceContainer()->get('CaptchaSettingsFactory');
		}

		/** @inheritDoc */
		public static function WatermarkSettingsFactory() {
			return self::getServiceContainer()->get('WatermarkSettingsFactory');
		}

		/** @inheritDoc */
		public static function StubSettingsFactory() {
			return self::getServiceContainer()->get('StubSettingsFactory');
		}

		/** @inheritDoc */
		public static function SeoSettingsFactory() {
			return self::getServiceContainer()->get('SeoSettingsFactory');
		}

		/** @inheritDoc */
		public static function MailSettingsFactory() {
			return self::getServiceContainer()->get('MailSettingsFactory');
		}

		/** @inheritDoc */
		public static function LanguageCollection() {
			return self::getServiceContainer()->get('LanguageCollection');
		}

		/** @inheritDoc */
		public static function LanguageDetector() {
			return self::getServiceContainer()->get('LanguageDetector');
		}

		/** @inheritDoc */
		public static function YandexOAuthClient() {
			return self::getServiceContainer()->get('YandexOAuthClient');
		}

		/** @inheritDoc */
		public static function ObjectPropertyValueTableSchema() {
			return self::getServiceContainer()->get('ObjectPropertyValueTableSchema');
		}

		/** @inheritDoc */
		public static function SolutionRegistry() {
			return self::getServiceContainer()->get('SolutionRegistry');
		}

		/** @inheritDoc */
		public static function UmiDumpSolutionPostfixBuilder() {
			return self::getServiceContainer()->get('UmiDumpSolutionPostfixBuilder');
		}

		/** @inheritDoc */
		public static function UmiDumpFilePathConverter() {
			return self::getServiceContainer()->get('UmiDumpFilePathConverter');
		}

		/** @inheritDoc */
		public static function UmiDumpSolutionPostfixFilter() {
			return self::getServiceContainer()->get('UmiDumpSolutionPostfixFilter');
		}

		/** @inheritDoc */
		public static function Protection() {
			return self::getServiceContainer()->get('Protection');
		}

		/** @inheritDoc */
		public static function SystemInfo() {
			return self::getServiceContainer()->get('SystemInfo');
		}

		/** @inheritDoc */
		public static function TradeOfferFacade() {
			return self::getServiceContainer()->get('TradeOfferFacade');
		}

		/** @inheritDoc */
		public static function TradeOfferPriceFacade() {
			return self::getServiceContainer()->get('TradeOfferPriceFacade');
		}

		/** @inheritDoc */
		public static function TradeOfferPriceTypeFacade() {
			return self::getServiceContainer()->get('TradeOfferPriceTypeFacade');
		}

		/** @inheritDoc */
		public static function TradeStockFacade() {
			return self::getServiceContainer()->get('TradeStockFacade');
		}

		/** @inheritDoc */
		public static function TradeStockBalanceFacade() {
			return self::getServiceContainer()->get('TradeStockBalanceFacade');
		}

		/** @inheritDoc */
		public static function Hierarchy() {
			return self::getServiceContainer()->get('pages');
		}

		/** @inheritDoc */
		public static function FieldsFacade() {
			return self::getServiceContainer()->get('fields');
		}

		/** @inheritDoc */
		public static function FieldFacade() {
			return self::FieldsFacade();
		}

		/** @inheritDoc */
		public static function FieldTypeFacade() {
			return self::getServiceContainer()->get('FieldTypeFacade');
		}

		/** @inheritDoc */
		public static function TradeOfferCharacteristicFacade() {
			return self::getServiceContainer()->get('TradeOfferCharacteristicFacade');
		}

		/** @inheritDoc */
		public static function CurrencyFacade() {
			return self::getServiceContainer()->get('Currencies');
		}

		/** @inheritDoc */
		public static function UmiDumpEntityBaseImporterFactory() {
			return self::getServiceContainer()->get('UmiDumpEntityBaseImporterFactory');
		}

		/** @inheritDoc */
		public static function EmojiTranslator() {
			return self::getServiceContainer()->get('EmojiTranslator');
		}

		/** @inheritDoc */
		public static function PageChildrenIdGetter() {
			return self::getServiceContainer()->get('HierarchyElementChildrenIdGetter');
		}

		/** @inheritDoc */
		public static function AdminTemplater() {
			return self::getServiceContainer()->get('AdminTemplater');
		}

		/** @inheritDoc */
		public static function AdminSkin() {
			return self::getServiceContainer()->get('AdminSkin');
		}

		/** @inheritDoc */
		public static function ResponseErrorEntryFacade() {
			return self::getServiceContainer()->get('ResponseErrorEntryFacade');
		}

		/** @inheritDoc */
		public static function Router() {
			return self::getServiceContainer()->get('Router');
		}

		/** @inheritDoc */
		public static function ControllerFactory() {
			return self::getServiceContainer()->get('ControllerFactory');
		}

		/** @inheritDoc */
		public static function PageNumAgentFacade() {
			return self::getServiceContainer()->get('PageNumAgentFacade');
		}

		/** @inheritDoc */
		public static function TemplateEngineFactory() {
			return self::getServiceContainer()->get('TemplateEngineFactory');
		}

		/** @inheritDoc */
		public static function GoogleOAuth() : iOAuth {
			return self::getServiceContainer()->get(iOAuth::SERVICE_NAME);
		}

		/** @inheritDoc */
		public static function HtmlParser() : iParser {
			return self::getServiceContainer()->get(iParser::SERVICE_NAME);
		}

		/** @inheritDoc */
		public static function EventHandlerFactory() : iEventHandlerFactory {
			return self::getServiceContainer()->get(iEventHandlerFactory::SERVICE_NAME);
		}

		/** @inheritDoc */
		public static function EventController() : iEventController {
			return self::getServiceContainer()->get(iEventController::SERVICE_NAME);
		}

		/** @inheritDoc */
		public static function EventHandlerExecutorFactory() : iEventHandlerExecutorFactory {
			return self::getServiceContainer()->get(iEventHandlerExecutorFactory::SERVICE_NAME);
		}

		/** @inheritDoc */
		public static function LiteHttpClientFactory() : iLiteHttpClientFactory {
			return self::getServiceContainer()->get('LiteHttpClientFactory');
		}

		/**
		 * Возвращает контейнер сервисов
		 * @return \iServiceContainer
		 */
		private static function getServiceContainer() {
			return \ServiceContainerFactory::create();
		}
	}
