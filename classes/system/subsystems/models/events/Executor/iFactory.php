<?php
	namespace UmiCms\System\Events\Executor;

	use \iServiceContainer;
	use UmiCms\System\Events\iHandler;
	use UmiCms\System\Events\iExecutor;
	use UmiCms\System\Events\Executor\iModule as iModuleExecutor;

	/**
	 * Интерфейс фабрики исполнителей обработчиков событий
	 * @package UmiCms\System\Events\Executor
	 */
	interface iFactory {

		/** @var string SERVICE_NAME имя сервиса в UMI  */
		const SERVICE_NAME = 'EventHandlerExecutor';

		/**
		 * Конструктор
		 * @param iServiceContainer $serviceContainer контейнер сервисов
		 */
		public function __construct(iServiceContainer $serviceContainer);

		/**
		 * Создает исполнителя для обработчика событий
		 * @param iHandler $handler обработчик событий
		 * @return iExecutor
		 * @throws \Exception
		 * @throws \ErrorException
		 */
		public function createForHandler(iHandler $handler) : iExecutor;
	}