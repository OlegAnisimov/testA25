<?php

	namespace UmiCms\Classes\System\Exception\Handler;

	/**
	 * Родительский класс обработчика исключений
	 * @package UmiCms\Classes\System\Exception\Handler
	 */
	abstract class Base implements iExceptionHandler {

		/** @var string $templateFile Шаблон для вывода исключений */
		protected $templateFile;

		/** @inheritDoc */
		abstract public function report($exception);

		/** @inheritDoc */
		abstract public function handler($exception);

		/** @inheritDoc */
		abstract public function createCrashReport($message, $trace);

		/**
		 * @inheritDoc
		 * @throws \Exception если шаблон не существует
		 */
		public function setExceptionTemplate($file) {
			if (!file_exists($file)) {
				throw new \Exception('Exception template not exists ' . $file);
			}

			$this->templateFile = $file;
		}

		/** @inheritDoc */
		public function getErrorContent($error) {
			$errorPath = $this->getErrorFilePath($error);
			$errorPath = is_file($errorPath) ? $errorPath : $this->getErrorFilePath();
			return file_get_contents($errorPath);
		}

        /**
         * Возвращает путь до шаблона ошибки
         * @param string $error ошибка
         * @return string
         */
        private function getErrorFilePath($error = 'default') {
            return SYS_ERRORS_PATH . "$error.html";
        }

		/**
		 * Возвращает путь до шаблона для вывода исключений.
		 * Устанавливает default шаблон, если он еще не был задан.
		 * @return string
		 * @throws \Exception
		 */
		protected function getExceptionTemplate() {
			if (!file_exists($this->templateFile)) {
				$this->setExceptionTemplate(SYS_ERRORS_PATH . 'exception.html.php');
			}

			return $this->templateFile;
		}

		/**
		 * Выводит сообщение об исключении
		 * @param \Exception|\Error $exception Исключение
		 * @throws \Exception
		 */
		protected function printTemplate($exception) {
			$templateException = new \stdClass();
			$templateException->code = $exception->getCode();
			$templateException->message = $exception->getMessage();
			$templateException->type = get_class($exception);

			if (DEBUG_SHOW_BACKTRACE) {
				$templateException->trace = $exception->getTrace();
				$templateException->traceAsString = $exception->getTraceAsString();
			}

			require $this->getExceptionTemplate();
		}
	}