<?php

	/** @var array $rules правила инициализации сервисов */
	$rules = [
		'ExchangeAdminSettingsManager' => [
			'class' => 'UmiCms\Classes\Components\Exchange\AdminSettingsManager'
		],

		'ExchangeSettingsFactory' => [
			'class' => '\UmiCms\Classes\System\Utils\Exchange\Settings\Factory',
			'arguments' => [
				new ServiceReference('Registry'),
				new ServiceReference('DomainDetector'),
				new ServiceReference('LanguageDetector')
			]
		]
	];
