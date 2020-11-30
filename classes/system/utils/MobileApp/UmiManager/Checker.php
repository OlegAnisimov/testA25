<?php
	namespace UmiCms\Classes\System\MobileApp\UmiManager;

	use \iRegedit as iRegistry;
	use UmiCms\System\Request\iFacade as iRequest;
	use UmiCms\System\Response\iFacade as iResponse;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Класс валидатора системы для работы с мобильным приложением "UMI.Manager"
	 * @package UmiCms\Classes\System\MobileApp\UmiManager
	 */
	class Checker implements iChecker {

		/** @var iRequest $request запрос */
		private $request;

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iResponse $response ответ */
		private $response;

		/** @var iTranslator $translator транслятор */
		private $translator;

		/** @inheritDoc */
		public function __construct(iRequest $request, iRegistry $registry, iResponse $response, iTranslator $translator) {
			$this->request = $request;
			$this->registry = $registry;
			$this->response = $response;
			$this->translator = $translator;
		}

		/** @inheritDoc */
		public function checkRequiredModules() {
			if ($this->isEmarketExists()) {
				return;
			}

			$data = [
				'data' => [
					'type' => null,
					'action' => null,
					'error' => [
						'code' => 0,
						'message' => getLabel('label-module-emarket-is-absent')
					]
				]
			];

			$this->response->getCurrentBuffer()->clear();

			if ($this->request->isXml()) {
				$document = $this->translator->translateToDomDocument($data);
				$this->response->printXml($document);
			} elseif ($this->request->isJson()) {
				$json = $this->translator->translateToJson($data);
				$this->response->printJson($json);
			}

			throw new \publicException(getLabel('label-module-emarket-is-absent'));
		}

		/**
		 * Определяет установлен ли модуль "Интернет-магазин"
		 * @return bool
		 */
		private function isEmarketExists() {
			return (bool) $this->registry->get('//modules/emarket');
		}
	}