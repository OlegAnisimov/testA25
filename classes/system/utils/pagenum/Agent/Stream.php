<?php
	namespace UmiCms\Classes\System\PageNum\Agent;

	/**
	 * Класс агента пагинации протоколов
	 * @package UmiCms\Classes\System\PageNum\Agent
	 */
	class Stream extends Common implements iStream {

		/** @inheritDoc */
		protected function defineParameterKey() : void {
			$this->parameterKey = self::DEFAULT_KEY;
		}
	}