<?php
	namespace UmiCms\Classes\System\PageNum;

	/**
	* Интерфейс агента пагинации
	* @package UmiCms\Classes\System\PageNum
	*/
	interface iAgent {

		/**
		 * Извлекает номер страницы пагинации из адреса
		 * @param string $url адрес страницы
		 * @return int
		 */
		public function resolve(string $url) : int;

		/**
		 * Определяет задан ли номер страницы
		 * @param string $url
		 * @return bool
		 */
		public function issetPageNumber(string $url) : bool;

		/**
		 * Очищает адрес страницы от параметров пагинации
		 * @param string $url
		 * @return string
		 */
		public function cleanUrl(string $url) : string;

		/**
		 * Генерирует адрес страницы с параметрами пагинации
		 * @param string $url адрес страницы
		 * @param int $number номер страницы
		 * @return string
		 */
		public function generateUri(string $url, int $number) : string;
	}