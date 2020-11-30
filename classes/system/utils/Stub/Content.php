<?php
	namespace UmiCms\Classes\System\Utils\Stub;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\Utils\Stub\Settings\iSettings;
	use UmiCms\Classes\System\Utils\Stub\Settings\iFactory as iSettingFactory;
	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDomDocumentFactory;

	/**
	 * Класс контента заглушки
	 * @package UmiCms\Classes\System\Utils\Stub
	 */
	class Content implements iContent {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iSettingFactory $settingsFactory фабрика настроек */
		private $settingsFactory;

		/** @var iDomDocumentFactory $domDocumentFactory фабрика xml документов */
		private $domDocumentFactory;

		/** @inheritDoc */
		public function __construct(iConfig $config, iSettingFactory $settingsFactory, iDomDocumentFactory $domDocumentFactory) {
			$this->config = $config;
			$this->settingsFactory = $settingsFactory;
			$this->domDocumentFactory = $domDocumentFactory;
		}

		/** @inheritDoc */
		public function getCustom() {
			/** @var iSettings $settings */
			$settings = $this->settingsFactory->createCustom();
			return $this->get($settings);
		}

		/** @inheritDoc */
		public function getDefault() {
			/** @var iSettings $settings */
			$settings = $this->settingsFactory->createCommon();
			return $this->get($settings);
		}

		/**
		 * Возвращает содержимое заглушки
		 * @param iSettings $settings настройки заглушки
		 * @return bool|false|string
		 * @throws \coreException
		 */
		private function get(iSettings $settings) {
			$filePath = $settings->getStubFilePath();
			$content = is_file($filePath) ? file_get_contents($filePath) : false;

			if ($content) {
				$isShowModalWindow = (bool) $this->config->get('stub', 'show-modal-window');
				$content = $isShowModalWindow ? $this->addAlertForm($content) : $content;
			} else {
				$content = $this->getFailBack();
			}

			return $content;
		}

		/**
		 * Возвращает резервное содержимое заглушки
		 * @return false|string
		 * @throws \coreException
		 */
		private function getFailBack() {
			$stubFilePath = $this->config->includeParam('system.stub');

			if (!is_file($stubFilePath)) {
				throw new \coreException("Stub file $stubFilePath not found");
			}

			return file_get_contents($stubFilePath);
		}

		/**
		 * Добавляет форму для отключения заглушки
		 * @param string $content
		 * @return string
		 */
		private function addAlertForm($content) {
			$document = $this->domDocumentFactory->create();
			libxml_use_internal_errors(true);
			$document->loadHTML('<?xml encoding="utf-8" ?>' . $content);

			$head = $document->getElementsByTagName('head');

			if ($head->length === 0) {
				$head = $document->createElement('head', '');

				$document->getElementsByTagName('html')
					->item(0)
					->appendChild($head);

				$head = $document->getElementsByTagName('head');
			}

			foreach ($this->getScriptList() as $script) {
				$element = $document->createElement('script', '');
				$element->setAttribute('src' , $script);
				$head->item(0)
					->appendChild($element);
			}

			$style = $document->createElement('link', '');
			$style->setAttribute('href', $this->getAlertCss());
			$style->setAttribute('rel','stylesheet');

			$document->getElementsByTagName('body')
				->item(0)
				->appendChild($style);

			return $document->saveHTML();
		}

		/**
		 * Возвращает список ссылок для скриптов для формы с входом
		 * @return array
		 */
		private function getScriptList() {
			return [
				'/styles/common/js/jquery/jquery.js',
				'/styles/common/js/jquery/jquery-migrate.js',
				'/ulang/common.js',
				'/styles/skins/modern/design/js/dataView/underscore-min.js',
				'/errors/stub.js'
			];
		}

		/**
		 * Возвращает ссылку на css файл для формы отключения страницы заглушки
		 * @return string
		 */
		private function getAlertCss() {
			return $this->config->get('stub', 'modal-window-css');
		}
	}