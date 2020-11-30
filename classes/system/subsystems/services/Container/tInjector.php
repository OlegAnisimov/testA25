<?php
	namespace UmiCms\System\Service\Container;

	/**
	 * Трейт инжектора контейнера сервисов
	 * @package UmiCms\System\Service\Container
	 */
	trait tInjector {

		/** @var \iServiceContainer|null $container контейнер сервисов*/
		private $container;

		/**
		 * Устанавливает контейнер сервисов
		 * @param \iServiceContainer $container
		 * @return $this
		 */
		public function setServiceContainer(\iServiceContainer $container) {
			$this->container = $container;
			return $this;
		}

		/**
		 * Возвращает контейнер сервисов
		 * @return \iServiceContainer
		 */
		public function getServiceContainer() {
			return $this->container;
		}
	}