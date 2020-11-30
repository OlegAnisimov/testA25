<?php
	namespace UmiCms\System\Hierarchy\Template;

	/**
	 * Класс фабрики шаблонов
	 * @todo: реализовать создание шаблонов сайта и внедрить данный класс в "коллекции" доменов
	 * @package UmiCms\System\Hierarchy\Template
	 */
	class Factory implements iFactory {

		use \UmiCms\System\Service\Container\tInjector;

		/** @inheritDoc */
		public function createDummy() {
			return clone $this->getServiceContainer()
				->get('DummyTemplate');
		}
	}