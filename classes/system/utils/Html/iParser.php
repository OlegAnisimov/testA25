<?php
	namespace UmiCms\Classes\System\Utils\Html;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDocumentFactory;

	/**
	 * Интерфейс html парсера
	 * @package UmiCms\Classes\System\Utils\Html
	 */
	interface iParser {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'HtmlParser';

		/**
		 * Конструктор
		 * @param iDocumentFactory $documentFactory фабрика DOM документов
		 */
		public function __construct(iDocumentFactory $documentFactory);

		/**
		 * Возвращает изображения с атрибутами
		 * @param string $html html
		 * @return array
		 */
		public function getImages(string $html) : array;

		/**
		 * Возвращает теги с атрибутами
		 * @param string $tag имя тега
		 * @param string $html html
		 * @return array
		 */
		public function getTagAttributes(string $tag, string $html) : array;

		/**
		 * Заменяет изображения на произвольный html.
		 * Теги изображений должны быть закрыты
		 * @param array $tagsAttributes атрибуты изображения со значением замены по ключу replacement
		 * @param string $html html
		 * @return string
		 */
		public function replaceImages(array $tagsAttributes, string $html) : string;
	}