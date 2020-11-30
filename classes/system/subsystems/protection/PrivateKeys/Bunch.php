<?php
	namespace UmiCms\System\Protection\PrivateKeys;

	use \iConfiguration as iConfig;
	use UmiCms\System\Protection\iEncrypter as iCryptographer;
	use UmiCms\Classes\System\Entities\File\iFactory as iFileFactory;

	/**
	 * Класс связки приватных ключей
	 * @package UmiCms\System\Protection\PrivateKeys
	 */
	class Bunch implements iBunch {

		/** @var iConfig $config конфигурация */
		private $config;

		/** @var iCryptographer $cryptographer шифровальщик */
		private $cryptographer;

		/** @var iFileFactory $fileFactory фабрика файлов */
		private $fileFactory;

		/** @inheritDoc */
		public function __construct(
			iCryptographer $cryptographer, iConfig $config, iFileFactory $fileFactory
		) {
			$this->cryptographer = $cryptographer;
			$this->config = $config;
			$this->fileFactory = $fileFactory;
		}

		/** @inheritDoc */
		public function get(string $name) : string {
			$path = $this->resolveFilePath($name);
			$file = $this->fileFactory->create($path);

			if (!$file->isExists()) {
				throw new \ErrorException(sprintf('Cannot read key "%s"', $name));
			}

			$content = (string) $file->getContent();

			if ($content === '') {
				throw new \ErrorException(sprintf('Key "%s" is empty', $name));
			}

			return $this->cryptographer->decrypt($content);
		}

		/** @inheritDoc */
		public function set(string $name, string $content) : iBunch {
			if ($content === '') {
				throw new \ErrorException(
					sprintf('Cannot write empty content for key "%s"', $name)
				);
			}

			$content = $this->normaliseKey($content);
			$path = $this->resolveFilePath($name);
			$file = $this->fileFactory->create($path);

			$content = $this->cryptographer->encrypt($content);
			$file->putContent($content);
			$file->refresh();

			if (!$file->isExists()) {
				throw new \ErrorException(sprintf('Cannot write key "%s"', $name));
			}

			return $this;
		}

		/** @inheritDoc */
		public function delete(string $name) : iBunch {
			$path = $this->resolveFilePath($name);
			$file = $this->fileFactory->create($path);

			if (!$file->isExists()) {
				throw new \ErrorException(sprintf('Cannot read key "%s"', $name));
			}

			$file->delete();
			$file->refresh();

			if ($file->isExists()) {
				throw new \ErrorException(sprintf('Cannot delete key "%s"', $name));
			}

			return $this;
		}

		/**
		 * Возвращает путь до файла приватного ключа
		 * @param string $fileName имя ключа
		 * @return string
		 * @throws \ErrorException
		 */
		private function resolveFilePath(string $fileName) : string {
			$directory = $this->config->includeParam('private-keys-path');
			return $directory . '/' . $this->cryptographer->encrypt($fileName);
		}

		/**
		 * Исправляет содержимое приватного ключа
		 * @param string $content содержимое
		 * @return string
		 */
		private function normaliseKey(string $content) : string {
			$content = trim($content);
			$content = str_replace('\\n', PHP_EOL, $content);
			$content = str_replace('\n', PHP_EOL, $content);
			$content = str_replace('\n\\', PHP_EOL, $content);
			$content = str_replace('\r\n', PHP_EOL, $content);
			return str_replace('\r', PHP_EOL, $content);
		}
	}