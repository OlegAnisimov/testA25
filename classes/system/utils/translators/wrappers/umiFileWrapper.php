<?php

	/** Класс xml транслятора (сериализатора) файла */
	class umiFileWrapper extends translatorWrapper {

		/**
		 * @inheritDoc
		 * @param iUmiFile $object
		 */
		public function translate($object) {
			return $this->translateData($object);
		}

		/**
		 * Преобразует файл с разметкой для последующей сериализации в xml
		 * @param iUmiFile $file файл
		 * @return array
		 */
		protected function translateData(iUmiFile $file) {
			$result = [
				'attribute:id' => $file->getId(),
				'attribute:path' => $file->getFilePath(),
				'attribute:size' => $file->getSize(),
				'attribute:ext' => $file->getExt(),
				'attribute:title' => $file->getTitle(),
				'attribute:ord' => $file->getOrder(),
				'attribute:folder_hash' => $file->getDirHash(),
				'attribute:file_hash' => $file->getPathHash(),
				'node:src' => $file->getFilePath(true)
			];

			if ($file instanceof iUmiImageFile) {
				$result['attribute:alt'] = $file->getAlt();
				$result['attribute:width'] = $file->getWidth();
				$result['attribute:height'] = $file->getHeight();
			}

			return $result;
		}
	}
