<?php

	namespace UmiCms\System\Utils\Image\Processor;

	/**
	 * Фабрика обработчиков изображений
	 * @package UmiCms\System\Utils\Image\Processor
	 */
	class Factory implements iFactory {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function create() {
			return \imageUtils::getImageProcessor();
		}
	}