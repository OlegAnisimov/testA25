#!/usr/local/bin/php
<?php
	define('CRON', 'CLI');
	use UmiCms\Service;
	include_once __DIR__  . '/../../../standalone.php';

	$buffer = Service::Response()
		->getCliBuffer();

	if (!$argv || !isset($argv[1])) {
		$buffer->push('Первым параметром нужно передать путь до файла конфигурации пакера.' . PHP_EOL);
		$buffer->end();
	}

	$configFilePath = $argv[1];

	try {
		$packer = new Packer($configFilePath);
		$packer->setExporter(
			new xmlExporter(
				$packer->getConfig('package')
			)
		);
		$packer->run();
	} catch (Exception $e) {
		$buffer->push($e->getMessage() . PHP_EOL);
		$buffer->end();
	}