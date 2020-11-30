<?php
	namespace UmiCms\Utils\Dispatches;

	/**
	 * Интерфейс счетчика открытия рассылок
	 * @package UmiCms\Utils\Dispatches
	 */
	interface iCounter {

		/**
		 * Конструктор
		 * @param \IConnection $connection подключение к бд
		 * @param \iConfiguration $config конфигурация
		 */
		public function __construct(\IConnection $connection, \iConfiguration $config);

		/**
		 * Подсчитывает запрос счетчика
		 * @param string $path адрес запроса
		 * @throws \databaseException
		 */
		public function countEntry($path);

		/**
		 * Генерирует невидимое изображение для вставки в рассылку и возвращает его путь
		 * @return string
		 */
		public function generateImage();
	}