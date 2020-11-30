<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\Utils\Stub\Settings\iFactory as iSettingFactory;
	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDomDocumentFactory;

	/**
	 * Интерфейс контента заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	interface iContent {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iSettingFactory $settingsFactory фабрика настроек
		 * @param iDomDocumentFactory $domDocumentFactory фабрика xml документов
		 */
		public function __construct(iConfig $config, iSettingFactory $settingsFactory, iDomDocumentFactory $domDocumentFactory);

		/**
		 * Возвращает пользовательское содержимое заглушки
		 * @return bool|false|string
		 * @throws \coreException
		 */
		public function getCustom();

		/**
		 * Возвращает содержимое заглушки по умолчанию
		 * @return bool|false|string
		 * @throws \coreException
		 */
		public function getDefault();
	}