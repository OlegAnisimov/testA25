<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\PageNum\iAgent;
	use UmiCms\System\Request\iFacade as iRequest;

	/**
	 * Интерфейс фасада агентов пагинации
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	interface iFacade {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iFactory $factory фабрика агентов
		 */
		public function __construct(iConfig $config, iFactory $factory);

		/**
		 * Создает агента пагиации
		 * @param iRequest $request запрос к сайту
		 * @return iAgent
		 * @throws \Exception
		 */
		public function getAgent(iRequest $request) : iAgent;
	}