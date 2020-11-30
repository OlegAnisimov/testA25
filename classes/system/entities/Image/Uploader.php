<?php

	namespace UmiCms\Classes\System\Entities\Image;

	use UmiCms\Classes\System\Entities\File\iFactory as iFileFactory;
	use UmiCms\Classes\System\Entities\File\Uploader as FileUploader;
	use UmiCms\System\Utils\Image\Processor\iFactory as iImageProcessorFactory;
	use UmiCms\Classes\System\Entities\Directory\iFactory as iDirectoryFactory;

	/**
	 * Загрузчик изображений
	 * @package UmiCms\Classes\System\Entities\Image
	 */
	class Uploader extends FileUploader implements iUploader {

		/** @var iFactory $imageFactory */
		private $imageFactory;

		/** @var iImageProcessorFactory $imageProcessorFactory */
		private $imageProcessorFactory;

		/**
		 * Конструктор
		 * @param \iRegedit $registry
		 * @param \mainConfiguration $configuration
		 * @param iDirectoryFactory $directoryFactory
		 * @param iFileFactory $fileFactory
		 * @param iFactory $imageFactory
		 * @param iImageProcessorFactory $imageProcessorFactory
		 */
		public function __construct(
			\iRegedit $registry,
			\mainConfiguration $configuration,
			iDirectoryFactory $directoryFactory,
			iFileFactory $fileFactory,
			iFactory $imageFactory,
			iImageProcessorFactory $imageProcessorFactory
		) {
			parent::__construct(
				$registry,
				$configuration,
				$directoryFactory,
				$fileFactory
			);

			$this->imageFactory = $imageFactory;
			$this->imageProcessorFactory = $imageProcessorFactory;
		}

		/**
		 * @inheritDoc
		 * @throws \umiRemoteFileGetterException
		 * @throws \Exception
		 */
		public function uploadByHttpLink($link, $destinationDirectory) {
			$directory = $this->directoryFactory->create($destinationDirectory);

			if (!$directory->isExists()) {
				$directory::requireFolder($directory->getPath());
				$directory->refresh();
			}

			if ($directory->getIsBroken() || !$directory->isWritable()) {
				return false;
			}

			$temporaryFile = \umiRemoteFileGetter::get($link, $this->getTemporaryFilePath());

			if (!$this->isAllowedImageSize($temporaryFile->getSize())) {
				$temporaryFile->delete();
				return false;
			}

			$newPath = $destinationDirectory . basename($link);
			/** @var \umiImageFile|\iUmiImageFile $image */
			$image = $this->imageFactory->create($newPath);

			if (!$image instanceof \iUmiImageFile) {
				$temporaryFile->delete();
				return false;
			}

			if (!$image::getIsImage($image->getFilePath()) || !$image->putContent($temporaryFile->getContent())) {
				$temporaryFile->delete();
				$image->delete();
				return false;
			}

			if ($this->configuration->get('kernel', 'jpg-through-gd')) {
				$this->imageProcessorFactory
					->create()
					->optimize(
						$image->getFilePath(true),
						$this->configuration->get('system', 'image-compression')
					);
			}

			return $image;
		}

		/**
		 * Определяет допустим ли размер изображения
		 * @param int $imageSize размер файла
		 * @return bool
		 */
		private function isAllowedImageSize($imageSize) {
			$maxImageSize = (int) $this->registry->get('//settings/max_img_filesize');
			$uploadMaxFileSize = (int) ini_get('upload_max_filesize');
			$maxImageSize = ($maxImageSize < $uploadMaxFileSize) ? $maxImageSize : $uploadMaxFileSize;
			$maxImageSize = $maxImageSize * 1024 * 1024;

			return $maxImageSize > 0 && $maxImageSize > $imageSize;
		}

		/**
		 * Возвращает путь для временного файла
		 * @return string
		 */
		private function getTemporaryFilePath() {
			return sys_get_temp_dir() . '/php' . substr(md5(microtime()), rand(0, 26), 6);
		}
	}