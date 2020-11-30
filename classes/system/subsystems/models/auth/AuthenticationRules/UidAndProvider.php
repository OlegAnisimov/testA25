<?php

	namespace UmiCms\System\Auth\AuthenticationRules;

	use UmiCms\System\Selector\iFactory as SelectorFactory;

	/**
	 * Класс правила аутентификации пользователя по идентификатору и названию провайдера данных пользователя (социальной сети)
	 * @package UmiCms\System\Auth\AuthenticationRules
	 */
	class UidAndProvider extends Rule {

		/** @var string $uid идентификатор пользователя в социальной сети */
		private $uid;

		/** string $provider название провайдера данных пользователя (социальной сети) */
		private $provider;

		/**
		 * Конструктор
		 * @param string $uid идентификатор пользователя в социальной сети
		 * @param string $provider название провайдера данных пользователя (социальной сети)
		 * @param SelectorFactory $selectorFactory фабрика селекторов
		 */
		public function __construct($uid, $provider, SelectorFactory $selectorFactory) {
			$this->uid = (string) $uid;
			$this->provider = (string) $provider;
			$this->selectorFactory = $selectorFactory;
		}

		/** @inheritDoc */
		public function validate() {
			$uid = $this->getUid();
			$provider = $this->getProvider();

			try {
				$queryBuilder = $this->getQueryBuilder();
				$queryBuilder->where('social_uid')->equals($uid);
				$queryBuilder->where('loginza')->equals($provider);
				$queryBuilder->where('is_activated')->equals(true);
				$queryResultSet = $queryBuilder->result();
			} catch (\Exception $e) {
				return false;
			}

			if (umiCount($queryResultSet) === 0) {
				return false;
			}

			$queryResultItem = array_shift($queryResultSet);
			return (int) $queryResultItem['id'];
		}

		/**
		 * Возвращает идентификатор пользователя в социальной сети
		 * @return string
		 */
		private function getUid() {
			return $this->uid;
		}

		/**
		 * Возвращает название провайдера данных пользователя (социальной сети)
		 * @return string
		 */
		private function getProvider() {
			return $this->provider;
		}
	}
