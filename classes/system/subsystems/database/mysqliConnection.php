<?php

	use UmiCms\Service;

	/** Класс соединения с базой данных MySQL через расширение mysqli */
	class mysqliConnection implements IConnection {

		/** @var string $host хост для подключения */
		private $host;

		/** @var string $userName логин для подключения */
		private $userName;

		/** @var string $password пароль для подключения */
		private $password;

		/** @var string $databaseName имя базы данных, с которой будет вестить работа */
		private $databaseName;

		/** @var null|int $port порт для подключения */
		private $port;

		/** @var null|string $socket сокет для подключения */
		private $socket;

		/** @var bool $isPersistent использовать ли постоянное соединение */
		private $isPersistent;

		/** @var bool $isCritical является ли соединение критичным для работы приложения */
		private $isCritical;

		/** @var mysqli $mysqliConnection соединение с базой данных */
		private $mysqliConnection;

		/** @var bool $isOpen открыто ли соединение */
		private $isOpen = false;

		/** @var int $queriesCount количество выполненных выборок за сессию */
		private $queriesCount = 0;

		/** @var null|iMysqlLogger $queriesLogger логгер запросов */
		private $queriesLogger;

		/** @const string PERSISTENT_CONNECTION_PREFIX префикс хоста для указания, что подключение будет постоянным */
		const PERSISTENT_CONNECTION_PREFIX = 'p:';

		/** @inheritDoc */
		public function __construct(
			$host,
			$userName,
			$password,
			$databaseName,
			$port = false,
			$persistent = false,
			$critical = true
		) {
			$this->host = $host;
			$this->userName = $userName;
			$this->password = $password;
			$this->databaseName = $databaseName;
			$this->port = is_numeric($port) ? $port : null;
			$this->isPersistent = $persistent;
			$this->isCritical = $critical;
		}

		/**
		 * Устанавливает сокет для подключени
		 * @param string $socket сокет
		 */
		public function setSocket($socket) {
			$this->socket = $socket;
		}

		/** @inheritDoc */
		public function setLogger(iMysqlLogger $mysqlLogger) {
			$this->queriesLogger = $mysqlLogger;
		}

		/** @inheritDoc */
		public function open() {
			if ($this->isOpen()) {
				return true;
			}

			try {
				$this->mysqliConnection = new mysqli(
					$this->isPersistent ? self::PERSISTENT_CONNECTION_PREFIX . $this->host : $this->host,
					$this->userName,
					$this->password,
					$this->databaseName,
					$this->port,
					$this->socket
				);

				if ($this->errorOccurred()) {
					throw new Exception($this->errorDescription());
				}

				$this->initConnection();
			} catch (Exception $e) {
				return $this->isCritical ? $this->makeFatalError() : false;
			}

			$this->isOpen = true;
			return true;
		}

		/** @inheritDoc */
		public function close() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				$this->mysqliConnection->close();
				$this->isOpen = false;
			}
		}

		/** @inheritDoc */
		public function query($queryString) {
			if ((!$this->isOpen() || !$this->isCorrectConnection()) && !$this->open()) {
				return false;
			}

			if (!is_string($queryString)) {
				throw new Exception('Query string expected');
			}

			$queryString = $this->prepareQueryString($queryString);

			$result = $this->mysqliQuery($queryString);
			++$this->queriesCount;

			if ($this->errorOccurred()) {
				throw new databaseException($this->errorDescription($queryString), $this->mysqliConnection->errno);
			}

			return $result;
		}

		/** @inheritDoc */
		public function startTransaction($comment = '') {
			$comment = (!is_string($comment)) ? '' : $this->escape($comment);
			$command = sprintf('START TRANSACTION /* %s */', $comment);
			$this->query($command);
			return $this;
		}

		/** @inheritDoc */
		public function commitTransaction() {
			$this->query('COMMIT');
			return $this;
		}

		/** @inheritDoc */
		public function rollbackTransaction() {
			$this->query('ROLLBACK');
			return $this;
		}

		/** @inheritDoc */
		public function getQueriesCount() {
			return $this->queriesCount;
		}

		/** @inheritDoc */
		public function queryResult($queryString) {
			$result = $this->query($queryString);
			return $this->isCorrectQueryResult($result) ? new mysqliQueryResult($result) : null;
		}

		/** @deprecated alias */
		public function errorOccured() {
			return $this->errorOccurred();
		}

		/** @inheritDoc */
		public function errorOccurred() {
			if ($this->isCorrectConnection()) {
				return $this->mysqliConnection->error !== '';
			}
		}

		/** @inheritDoc */
		public function errorDescription($sqlQuery = null) {
			if ($this->isCorrectConnection() && $this->errorOccurred()) {
				$errorMessage = $this->mysqliConnection->error;
				return is_string($sqlQuery) ? $errorMessage . ' in query: ' . $sqlQuery : $errorMessage;
			}
		}

		/** @inheritDoc */
		public function isOpen() {
			return $this->isOpen;
		}

		/** @inheritDoc */
		public function escape($input) {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->real_escape_string($input);
			}

			return addslashes($input);
		}

		/** @inheritDoc */
		public function getConnectionInfo() {
			return [
				'host' => $this->host,
				'port' => $this->port,
				'user' => $this->userName,
				'password' => $this->password,
				'dbname' => $this->databaseName,
				'link' => $this->mysqliConnection,
				'socket' => $this->socket
			];
		}

		/** @inheritDoc */
		public function insertId() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->insert_id;
			}
			return 0;
		}

		/** @inheritDoc */
		public function errorNumber() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->connect_errno;
			}
			return 0;
		}

		/** @inheritDoc */
		public function getServerInfo() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->server_info;
			}
			return null;
		}

		/** @inheritDoc */
		public function errorMessage() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->error;
			}
			return '';
		}

		/** @inheritDoc */
		public function affectedRows() {
			if ($this->isOpen() && $this->isCorrectConnection()) {
				return $this->mysqliConnection->affected_rows;
			}
			return 0;
		}

		/** @inheritDoc */
		public function isDuplicateKey(databaseException $exception) {
			return $exception->getCode() === self::DUPLICATE_KEY_ERROR_CODE;
		}

		/**
		 * Возвращает логгер запросов
		 * @return null|iMysqlLogger
		 */
		private function getLogger() {
			return $this->queriesLogger;
		}

		/** Устанавливает настройки для подключения */
		private function initConnection() {
			$queryList = mainConfiguration::getInstance()
				->get('connections', 'core.init.query', [], true);

			if (!is_array($queryList)) {
				return;
			}

			$count = 0;
			foreach ($queryList as $query) {
				if (!$this->validateQuery($query)) {
					continue;
				}

				$this->mysqliQuery($query);
				$count++;
			}

			$this->queriesCount += $count;
		}

		/**
		 * Корректно ли текущее соединение с базой данных
		 * @return bool
		 */
		private function isCorrectConnection() {
			return $this->mysqliConnection instanceof mysqli;
		}

		/**
		 * Корректен ли результат выборки
		 * @param mixed $result
		 * @return bool
		 */
		private function isCorrectQueryResult($result) {
			return $result instanceof mysqli_result;
		}

		/**
		 * Подготавливает и возвращает строку запроса к выполнению
		 * @param string $queryString строка запроса
		 * @return string
		 */
		private function prepareQueryString($queryString) {
			return trim($queryString, " \t\n");
		}

		/**
		 * Генерирует фатальную ошибку работы с базой,
		 * приводящую к остановке работы приложения
		 */
		private function makeFatalError() {
			Service::Response()
				->getCurrentBuffer()
				->crash('mysql_failed');
		}

		/**
		 * Выполняет выборку и возвращает результат,
		 * при наличии логгера - логгирует запрос
		 * @param string $queryString строка запроса
		 * @return bool|mysqli_result
		 */
		private function mysqliQuery($queryString) {
			if (!$this->isCorrectConnection()) {
				return false;
			}

			$logger = $this->getLogger();

			if ($logger === null) {
				return $this->mysqliConnection->query($queryString);
			}

			if ($logger instanceof iMysqlLogger) {
				$logger->runTimer();
				$queryResult = $this->mysqliConnection->query($queryString);
				$elapsedTime = $logger->getTimer();
				$message = $logger->getMessage($queryString, $elapsedTime);
				$logger->log($message);
				return $queryResult;
			}
		}

		/** 
		 * Валидирует SQL запрос
		 * @param string $query строка запроса для валидации
		 * @return bool
		 */
		private function validateQuery($query) : bool {
			$disableCommandList = ['DELETE', 'DROP', 'TRUNCATE', 'ALTER', 'SELECT', 'UPDATE', 'INSERT', 'CREATE'];
			$wordList = explode(' ', $query);
			if (count($wordList) < 2) {
				return false;
			}

			foreach ($wordList as $n => $word) {
				$word = strtoupper($word);
				if ($n == 0 && $word != 'SET') {
					return false;
				}

				if (in_array($word, $disableCommandList)) {
					return false;
				}
			}

			return true;
		}

		/** @deprecated */
		public function clearCache() {
			return false;
		}
	}
