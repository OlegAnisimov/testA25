<?php

	use UmiCms\Service;

	/** Тип экспорта каталога товаров в формате Яндекс.Маркет (YML) */
	class YMLExporter extends umiExporter {

		/** @inheritDoc */
		public function setOutputBuffer() {
			$buffer = Service::Response()
				->getCurrentBuffer();
			$buffer->charset($this->encoding);
			$buffer->contentType('text/xml');
			return $buffer;
		}

		/** @inheritDoc */
		public function export($exportList, $ignoreList) {
			$id = getRequest('param0');
			$dirName = $this->getExportPath();

			if (!file_exists($dirName . $id . 'el')) {
				$labelNoInformation = getLabel('label-errors-no-information');
				$message = <<<HTML
<a href="$labelNoInformation" target="blank">$labelNoInformation</a>
HTML;
				throw new publicException($message);
			}

			$elementsToExport = unserialize(file_get_contents($dirName . $id . 'el'));
			$xml = $dirName . $id . '.xml';

			if (file_exists($xml)) {
				unlink($xml);
			}

			$currentDate = date('Y-m-d H:i');
			$encoding = $this->encoding;
			$ymlHeader = <<<XML
<?xml version="1.0" encoding="$encoding"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="$currentDate">
	<shop>
XML;
			file_put_contents($xml, $ymlHeader);

			if (file_exists($dirName . 'shop' . $id)) {
				file_put_contents($xml, file_get_contents($dirName . 'shop' . $id), FILE_APPEND);
			}

			file_put_contents($xml, '<platform>UMI.CMS</platform>', FILE_APPEND);

			if (file_exists($dirName . 'currencies')) {
				file_put_contents($xml, file_get_contents($dirName . 'currencies'), FILE_APPEND);
			}

			if (file_exists($dirName . 'categories' . $id)) {
				file_put_contents($xml, '<categories>', FILE_APPEND);
				$categories = unserialize(file_get_contents($dirName . 'categories' . $id));

				$catEventPoint = new umiEventPoint('yml_export_categories');
				$catEventPoint->setMode('before');
				$catEventPoint->setParam('id', $id);
				$catEventPoint->addRef('categories', $categories);
				def_module::setEventPoint($catEventPoint);

				foreach ($categories as $categoryId => $name) {
					file_put_contents($xml, $name, FILE_APPEND);
				}

				file_put_contents($xml, '</categories>', FILE_APPEND);
			}

			if (file_exists($dirName . 'delivery-options' . $id)) {
				file_put_contents($xml, file_get_contents($dirName . 'delivery-options' . $id), FILE_APPEND);
			}

			file_put_contents($xml, '<offers>', FILE_APPEND);

			foreach ($elementsToExport as $fileId) {
				$filePath = $dirName . $fileId . '.txt';

				if (is_file($filePath)) {
					file_put_contents($xml, file_get_contents($filePath), FILE_APPEND);
				}
			}

			file_put_contents($xml, '</offers></shop></yml_catalog>', FILE_APPEND);
			$result = file_get_contents($xml);

			if (mb_convert_case($encoding, MB_CASE_LOWER) === 'windows-1251') {
				return mb_convert_encoding($result, 'CP1251', 'UTF-8');
			}

			return $result;
		}

		/** @inheritDoc */
		protected function getExportPath() {
			return SYS_TEMP_PATH . '/yml/';
		}
	}
