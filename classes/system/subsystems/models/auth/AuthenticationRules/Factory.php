<?php

	namespace UmiCms\System\Auth\AuthenticationRules;

	use UmiCms\System\Auth\PasswordHash\iAlgorithm;
	use UmiCms\System\Selector\iFactory as SelectorFactory;
	use UmiCms\System\Protection\iHashComparator;

	/**
	 * Класс фабрики правил аутентификации
	 * @package UmiCms\System\Auth\AuthenticationRules
	 */
	class Factory implements iFactory {

		/** iAlgorithm $hashAlgorithm алгоритм хеширования паролей */
		private $hashAlgorithm;

		/** SelectorFactory $selectorFactory фабрика селекторов */
		private $selectorFactory;

		/** @var iHashComparator $hashComparator сравнитель хэшей */
		private $hashComparator;

		/** @inheritDoc */
		public function __construct(iAlgorithm $algorithm, SelectorFactory $selectorFactory,
			iHashComparator $hashComparator) {
			$this->hashAlgorithm = $algorithm;
			$this->selectorFactory = $selectorFactory;
			$this->hashComparator = $hashComparator;
		}

		/** @inheritDoc */
		public function createByLoginAndPassword($login, $password) {
			$hashAlgorithm = $this->getHashAlgorithm();
			$queryBuilder = $this->getQueryBuilder();
			return new LoginAndPassword($login, $password, $hashAlgorithm, $queryBuilder, $this->getHashComparator());
		}

		/** @inheritDoc */
		public function createByLoginAndHash($login, $hash) {
			$queryBuilder = $this->getQueryBuilder();
			return new LoginAndHash($login, $hash, $queryBuilder, $this->getHashComparator());
		}

		/** @inheritDoc */
		public function createByActivationCode($activationCode) {
			$queryBuilder = $this->getQueryBuilder();
			return new ActivationCode($activationCode, $queryBuilder);
		}

		/** @inheritDoc */
		public function createByLoginAndProvider($login, $provider) {
			$queryBuilder = $this->getQueryBuilder();
			return new LoginAndProvider($login, $provider, $queryBuilder);
		}

		/** @inheritDoc */
		public function createByUserId($userId) {
			$queryBuilder = $this->getQueryBuilder();
			return new UserId($userId, $queryBuilder);
		}

		/** @inheritDoc */
		public function createByFakeUser($userId) {
			$queryBuilder = $this->getQueryBuilder();
			return new FakeUser($userId, $queryBuilder);
		}

		/** @inheritDoc */
		public function createByLoginAndToken($login, $token) {
			$queryBuilder = $this->getQueryBuilder();
			$hashComparator = $this->getHashComparator();
			$hashAlgorithm = $this->getHashAlgorithm();
			return new LoginAndToken($login, $token, $queryBuilder, $hashComparator, $hashAlgorithm);
		}

		/** @inheritDoc */
		public function createByUidAndProvider($uid, $provider) {
			$queryBuilder = $this->getQueryBuilder();
			return new UidAndProvider($uid, $provider, $queryBuilder);
		}

		/**
		 * Возвращает алгоритм хеширования паролей
		 * @return iAlgorithm
		 */
		private function getHashAlgorithm() {
			return $this->hashAlgorithm;
		}

		/**
		 * Возвращает конструктор запросов к бд
		 * @return SelectorFactory
		 */
		private function getQueryBuilder() {
			return $this->selectorFactory;
		}

		/**
		 * Возвращает сравнителя хэшей
		 * @return iHashComparator
		 */
		private function getHashComparator() {
			return $this->hashComparator;
		}
	}
