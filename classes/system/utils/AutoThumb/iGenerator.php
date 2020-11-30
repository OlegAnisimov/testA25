<?php
	namespace UmiCms\Utils\AutoThumb;

	use \iConfiguration as iConfig;
	use \iImageProcessor as iImageProcessor;
	use UmiCms\Classes\System\Entities\Image\iFactory as iImageFactory;

	/**
	 * Интерфейс генератора автоматических миниатюр
	 * @package UmiCms\Utils\AutoThumb
	 */
	interface iGenerator {

		/**
		 * Конструктор
		 * @param iConfig $config конфигурация
		 * @param iImageFactory $imageFactory фабрика изображений
		 * @param iImageProcessor $imageProcessor процессор изображений
		 */
		public function __construct(iConfig $config, iImageFactory $imageFactory, iImageProcessor $imageProcessor);

		/**
		 * Создает миниатюру и возвращает ее
		 * @param string $path псевдоадрес миниатюры
		 * @example: ./images/top_auto_auto.jpg
		 * @return \iUmiImageFile
		 * @throws \ErrorException
		 * @throws \coreException
		 */
		public function execute($path);
	}