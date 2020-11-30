<?php

	namespace UmiCms\System\Utils\Image\Processor;

	/**
	 * Интерфейс фабрики обработчиков изображений
	 * @package UmiCms\System\Utils\Image\Processor
	 */
	interface iFactory {

		/**
		 * Создает обработчик изображений
		 * @return \iImageProcessor
		 */
		public function create();
	}