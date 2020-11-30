<?php

	namespace UmiCms\System\Auth\AuthenticationRules;

	use UmiCms\Service;
	use UmiCms\System\Selector\iFactory as SelectorFactory;
	use UmiCms\System\Protection\iHashComparator;
	use UmiCms\System\Auth\PasswordHash\iAlgorithm;
	use UmiCms\System\Auth\PasswordHash\WrongAlgorithmException;

	/**
	 * Класс правила аутентификации пользователя по логину и токен авторизации
	 * @package UmiCms\System\Auth\AuthenticationRules
	 */
	class LoginAndToken extends Rule {

		/** @var string $login логин */
		private $login;

		/** @var string $token токен авторизации */
		private $token;

		/**
		 * Конструктор
		 * @param string $login логин
		 * @param string $token токен авторизации
		 * @param SelectorFactory $selectorFactory фабрика селекторов
		 * @param iHashComparator $hashComparator сравнитель хэшей
		 * @param iAlgorithm $algorithm алгоритм хеширования
		 */
		public function __construct($login, $token, 
				SelectorFactory $selectorFactory, 
				iHashComparator $hashComparator,
				iAlgorithm $algorithm) {
			$this->login = (string) $login;
			$this->token = (string) $token;
			$this->selectorFactory = $selectorFactory;
			$this->hashComparator = $hashComparator;
			$this->hashAlgorithm = $algorithm;
		}

		/** @inheritDoc */
		public function validate() {
			$login = $this->getLogin();
			try {
				$queryBuilder = $this->getQueryBuilder();
				$queryBuilder->option('return')->value(['id', 'auth_token']);
				$queryBuilder->where('login')->equals($login);
				$queryBuilder->where('is_activated')->equals(true);
				$queryBuilder->limit(0, 1);
				$queryResultSet = $queryBuilder->result();
			} catch (\Exception $e) {
				return false;
			}

			if (count($queryResultSet) === 0) {
				return false;
			}

			$correctToken = (string) $queryResultSet[0]['auth_token'];
			$algorithm = $this->getHashAlgorithm();
			$remoteAddress = Service::Request()->remoteAddress();
			$userAgent = Service::Request()->userAgent();
			$token = $algorithm::hash($this->getToken() . $remoteAddress . $userAgent);

			$hashComparator = $this->getHashComparator();
			if (!$hashComparator->equals($correctToken, $token)) {
				return false;
			}

			return (int) $queryResultSet[0]['id'];
		}

		/**
		 * Возвращает логин
		 * @return string
		 */
		private function getLogin() {
			return $this->login;
		}

		/**
		 * Возвращает токен авторизации
		 * @return string
		 */
		private function getToken() {
			return $this->token;
		}

		/**
		 * Возвращает алгоритм хеширования
		 * @return iAlgorithm
		 */
		private function getHashAlgorithm() {
			return $this->hashAlgorithm;
		}
	}
