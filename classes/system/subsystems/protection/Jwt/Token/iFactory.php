<?php
	namespace UmiCms\System\Protection\Jwt\Token;

	use UmiCms\System\Protection\Jwt\iToken;
	use UmiCms\System\Protection\PrivateKeys\iBunch;

	/**
	 * Интерфейс фабрики jwt токенов
	 * @link https://jwt.io/introduction/
	 * @package UmiCms\System\Protection\Jwt\Token
	 */
	interface iFactory {

		/** @var string SERVICE_NAME имя сервиса в UMI */
		const SERVICE_NAME = 'JvmTokenFactory';

		/**
		 * Конструктор
		 * @param iBunch $privateKeysBunch связка ключей
		 */
		public function __construct(iBunch $privateKeysBunch);

		/**
		 * Создает Jwt токен
		 * @param array $attributes атрибуты токена
		 * @return iToken
		 */
		public function create(array $attributes = []) : iToken;

		/**
		 * Создает Jwt токен с указанным приватным ключом
		 * @param string $privateKey имя приватного ключа
		 * @param array $attributes атрибуты токена
		 * @return iToken
		 * @throws \ErrorException
		 */
		public function createByPrivateKey(string $privateKey, array $attributes = []) : iToken;
	}