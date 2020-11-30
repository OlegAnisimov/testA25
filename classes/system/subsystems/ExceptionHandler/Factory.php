<?php

	namespace UmiCms\Classes\System\Exception\Handler;

	use UmiCms\Service;

	/**
	 * Фабрика обработчика исключений
	 * @package UmiCms\Classes\System\Exception\Handler
	 */
	class Factory implements iFactory {

		/** @var \mainConfiguration $config конфигурация */
		private $config;

		/**
		 * Конструктор
		 * @param \mainConfiguration $config конфигурация
		 */
		public function __construct(\mainConfiguration $config) {
			$this->config = $config;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function create() {
			$customHandler = $this->createCustomHandler();
			return ($customHandler instanceof iExceptionHandler) ? $customHandler : $this->createSystemHandler();
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function createCustomHandler() {
			$customExceptionHandler = $this->config->get('debug', 'custom-exception-handler');
			return class_exists($customExceptionHandler) ? new $customExceptionHandler() : false;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function createSystemHandler() {
			return Service::get('SystemExceptionHandler');
		}
	}