<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	use UmiCms\Classes\System\PageNum\iAgent;
	use \iServiceContainer as iServiceContainer;

	/**
	 * Класс фабрики агентов пагинации
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	class Factory implements iFactory {

		/** @var iServiceContainer $serviceContainer контейнер сервисов */
		private $serviceContainer;

		/** @inheritDoc */
		public function __construct(iServiceContainer $serviceContainer) {
			$this->serviceContainer = $serviceContainer;
		}

		/** @inheritDoc */
		public function createAdmin() : iAgent {
			return $this->serviceContainer->get('PageNumAgentAdmin');
		}

		/** @inheritDoc */
		public function createSite() : iAgent {
			return $this->serviceContainer->get('PageNumAgentSite');
		}

		/** @inheritDoc */
		public function createStream() : iAgent {
			return $this->serviceContainer->get('PageNumAgentStream');
		}

		/** @inheritDoc */
		public function createCommon() : iAgent {
			return $this->serviceContainer->get('PageNumAgentCommon');
		}

		/** @inheritDoc */
		public function createCustom(string $class) : iAgent {
			return $this->instanceCustom($class);
		}

		/**
		* Создает экземпляр кастомного агента пагинации
		* @param string $class класс кастомного алента пагинации
		* @return iAgent
		* @throws \ErrorException
		*/
		public function instanceCustom(string $class) : iAgent {
			try {
				$reflection = new \ReflectionClass($class);
			} catch (\ReflectionException $exception) {
				throw new \ErrorException(sprintf('Bad class given: "%s"', $class));
			}

			if (!$reflection->isInstantiable()) {
				throw new \ErrorException(sprintf('Not instantiable class given: "%s"', $class));
			}

			if (!$reflection->implementsInterface('UmiCms\Classes\System\PageNum\iAgent')) {
				throw new \ErrorException('Class must implement iAgent interface');
			}

			return new $class();
		}
	}