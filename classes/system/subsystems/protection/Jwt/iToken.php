<?php
	namespace UmiCms\System\Protection\Jwt;

	use UmiCms\System\Patterns\iArrayContainer;

	/**
	 * Интерфейс Jwt токена
	 * @link https://jwt.io/introduction/
	 * @package UmiCms\System\Protection\Jwt
	 */
	interface iToken extends iArrayContainer {

		/**
		 * Устанавливает приватный ключ для подписи запросов
		 * @param string $key приватный ключ
		 * @return $this|iToken
		 */
		public function setPrivateKey(string $key) : iToken;

		/**
		 * Возвращает токен
		 * @return string
		 * @throws \ErrorException
		 */
		public function getValue() : string;

		/**
		 * @inheritDoc
		 * @return iToken
		 */
		public function set($key, $value);
	}