<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iOutputBuffer as iBuffer;
	use UmiCms\System\Request\iFacade as iRequest;
	use UmiCms\System\Response\iFacade as iResponse;

	/**
	 * Класс абстрактного контроллера
	 * @package UmiCms\Classes\System\Controllers
	 */
	abstract class AbstractController implements iController {

		/** @var iRequest $request запрос */
		protected $request;

		/** @var iBuffer $buffer буффер ответа */
		protected $buffer;

		/** @var iResponse $response ответ */
		protected $response;

		/** @var array $parameters параметры роутера */
		protected $parameters = [];

		/** @inheritDoc */
		public function __construct(iRequest $request, iResponse $response) {
			$this->request = $request;
			$this->response = $response;
			$this->buffer = $response->getCurrentBuffer();
		}

		/** @inheritDoc */
		public function setRouterParameters(array $parameters) {
			$this->parameters = $parameters;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			$this->stubIfUpdating();
		}

		/**
		 * Показывает заглушку, если система обновляется
		 * @throws \Exception
		 */
		protected function stubIfUpdating() {
			if (isCmsUpdating()) {
				$this->buffer->crash('updating');
			}
		}

		/**
		 * Возвращает ответ
		 * @return iResponse
		 */
		protected function getResponse() {
			return $this->response;
		}

		/**
		 * Возвращает запрос
		 * @return iRequest
		 */
		protected function getRequest() {
			return $this->request;
		}

		/**
		 * Возвращает буффер вывода
		 * @return iBuffer
		 */
		protected function getBuffer() {
			return $this->buffer;
		}
	}