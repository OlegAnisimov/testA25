<?php
	namespace UmiCms\System\Protection\Jwt\Token;

	use UmiCms\System\Protection\Jwt\Token;
	use UmiCms\System\Protection\Jwt\iToken;
	use UmiCms\System\Protection\PrivateKeys\iBunch;

	/**
	 * Класс фабрики jwt токенов
	 * @link https://jwt.io/introduction/
	 * @package UmiCms\System\Protection\Jwt\Token
	 */
	class Factory implements iFactory {

		/** @var iBunch $certificatesBox связка ключей */
		private $privateKeysBunch;

		/** @inheritDoc */
		public function __construct(iBunch $privateKeysBunch) {
			$this->privateKeysBunch = $privateKeysBunch;
		}

		/** @inheritDoc */
		public function create(array $attributes = []) : iToken {
			return new Token($attributes);
		}

		/** @inheritDoc */
		public function createByPrivateKey(string $privateKey, array $attributes = []) : iToken {
			$privateKey = $this->privateKeysBunch->get($privateKey);
			return $this->create($attributes)
				->setPrivateKey($privateKey);
		}
	}