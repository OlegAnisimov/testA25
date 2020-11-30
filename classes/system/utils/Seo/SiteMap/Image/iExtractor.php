<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use \iUmiImageFile as iImage;
	use mainConfiguration as iConfig;
	use UmiCms\Classes\System\Utils\Html\iParser;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Utils\Url\iFactory as iUrlFactory;
	use UmiCms\Classes\System\Entities\Image\iFactory as iImageFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Lite\Client\iFactory as iHttpClientFactory;

	/**
	 * Интерфейс извлекателя изображений карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iExtractor {

		/**
		 * Конструктор
		 * @param iUrlFactory $urlFactory фабрика url'ов
		 * @param iImageFactory $imageFactory фабрика изображений
		 * @param iParser $parser html парсер
		 * @param iHttpClientFactory $httpClientFactory фабрика http клиентов
		 * @param iConfig $config конфигурация
		 */
		public function __construct(
			iUrlFactory $urlFactory,
			iImageFactory $imageFactory,
			iParser $parser,
			iHttpClientFactory $httpClientFactory,
			iConfig $config
		);

		/**
		 * Извлекает изображения из адреса карты сайта
		 * @param iLocation $location
		 * @return iImage[]
		 * @throws \Exception
		 */
		public function extract(iLocation $location) : array;
	}