<?php
	namespace UmiCms\Classes\System\Controllers;

	/**
	 * Класс контроллера запроса json данных
	 * @package UmiCms\Classes\System\Controllers
	 */
	class JsonForceController extends XmlForceController implements iJsonForceController {

		/**
		 * Определяет доступно ли получение пользовательских данных
		 * @return bool
		 */
		protected function isEnabled() {
			return $this->request->isAdmin() || (bool) $this->config->get('router', 'jsonForce.enabled');
		}

		/** @inheritDoc */
		protected function handleRequest() {
			\def_module::isXSLTResultMode(true);
			$globalVariables = $this->moduleRouter->getGlobalVariables();
			$json = $this->translator->translateToJson($globalVariables);

			$this->buffer->contentType('application/json');
			$this->buffer->push($json);
			$this->buffer->option('generation-time', false);
			$this->buffer->end();
		}
	}