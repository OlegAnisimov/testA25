<?php

	namespace UmiCms\System\Module\Permissions;

	use UmiCms\Classes\System\Entities\File\iFactory as FileFactory;
	use UmiCms\Classes\System\Entities\Directory\iFactory as DirectoryFactory;

	/** Загрузчик прав модулей */
	class Loader implements iLoader {

		/** @var string $templateCustomsDirectory путь до директории c кастомными макросами шаблона дизайна */
		private $templateCustomsDirectory;

		/** @var DirectoryFactory фабрика директорий */
		private $directoryFactory;

		/** @var FileFactory фабрика файлов */
		private $fileFactory;

		/**
		 * @var array права на модули, загружаемые из файлов permissions.*.php
		 * [
		 *     <moduleName> => []
		 * ]
		 */
		private $permissions;

		/** @inheritDoc */
		public function __construct(
			\iCmsController $cmsController,
			DirectoryFactory $directoryFactory,
			FileFactory $fileFactory
		) {
			$template = $cmsController->getCurrentTemplate();
			$this->templateCustomsDirectory = ($template instanceof \iTemplate) ? $template->getCustomsDirectory() : null;
			$this->directoryFactory = $directoryFactory;
			$this->fileFactory = $fileFactory;
		}

		/** @inheritDoc */
		public function load($module) {
			$this->permissions[$module] = [];
			$this->loadSystemPermissions($module);
			$this->loadCustomPermissions($module);
			$this->loadTemplatePermissions($module);
			$this->loadExtensionPermissions($module);
			return $this->permissions[$module];
		}

		/**
		 * Загружает системные права на модуль
		 * @param string $module название модуля
		 */
		private function loadSystemPermissions($module) {
			$file = $this->getFile(SYS_MODULES_PATH . $module . '/permissions.php');
			$this->loadFilePermissions($module, $file);
		}

		/**
		 * Загружает права на модуль из файла
		 * @param string $module название модуля
		 * @param \iUmiFile $file файл
		 */
		private function loadFilePermissions($module, \iUmiFile $file) {
			if (!$file->isExists()) {
				return;
			}

			/** @var array переменная наполняется в подключаемом файле */
			$permissions = [];
			require $file->getFilePath();

			if (is_array($permissions)) {
				$this->permissions[$module] = array_merge_recursive(
					$this->permissions[$module],
					$permissions
				);
			}
		}

		/**
		 * Загружает кастомные права на модуль
		 * @param string $module название модуля
		 */
		private function loadCustomPermissions($module) {
			$file = $this->getFile(SYS_MODULES_PATH . $module . '/permissions.custom.php');
			$this->loadFilePermissions($module, $file);
		}

		/**
		 * Загружает права на модуль из шаблона дизайна
		 * @param string $module название модуля
		 */
		private function loadTemplatePermissions($module) {
			if (!$this->templateCustomsDirectory) {
				return;
			}

			$file = $this->getFile($this->templateCustomsDirectory . $module . '/permissions.php');
			$this->loadFilePermissions($module, $file);
		}

		/**
		 * Загружает права на модуль из расширений модуля
		 * @param string $module название модуля
		 */
		private function loadExtensionPermissions($module) {
			$dir = $this->getDirectory(SYS_MODULES_PATH . $module);
			foreach ($dir->getList('ext/permissions.*.php') as $path) {
				$file = $this->fileFactory->create($path);
				$this->loadFilePermissions($module, $file);
			}
		}

		/**
		 * Возвращает файл
		 * @param string $path путь до файла
		 * @return \iUmiFile
		 */
		private function getFile($path) {
			return $this->fileFactory->create($path);
		}

		/**
		 * Возвращает директорию
		 * @param string $path путь до директории
		 * @return \iUmiDirectory
		 */
		private function getDirectory($path) {
			return $this->directoryFactory->create($path);
		}
	}
