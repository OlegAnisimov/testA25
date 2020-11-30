<?php

	namespace UmiCms\System\Events;

	/**
	 * Класс фабрики событий
	 * @package UmiCms\System\Events
	 */
	class EventPointFactory implements iEventPointFactory {

		/** @inheritDoc */
		public function create($id, $mode = 'process', array $moduleList = []) {
			$eventPoint = new \umiEventPoint($id);
			return $eventPoint->setMode($mode)
				->setModules($moduleList);
		}
	}
