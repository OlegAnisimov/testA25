<?php
	namespace UmiCms\Utils\AutoThumb;

	use \iConfiguration as iConfig;
	use \iImageProcessor as iImageProcessor;
	use UmiCms\Classes\System\Entities\Image\iFactory as iImageFactory;

	/**
	 * Класс генератора автоматических миниатюр
	 * @package UmiCms\Utils\AutoThumb
	 */
	class Generator implements iGenerator {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iImageFactory $imageFactory фабрика изображений */
		private $imageFactory;

		/** @var iImageProcessor $imageProcessor процессор изображений */
		private $imageProcessor;

		/** @inheritDoc */
		public function __construct(iConfig $config, iImageFactory $imageFactory, iImageProcessor $imageProcessor) {
			$this->config = $config;
			$this->imageFactory = $imageFactory;
			$this->imageProcessor = $imageProcessor;
		}

		/** @inheritDoc */
		public function execute($path) {
			$path = trim($path);

			if ($path === '') {
				throw new \ErrorException('Empty path given');
			}

			$path = str_replace('./', '/', $path);

			if (!$this->isAllowedPath($path)) {
				throw new \ErrorException(sprintf('Image path not allowed: %s', $path));
			}

			return $this->generate($path);
		}

		/**
		 * Определяет разрешено ли создание миниатюры
		 * @param string $path псевдоадрес миниатюры
		 * @return bool
		 */
		private function isAllowedPath($path) {
			$checkPath = realpath(dirname(CURRENT_WORKING_DIR . $path));
			$allowedPath = $this->getAllowedPathList();
			return !(strcmp(mb_substr($checkPath, 0, mb_strlen($allowedPath[0])), $allowedPath[0]) != 0
				&& strcmp(mb_substr($checkPath, 0, mb_strlen($allowedPath[1])), $allowedPath[1]) != 0);
		}

		/**
		 * Возвращает список директорий с разрешенными файлами
		 * @return array
		 */
		private function getAllowedPathList() {
			return [
				$this->config->includeParam('user-images-path'),
				$this->config->includeParam('user-files-path')
			];
		}

		/**
		 * Возвращает адрес директории с миниатюрами
		 * @return string
		 */
		private function getAutoThumbPath() {
			return $this->config->includeParam('user-images-path') . '/cms/thumbs/autothumbs';
		}

		/**
		 * Создает миниатюру на основе переданного псевдоадреса миниатюры
		 * @param string $path псевдоадрес миниатюры
		 * (в нем зашифрованы параметры оригинального и результирующего изображений)
		 * @return \iUmiImageFile
		 * @throws \ErrorException
		 * @throws \coreException
		 */
		private function generate($path) {
			$path = str_replace('sl_', '', $path); // legacy fix
			$imagePath = ltrim($path, "/\\");
			$imagePathParts = explode('/', $imagePath);
			$imageName = array_pop($imagePathParts);
			$imageNameParts = explode('.', $imageName);
			$thumbExtension = array_pop($imageNameParts);
			$thumbName = md5($path);
			$thumbPath = $this->getAutoThumbPath() . '/' . $thumbName . '.' . $thumbExtension;
			$imageNameWithDimensions = implode('.', $imageNameParts);
			$thumbDimensions = explode('_', $imageNameWithDimensions);
			$thumbHeight = (int) array_pop($thumbDimensions);
			$thumbWidth = (int) array_pop($thumbDimensions);

			$sourceImagePath = './' . implode('/', $imagePathParts) . '/' . implode('_', $thumbDimensions) . '.' . $thumbExtension;
			$sourceImage = $this->imageFactory->create($sourceImagePath);

			if ($sourceImage->getIsBroken()) {
				throw new \ErrorException(sprintf('Image source not exists: %s', $sourceImagePath));
			}

			if ((!file_exists($thumbPath)) || (filemtime($sourceImagePath) > filemtime($thumbPath))) {
				if (!$this->isEnoughDiscSpace()) {
					throw new \ErrorException('Disc quota exceeded');
				}

				$thumbPath = $this->createThumbnail($sourceImagePath, $thumbWidth, $thumbHeight, $thumbPath);
			}

			$thumb = $this->imageFactory->create($thumbPath);

			if ($thumb->getIsBroken()) {
				throw new \ErrorException(sprintf('Cannot create thumb: %s', $thumbPath));
			}

			return $thumb;
		}

		/**
		 * Определяет достаточно ли места для создания миниатюры
		 * @return bool
		 */
		private function isEnoughDiscSpace() {
			$sizeQuota = $this->config->get('system', 'quota-files-and-images');
			$sizeQuotaInBytes = getBytesFromString($sizeQuota);

			if ($sizeQuotaInBytes != 0) {
				$busySizeInBytes = 0;

				foreach ($this->getAllowedPathList() as $directory) {
					$busySizeInBytes += getDirSize($directory);
				}

				if ($busySizeInBytes >= $sizeQuotaInBytes) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Создает миниатюру с заданными параметрами
		 * @param string $imagePath адрес оригинального изображения
		 * @param int $thumbWidth ширина миниатюры
		 * @param int $thumbHeight высота миниатюры
		 * @param string $thumbPath желаемый адрес миниатюры
		 * @return bool|string
		 * @throws \coreException
		 */
		private function createThumbnail($imagePath, $thumbWidth = 0, $thumbHeight = 0, $thumbPath = '') {

			$image = $this->imageFactory->create($imagePath);
			$imageBaseName = $image->getFileName();
			$imageExtension = $image->getExt();
			$imageExtensionCaseSensitive = $image->getExt(true);
			$imageWidth = $image->getWidth();
			$imageHeight = $image->getHeight();
			$thumbPath = (string) $thumbPath;

			if ($thumbPath === '') {
				$thumbBaseName = $imageBaseName . '_' . $thumbWidth . '_' . $thumbHeight . '_' . $imageExtensionCaseSensitive . '.' . $imageExtension;
				$thumbPath = $this->getAutoThumbPath() . '/' . $thumbBaseName;
			}

			if (file_exists($thumbPath)) {
				return $thumbPath;
			}

			if (($thumbWidth > $imageWidth && $thumbHeight > $imageHeight) // Оба параметра больше размеров исходной картинки
				|| ($thumbWidth > $imageWidth && $thumbHeight == 0) // Ширина больше исходной, высота автоматическая
				|| ($thumbWidth == 0 && $thumbHeight > $imageHeight)) { // Высота больше исходной, ширина автоматическая

				copy($imagePath, $thumbPath);
			} else {

				if (!$thumbHeight) {
					$thumbHeight = (int) round($imageHeight * ($thumbWidth / $imageWidth));
				}

				if (!$thumbWidth) {
					$thumbWidth = (int) round($imageWidth * ($thumbHeight / $imageHeight));
				}

				if (!$thumbWidth && !$thumbHeight) {
					$thumbWidth = $imageWidth;
					$thumbHeight = $imageHeight;
				}

				$this->imageProcessor->thumbnail($imagePath, $thumbPath, $thumbWidth, $thumbHeight);
			}

			return $thumbPath;
		}
	}