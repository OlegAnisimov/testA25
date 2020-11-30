<?php
	namespace UmiCms\System\Hierarchy\Template;

	/**
	 * Интерфейс фабрики шаблонов
	 * @todo: реализовать интерфейс создания шаблонов сайта
	 * @package UmiCms\System\Hierarchy\Template
	 */
	interface iFactory {

		/**
		 * Создает заглушку шаблона сайта
		 * @return \iTemplate
		 * @throws \privateException
		 */
		public function createDummy();
	}