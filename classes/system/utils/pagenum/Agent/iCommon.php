<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use \iConfiguration as iConfig;
	use UmiCms\Classes\System\PageNum\iAgent;
	use UmiCms\System\Utils\Url\iFactory as iUrlFactory;

	/**
	 * Интерфейс агента пагинации по-умолчанию
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	interface iCommon extends iAgent {

		/** @var string DEFAULT_KEY ключ пагинации по умолчанию */
		const DEFAULT_KEY = 'p';

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iUrlFactory $urlFactory фабрика адресов
		 */
		public function __construct(iConfig $config, iUrlFactory $urlFactory);
	}