<?php
	namespace UmiCms\Classes\System\Controllers;

	use UmiCms\System\Request\iFacade as iRequest;
	use UmiCms\System\Response\iFacade as iResponse;

	/**
	 * Интерфейс контроллера
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iController {

		/**
		 * Конструктор
		 * @param iRequest $request фасад запроса
		 * @param iResponse $response фасад ответа
		 */
		public function __construct(iRequest $request, iResponse $response);

		/**
		 * Устанавливает параметры роутера
		 * @param array $parameters параметры роутера
		 * @return $this
		 */
		public function setRouterParameters(array $parameters);

		/**
		 * Выполняет операцию
		 * @return mixed
		 * @throws \Exception
		 */
		public function execute();
	}