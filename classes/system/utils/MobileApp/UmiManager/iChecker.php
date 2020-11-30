<?php
	namespace UmiCms\Classes\System\MobileApp\UmiManager;

	use \iRegedit as iRegistry;
	use UmiCms\System\Request\iFacade as iRequest;
	use UmiCms\System\Response\iFacade as iResponse;
	use UmiCms\Classes\System\Translators\iFacade as iTranslator;

	/**
	 * Интерфейс валидатора системы для работы с мобильным приложением "UMI.Manager"
	 * @package UmiCms\Classes\System\MobileApp\UmiManager
	 */
	interface iChecker {

		/**
		 * Конструктор
		 * @param iRequest $request запрос
		 * @param iRegistry $registry реестр
		 * @param iResponse $response ответ
		 * @param iTranslator $translator транслятор
		 */
		public function __construct(iRequest $request, iRegistry $registry, iResponse $response, iTranslator $translator);

		/**
		 * Проверяет установлены ли все необходимые модули
		 * @throws \ErrorException
		 * @throws \publicException
		 */
		public function checkRequiredModules();
	}