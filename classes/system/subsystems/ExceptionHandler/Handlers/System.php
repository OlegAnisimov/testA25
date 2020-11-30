<?php

	namespace UmiCms\Classes\System\Exception\Handler;

	use UmiCms\System\Response\iFacade as iResponseFacade;

	/**
	 * Системный обработчик исключений
	 * @package UmiCms\Classes\System\Exception\Handler
	 */
	class System extends Base {

		/** @var string ERROR_MESSAGE текст ошибки */
		const ERROR_MESSAGE = <<<ERROR
Произошла критическая ошибка. Скорее всего, потребуется участие разработчиков.  
Подробности по ссылке <a title="" target="_blank" href="https://errors.umi-cms.ru/17000/">17000</a>
ERROR;

		/** @var \mainConfiguration $config конфигурация */
		protected $config;

		/** @var iResponseFacade $responseFacade фасад для работы с буферами вывода */
		private $responseFacade;

		/**
		 * Конструктор
		 * @param \mainConfiguration $config конфигурация
		 * @param iResponseFacade $responseFacade фасад для работы с буферами вывода
		 */
		public function __construct(\mainConfiguration $config, iResponseFacade $responseFacade) {
			$this->config = $config;
			$this->responseFacade = $responseFacade;
		}

		/** @inheritDoc */
		public function handler($exception) {
			$isShowBacktrace = $this->config->get('debug', 'show-backtrace');

			if (!$isShowBacktrace && $exception instanceof \databaseException) {
				$exception = new \Exception(
					self::ERROR_MESSAGE,
					17000,
					$exception
				);
			}

			$buffer = $this->responseFacade->getCurrentBuffer();
			$buffer->status(500);
			$this->printTemplate($exception);
			$this->report($exception);
			$buffer->stop();
		}

		/** @inheritDoc */
		public function report($exception) {
			return $this->createCrashReport($exception->getMessage(), $exception->getTraceAsString());
		}

		/** @inheritDoc */
		public function createCrashReport($message, $trace) {
			$logExceptions = $this->config->get('debug', 'log-exceptions');

			if (!$logExceptions) {
				return false;
			}

			$logsDirectory = ERRORS_LOGS_PATH . '/exceptions/';

			if (!is_dir($logsDirectory)) {
				mkdir($logsDirectory, 0777, true);
			}

			try {
				$logger = new \umiLogger($logsDirectory);
				$logger->pushGlobalEnvironment();
				$logger->push($message);
				$logger->push($trace);
				$logger->save();
				return true;
			} catch (\Exception $e) {
				return false;
			}
		}
	}