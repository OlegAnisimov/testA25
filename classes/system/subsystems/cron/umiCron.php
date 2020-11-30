<?php

	use \UmiCms\Service;
	use \UmiCms\System\Events\iEventPointFactory as iEventFactory;

	/** Класс, позволяющий запускать действия по расписанию */
	class umiCron implements iUmiCron {

		protected $statFile, $buffer = [], $logs;

		/** @var string[] $modules список модулей, для которых нужно выполнить обработчики  */
		private $modules = [];

		/** @var string[] $methods список обработчиков, которые нужно выполнить */
		private $methods = [];

		/** @var iConfiguration $config конфигурация */
		private $config;

		/** @var iEventFactory $eventFactory фабрика событий */
		private $eventFactory;

		/** @inheritDoc */
		public function __construct(\iConfiguration $config = null, iEventFactory $eventFactory = null) {
			$this->config = $config ?: mainConfiguration::getInstance();
			$this->eventFactory = $eventFactory ?: Service::EventPointFactory();
			$this->statFile = $this->config->includeParam('system.runtime-cache') . 'cron';
		}

		/** @inheritDoc */
		public function __destruct() {
			$this->setLastCall();
		}

		/** @inheritDoc */
		public function run() {
			$lastCallTime = $this->getLastCall();
			$currCallTime = time();

			$result = $this->callEvent($lastCallTime, $currCallTime);
			$this->setLastCall();
			return $result;
		}

		/**
		 * Возвращает буффер
		 * @return Mixed буфер
		 */
		public function getBuffer() {
			return $this->buffer;
		}

		/** @inheritDoc */
		public function setModules(array $modules = []) : iUmiCron {
			$this->modules = $modules;
			return $this;
		}

		/** @inheritDoc */
		public function setMethods(array $methods = []) : iUmiCron {
			$this->methods = $methods;
			return $this;
		}

		/** @inheritDoc */
		public function getLogs() {
			return $this->logs;
		}

		/** @inheritDoc */
		public function getParsedLogs() {
			$result = '';
			$logs = $this->getLogs();

			if (count($logs['executed'])) {
				$result .= "Executed event handlers:\n";
				$result .= $this->getParsedLogsByArray($logs['executed']);
				$result .= "\n";
			}

			if (count($logs['failed'])) {
				$result .= "Failed event handlers:\n";
				$result .= $this->getParsedLogsByArray($logs['failed']);
				$result .= "\n";
			}

			if (count($logs['suppressed'])) {
				$result .= "suppressed event handlers:\n";
				$result .= $this->getParsedLogsByArray($logs['suppressed']);
				$result .= "\n";
			}

			return $result ?: 'No event handlers found';
		}

		protected function getParsedLogsByArray($arr) {
			$result = '';
			for ($i = 0; $i < umiCount($arr); $i++) {
				$eventPoint = $arr[$i];
				$module = $eventPoint->getCallbackModule();
				$method = $eventPoint->getCallbackMethod();
				$priority = $eventPoint->getPriority();
				$critical = $eventPoint->getIsCritical() ? 'critical' : 'not critial';

				$n = $i + 1;
				$result .= <<<END
	{$n}. {$module}::{$method} (umiEventPoint), priority = {$priority}, {$critical}

END;
			}
			return $result;
		}

		/**
		 * Возвращает время последнего запуска
		 * @return Int Time Stamp последнего запуска
		 */
		protected function getLastCall() {
			if (is_file($this->statFile)) {
				return filemtime($this->statFile);
			}

			$this->setLastCall();
			return time();
		}

		/**
		 * Меняет время последнего запуска на текущее
		 * @return bool true - в случае успеха, false - в случае ошибки
		 */
		protected function setLastCall() {
			if (!$res = @touch($this->statFile)) {
				$res = @touch($this->statFile);
			}
			return $res;
		}

		protected function callEvent($lastCallTime, $currCallTime) {
			static $counter = 0;

			$event = $this->eventFactory->create('cron', 'process');
			$event->setModules($this->modules);
			$event->setMethods($this->methods);
			$event->setParam('lastCallTime', $lastCallTime);
			$event->setParam('currCallTime', $currCallTime);
			$event->addRef('buffer', $this->buffer);
			$event->addRef('counter', $counter);

			$this->logs = $event->call();

			return $counter;
		}
	}

