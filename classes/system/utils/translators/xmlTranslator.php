<?php

	use UmiCms\Service;

	/** Класс xml транслятора (сериализатора) */
	class xmlTranslator implements iXmlTranslator {

		/**
		 * @todo: убрать из этого класса, так как не относится к этому классу
		 * @var bool $showHiddenFieldGroups режим сериализации групп полей, при котором сериализуются невидимые группы
		 */
		public static $showHiddenFieldGroups = false;

		/**
		 * @todo: убрать из этого класса, так как не относится к этому классу
		 * @var bool $showUnsecureFields режим сериализации значений полей, при котором сериализуются приватные поля
		 */
		public static $showUnsecureFields = false;

		/**
		 * @todo: это свойство не должно быть публичным
		 * @var array $keysCache кеш ключей
		 */
		public static $keysCache = [];

		/**
		 * @todo: это свойство не должно быть публичным
		 * @var array $translateCache кеш сериализованных данных
		 */
		public static $translateCache = [];

		/** @var null|DOMDocument документ, куда требуется добавить сериализованные данные */
		protected $domDocument = null;

		/** @var array $shortKeys соответствия сокращений названий ключей их полным названиям */
		protected static $shortKeys = [
			'@' => 'attribute',
			'#' => 'node',
			'+' => 'nodes',
			'%' => 'xlink',
			'*' => 'comment'
		];

		/** @var string ESCAPE_OPTION опция экранирование данных, способных сделать xml невалидным */
		const ESCAPE_OPTION = 'escape-node-and-attribute-values';

		/** @var string DEEP_OPTION опция полной сериализации вложенных сушностей */
		const DEEP_OPTION = 'serialize-related-entities';

		/** @var string IGNORE_I18N опция игнорирования переводов языковых меток */
		const IGNORE_I18N = 'ignore-i18n';

		/** @var array $optionList опции сериализации */
		private static $optionList = [];

		/** @var bool|null $isParseTplMacros разрешен ли парсинг tpl макросов */
		private static $isParseTplMacros;

		/** @var string[] $macrosBlackList черный список макросов  */
		private static $macrosBlackList = [];

		/** @var string[] $macrosWhiteList белый список макросов */
		private static $macrosWhiteList = [];

		/** @inheritDoc */
		public function __construct(DOMDocument $domDocument = null) {
			$this->domDocument = $domDocument;
		}

		/** @inheritDoc */
		public function setDocument(DOMDocument $domDocument) {
			$this->domDocument = $domDocument;
			return $this;
		}

		/** @inheritDoc */
		public function translateToXml(DOMElement $rootNode, $userData) {
			$options = $this->getDefaultOptions();
			$this->chooseTranslator($rootNode, $userData, $options);
		}

		/** @inheritDoc */
		public function chooseTranslator(DOMElement $rootNode, $userData, $options = []) {
			switch (gettype($userData)) {

				case 'array': {
					$this->translateArray($rootNode, $userData, $options);
					break;
				}

				case 'object': {
					if (!$userData instanceof iUmiEntinty && !$userData instanceof umiObjectProxy) {
						$wrapper = translatorWrapper::get($userData);

						foreach ($options as $name => $value) {
							$wrapper->setOption($name, $value);
						}

						$this->chooseTranslator($rootNode, $wrapper->translate($userData), $options);
						break;
					}

					$cache = &self::$translateCache;
					$optionKey = '';

					foreach ($options as $name => $value) {
						$value = is_array($value) ? implode('', $value) : $value;
						$optionKey .= $name . $value;
					}

					$key = get_class($userData) .
						'#' . $userData->getId() .
						'#' . md5($optionKey) .
						'#' . ((int) translatorWrapper::$showEmptyFields);

					if (!isset($cache[$key])) {
						$wrapper = translatorWrapper::get($userData);

						foreach ($options as $name => $value) {
							$wrapper->setOption($name, $value);
						}

						$cache[$key] = $wrapper->translate($userData);
					}
					$this->chooseTranslator($rootNode, $cache[$key], $options);
					break;
				}

				default: {
					$this->translateBasic($rootNode, $userData, $options);
					break;
				}
			}
		}

		/**
		 * Возвращает опции сериализации по умолчанию
		 * @return array
		 */
		private function getDefaultOptions() {
			$request = Service::Request();
			return array_merge(self::$optionList, [
				self::DEEP_OPTION => false,
				self::ESCAPE_OPTION => $request->isSite() && $request->isXml()
			]);
		}

		/** @inheritDoc */
		public static function isParseTPLMacrosesAllowed() {
			if (is_bool(self::$isParseTplMacros)) {
				return self::$isParseTplMacros;
			}

			self::$isParseTplMacros = true;

			if (Service::Request()->isAdmin()) {
				self::$isParseTplMacros = false;
			} elseif (defined('XML_MACROSES_DISABLE') && XML_MACROSES_DISABLE) {
				$allowedList = mainConfiguration::getInstance()
					->get('kernel', 'xml-macroses.allowed');

				self::$isParseTplMacros = (is_array($allowedList) && umiCount($allowedList));
			}

			return self::$isParseTplMacros;
		}

		/** @inheritDoc  */
		public static function enableTplMacrosParsing(bool $status = true) : void {
			self::$isParseTplMacros = $status;
		}

		/** @inheritDoc */
		public static function getAllowedTplMacroses() {
			if (count(self::$macrosWhiteList) > 0) {
				return self::$macrosWhiteList;
			}

			if (defined('XML_MACROSES_DISABLE') && XML_MACROSES_DISABLE) {
				$macrosList = (array) mainConfiguration::getInstance()
					->get('kernel', 'xml-macroses.allowed');
			} else {
				$macrosList = [];
			}

			return self::$macrosWhiteList = $macrosList;
		}

		/** @inheritDoc */
		public static function setMacrosBlackList(array $macrosList) : void {
			self::$macrosBlackList = $macrosList;
		}

		/** @inheritDoc */
		public static function executeMacroses($userData, $scopeElementId = false, $scopeObjectId = false) {
			if (!self::isParseTPLMacrosesAllowed()) {
				return $userData;
			}

			if (!contains($userData, '%')) {
				return $userData;
			}

			/** @var umiTemplaterTPL $templateEngine */
			$templateEngine = umiTemplater::create('TPL');
			$templateEngine->setMacrosWhiteList(self::getAllowedTplMacroses());
			$templateEngine->setMacrosBlackList(self::$macrosBlackList);
			$templateEngine->setScope($scopeElementId, $scopeObjectId);
			return $templateEngine->parse([], $userData);
		}

		/** @inheritDoc */
		public static function getRealKey($key) {
			$keysCache = &self::$keysCache;
			if (!isset($keysCache[$key])) {
				$keysCache[$key] = self::getKey($key);
			}

			list(, $realKey) = $keysCache[$key];
			return $realKey;
		}

		/** @inheritDoc */
		public static function getSubKey($key) {
			$keysCache = &self::$keysCache;
			if (!isset($keysCache[$key])) {
				$keysCache[$key] = self::getKey($key);
			}

			list($subKey, ) = $keysCache[$key];
			return $subKey;
		}

		/** @inheritDoc */
		public static function getKey($key) {
			$key = (string) $key;

			if (isset(self::$shortKeys[$key[0]])) {
				return [
					self::$shortKeys[$key[0]],
					mb_substr($key, 1)
				];
			}

			$keySeparator = ':';

			return mb_strpos($key, $keySeparator) ? explode($keySeparator, $key, 2) : [false, $key];
		}

		/** @inheritDoc */
		public static function clearCache() {
			self::$keysCache = [];
			self::$translateCache = [];
		}

		/** @inheritDoc */
		public static function setOption($name, $value) {
			self::$optionList[$name] = $value;
		}

		/** @inheritDoc */
		public static function getOption($name) {
			if (!isset(self::$optionList[$name])) {
				return null;
			}

			return self::$optionList[$name];
		}

		/**
		 * Сериализует скалярные данные
		 * @param DOMElement $rootNode узел, куда требуется добавить сериализованные данные
		 * @param mixed $userData скалярные данные
		 * @param array $options опции сериализации
		 * @throws coreException
		 */
		protected function translateBasic(DOMElement $rootNode, $userData, array $options) {
			$dom = $this->domDocument;

			if ($dom === null) {
				throw new coreException('You should set document first!');
			}

			$userData = self::executeMacroses($userData);
			$needEscape = isset($options[self::ESCAPE_OPTION]) ? $options[self::ESCAPE_OPTION] : false;
			$element = $needEscape ? $dom->createCDATASection($userData) : $dom->createTextNode($userData);
			$rootNode->appendChild($element);
		}

		/**
		 * Сериализует массив
		 * @param DOMElement $rootNode узел, куда требуется добавить сериализованные данные
		 * @param array $userData массив
		 * @param array $options опции сериализации
		 * @throws coreException
		 */
		protected function translateArray(DOMElement $rootNode, $userData, $options = []) {
			$keysCache = &self::$keysCache;
			$dom = $this->domDocument;

			if ($dom === null) {
				throw new coreException('You should set document first!');
			}

			$needEscape = isset($options[self::ESCAPE_OPTION]) ? $options[self::ESCAPE_OPTION] : false;

			foreach ($userData as $key => $val) {
				if (!isset($keysCache[$key])) {
					$keysCache[$key] = self::getKey($key);
				}

				list($subKey, $realKey) = $keysCache[$key];
				$options[self::DEEP_OPTION] = $subKey === 'full';

				switch ($subKey) {
					case 'attr' :
					case 'attribute' : {
						if ($val === '' || $val === null || is_array($val)) {
							break;
						}
						$val = $needEscape ? htmlspecialchars($val) : $val;
						$rootNode->setAttribute($realKey, $val);
						break;
					}

					case 'list' :
					case 'nodes' : {
						if (is_array($val)) {
							foreach ($val as $cval) {
								$element = $dom->createElement($realKey);
								$this->chooseTranslator($element, $cval, $options);
								$rootNode->appendChild($element);
							}
						}
						break;
					}

					case 'node' : {
						$node = $needEscape ? $dom->createCDATASection($val) : $dom->createTextNode($val);
						$rootNode->appendChild($node);
						break;
					}

					case 'void' : {
						break;
					}

					case 'full' : {
						$element = $realKey ? $dom->createElement($realKey) : $rootNode;
						$this->chooseTranslator($element, $val, $options);
						if ($realKey) {
							$rootNode->appendChild($element);
						}
						break;
					}

					case 'xml' : {
						$val = html_entity_decode($val, ENT_COMPAT, 'utf-8');
						$val = str_replace('&', '&amp;', $val);
						$simpleXmlDocument = @secure_load_simple_xml($val);
						if ($simpleXmlDocument !== false) {
							$domElement = dom_import_simplexml($simpleXmlDocument);

							if ($domElement) {
								$domElement = $dom->importNode($domElement, true);
								$rootNode->appendChild($domElement);
							}
						} else {
							$rootNode->appendChild($dom->createTextNode($val));
						}
						break;
					}

					case 'xlink' : {
						$separator = ':';
						$rootNode->setAttribute('xlink' . $separator . $realKey, $val);
						break;
					}

					case 'comment' : {
						$rootNode->appendChild(new DOMComment(' ' . $val . ' '));
						break;
					}

					case 'subnodes' : {
						$nodeKey = 'nodes';
						$separator = ':';
						$nodeName = 'item';

						$res = [
							$realKey => [
								$nodeKey . $separator . $nodeName => $val
							]
						];

						$val = $res;
						unset($res);
					}

					default: {
						if ($realKey === 0) {
							$userDataExport = var_export($userData, true);
							throw new coreException("Can't translate to xml node with key {$key}, sub key {$subKey}, real key {$realKey}, value {$val} and user data {$userDataExport}");
						}
						$element = $dom->createElement($realKey);
						$this->chooseTranslator($element, $val, $options);
						$rootNode->appendChild($element);
					}
				}
			}
		}

		/** @deprecated */
		public static $socialNetworkMode = true;
	}
