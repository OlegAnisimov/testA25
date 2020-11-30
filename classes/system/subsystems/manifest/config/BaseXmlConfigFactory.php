<?php

	/** Фабрика менеджеров конфигураций в формате xml файлов */
	class BaseXmlConfigFactory implements iBaseXmlConfigFactory {

		/** @inheritDoc */
		public function create($configPath) {
			return new baseXmlConfig($configPath);
		}
	}
