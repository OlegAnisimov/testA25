<?php

	namespace UmiCms\Classes\System\Entities\File;

	use UmiCms\Classes\System\Entities\Directory\iFactory as iDirectoryFactory;

	/**
	 * Загрузчик файлов
	 * @package UmiCms\Classes\System\Entities\File
	 */
	class Uploader implements iUploader {

		/** @var \iRegedit $registry  */
		protected $registry;

		/** @var \mainConfiguration $configuration */
		protected $configuration;

		/** @var iDirectoryFactory $directoryFactory */
		protected $directoryFactory;

		/** @var iFactory $fileFactory */
		protected $fileFactory;

		/**
		 * Конструктор
		 * @param \iRegedit $registry
		 * @param \mainConfiguration $configuration
		 * @param iDirectoryFactory $directoryFactory
		 * @param iFactory $fileFactory
		 */
		public function __construct(
			\iRegedit $registry,
			\mainConfiguration $configuration,
			iDirectoryFactory $directoryFactory,
			iFactory $fileFactory
		) {
			$this->registry = $registry;
			$this->configuration = $configuration;
			$this->directoryFactory = $directoryFactory;
			$this->fileFactory = $fileFactory;
		}
	}