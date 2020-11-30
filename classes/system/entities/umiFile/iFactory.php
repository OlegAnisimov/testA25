<?php

	namespace UmiCms\Classes\System\Entities\File;

	/**
	 * Интерфейс фабрики файлов
	 * @package UmiCms\Classes\System\Entities\File
	 */
	interface iFactory {

		/**
		 * Создает файл
		 * @param string $path путь до файла
		 * @return \iUmiFile
		 */
		public function create($path);

		/**
		 * Создает файл с указанием "сырого" пути
		 * @param string $path путь до файла
		 * @return \iUmiFile
		 */
		public function createByRawPath($path);

		/**
		 * Создает безопасный (не php) файл
		 * @param string $path путь до файла
		 * @return \iUmiFile
		 */
		public function createSecure($path);

		/**
		 * Создает файл c переданными атрибутами
		 * @param string $path путь до файла
		 * @param array $attributeList список атрибутов
		 * @example
		 * [
		 *  'title' => title файла
		 *  'order' => 'Порядок файлов для набора файлов'
		 * ]
		 * @return \iUmiFile
		 */
		public function createWithAttributes($path, $attributeList = []);
	}
