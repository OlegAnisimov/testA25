<?php
	namespace UmiCms\System\Protection\PrivateKeys;

	use \iConfiguration as iConfig;
	use UmiCms\System\Protection\iEncrypter as iCryptographer;
	use UmiCms\Classes\System\Entities\File\iFactory as iFileFactory;

	/**
	 * Интерфейс связки приватных ключей
	 * @package UmiCms\System\Protection\PrivateKeys
	 */
	interface iBunch {

		/** @var string SERVICE_NAME имя сервиса в UMI */
		const SERVICE_NAME = 'PrivateKeysBunch';

		/**
		 * Конструктор
		 * @param iCryptographer $cryptographer шифровальщик
		 * @param iConfig $config конфигурация
		 * @param iFileFactory $fileFactory фабрика файлов
		 */
		public function __construct(
			iCryptographer $cryptographer, iConfig $config, iFileFactory $fileFactory
		);

		/**
		 * Возвращает содержимое приватного ключа
		 * @param string $name имя
		 * @return string
		 * @throws \ErrorException
		 */
		public function get(string $name) : string;

		/**
		 * Сохраняет приватный ключ
		 * @param string $name имя
		 * @param string $content содержимое
		 * @return $this|iBunch
		 * @throws \ErrorException
		 */
		public function set(string $name, string $content) : iBunch;

		/**
		 * Удаляет приватных ключ
		 * @param string $name имя
		 * @return $this|iBunch
		 * @throws \ErrorException
		 */
		public function delete(string $name) : iBunch;
	}