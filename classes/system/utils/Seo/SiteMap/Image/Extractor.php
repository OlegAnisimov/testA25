<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use \iUmiImageFile as iImage;
	use mainConfiguration as iConfig;
	use UmiCms\Classes\System\Utils\Html\iParser;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Utils\Url\iFactory as iUrlFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Lite\iClient;
	use \GuzzleHttp\Exception\ConnectException as iTimeoutException;
	use UmiCms\Classes\System\Entities\Image\iFactory as iImageFactory;
	use UmiCms\Classes\System\Utils\Api\Http\Lite\Client\iFactory as iHttpClientFactory;

	/**
	 * Класс извлекателя изображений карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Extractor implements iExtractor {

		/** @var int DEFAULT_TIMEOUT таймаут http запроса адреса карты сайта по-умолчанию в секундах */
		const DEFAULT_TIMEOUT = 4;

		/** @var iUrlFactory $urlFactory фабрика url'ов */
		private $urlFactory;

		/** @var iImageFactory $imageFactory фабрика изображений */
		private $imageFactory;

		/** @var iParser $parser html парсер */
		private $parser;

		/** @var iClient $httpClient http клиент */
		private $httpClient;

		/** @inheritDoc */
		public function __construct(
			iUrlFactory $urlFactory,
			iImageFactory $imageFactory,
			iParser $parser,
			iHttpClientFactory $httpClientFactory,
			iConfig $config
		) {
			$this->urlFactory = $urlFactory;
			$this->imageFactory = $imageFactory;
			$this->parser = $parser;
			$this->httpClient = $httpClientFactory->create([
				'timeout' => (int) $config->get(
					'site-map', 'update-site-map-image-request-timeout' , self::DEFAULT_TIMEOUT
				)
			]);
		}

		/** @inheritDoc */
		public function extract(iLocation $location) : array {
			$html = $this->loadHtml($location);
			$imageAttributeList = $this->parseImageAttributeList($html);
			return $this->createImageList($imageAttributeList);
		}

		/**
		 * Загружает html по заданному адресу
		 * @param iLocation $location адрес
		 * @return string
		 * @throws \Exception
		 */
		private function loadHtml(iLocation $location) : string {
			try {
				return $this->httpClient->get($location->getLink());
			} catch (iTimeoutException $exception) {
				\umiExceptionHandler::report($exception);
				return '';
			}
		}

		/**
		 * Разбирает атрибуты изображений из html
		 * @param string $html html
		 * @return array
		 */
		private function parseImageAttributeList(string $html) : array {
			if (!$html) {
				return [];
			}

			$imageAttributeList = $this->parser->getImages($html);
			$imageAttributeList = $this->filterImageListWithEmptySrc($imageAttributeList);
			$baseList = $this->parser->getTagAttributes('base', $html);
			$baseHref = (is_array($baseList) && isset($baseList['href'])) ? (string) $baseList['href'] : '';
			return $this->convertImageListSrcFromUrlToFilePath($imageAttributeList, $baseHref);
		}

		/**
		 * Отфильтровывать изображения без адресов изображений
		 * @param array $imageAttributeList атрибуты изображений
		 * @return array
		 */
		private function filterImageListWithEmptySrc(array $imageAttributeList) : array {
			return array_filter($imageAttributeList, function(array $imageAttribute) {
				return isset($imageAttribute['src']);
			});
		}

		/**
		 * Конвертирует веб адреса изображений в файловые пути
		 * @param array $imageAttributeList атрибуты изображений
		 * @param string $baseHref базовый путь до файлов
		 * @return array
		 */
		private function convertImageListSrcFromUrlToFilePath(array $imageAttributeList, string $baseHref) : array {
			return array_map(function(array $imageAttribute) use ($baseHref) {
				$src = $imageAttribute['src'];

				if (!startsWith($src, '/') && !startsWith($src, 'http') && !startsWith($src, '://')) {
					$src = $baseHref . $src;
				}

				$imageAttribute['src'] = $this->urlFactory
					->create($src)
					->getPath();

				return $imageAttribute;
			}, $imageAttributeList);
		}

		/**
		 * Создает список изображений
		 * @param array $imageAttributeList атрибуты изображений
		 * @return iImage[]
		 */
		private function createImageList(array $imageAttributeList) : array {
			$imageList = [];

			foreach ($imageAttributeList as $imageAttributes) {
				$image = $this->imageFactory
					->createWithAttributes($imageAttributes['src'], $imageAttributes);

				if ($image->getIsBroken()) {
					continue;
				}

				$imageList[$image->getFilePath(true)] = $image;
			}

			return $imageList;
		}
	}