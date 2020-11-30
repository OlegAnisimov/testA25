<?php

	namespace UmiCms\Classes\System\Entities\Image;

	use UmiCms\Classes\System\Entities\File\iUploader as iFileUploader;

	/**
	 * Интерфейс загрузчика изображений
	 * @package UmiCms\Classes\System\Entities\Image
	 */
	interface iUploader extends iFileUploader {

		/**
		 * Загружает файл по http-ссылке
		 * @param string $link ссылка
		 * @param string $destinationDirectory директория для загрузки
		 * @return \iUmiImageFile|false
		 */
		public function uploadByHttpLink($link, $destinationDirectory);
	}