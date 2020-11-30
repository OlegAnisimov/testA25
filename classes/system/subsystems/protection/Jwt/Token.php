<?php
	namespace UmiCms\System\Protection\Jwt;

	use UmiCms\System\Patterns\ArrayContainer;

	/**
	 * Класс Jwt токена
	 * @link https://jwt.io/introduction/
	 * @package UmiCms\System\Protection\Jwt
	 */
	class Token extends ArrayContainer implements iToken {

		/** @var string $privateKey приватный ключ для подписи запроса */
		private $privateKey;

		/** @inheritDoc */
		public function setPrivateKey(string $key) : iToken {
			$this->privateKey = $key;
			return $this;
		}

		/** @inheritDoc */
		public function getValue() : string {
			return $this->concat([
				$this->getHeader(),
				$this->getPayload(),
				$this->getSignature()
			]);
		}

		/**
		 * Возвращает заголовок
		 * @return string
		 */
		private function getHeader() : string {
			return $this->encode(json_encode([
				'alg' => 'RS256',
				'typ' => 'JWT'
			]));
		}
		/**
		 * Возвращает параметры запрашиваемых данных
		 * @return string
		 */
		private function getPayload() : string {
			return $this->encode(json_encode($this->getArrayCopy()));
		}

		/**
		 * Возвращает подпись
		 * @return string
		 * @throws \ErrorException
		 */
		private function getSignature() : string {
			$data = $this->concat([
				$this->getHeader(),
				$this->getPayload(),
			]);
			$binarySignature = '';
			$privateKey = $this->getPrivateKey();

			if (!openssl_sign($data, $binarySignature, $privateKey, 'SHA256')) {
				throw new \ErrorException(getLabel('label-error-bad-private-key'));
			}

			return $this->encode($binarySignature);
		}

		/**
		 * Кодирует строку
		 * @param string $string строка
		 * @return string
		 */
		private function encode(string $string) : string  {
			return base64_encode($string);
		}

		/**
		 * Склеивает массив в строку
		 * @param array $array массив
		 * @return string
		 */
		private function concat(array $array) : string {
			return implode('.', $array);
		}

		/**
		 * Возвращает приватный ключ
		 * @return string
		 * @throws \ErrorException
		 */
		private function getPrivateKey() : string {
			if (!$this->privateKey) {
				throw new \ErrorException('Google service account private key expected');
			}

			return $this->privateKey;
		}
	}