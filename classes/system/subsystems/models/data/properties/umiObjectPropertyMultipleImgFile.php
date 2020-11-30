<?php

	use UmiCms\Service;

	/**
	 * Этот класс служит для управления полем объекта.
	 * Обрабатывает тип поля "Набор изображений"
	 */
	class umiObjectPropertyMultipleImgFile extends umiObjectPropertyImgFile {

		/**
		 * @inheritDoc
		 * @throws Exception
		 */
		protected function loadValue() {
			$cache = $this->getPropData();

			if (isset($cache['img_val']) && is_array($cache['img_val'])) {
				return $this->getFromCache($cache['img_val']);
			}

			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$selection = <<<SQL
	SELECT `id`, `src`, `alt`, `title`, `ord`
	FROM `$tableName`
	WHERE `obj_id` = $objectId AND `field_id` = $fieldId;
SQL;
			$selectionResult = $this->getConnection()
				->queryResult($selection);
			$selectionResult->setFetchType(IQueryResult::FETCH_ASSOC);

			if ($selectionResult->length() == 0) {
				return [];
			}

			$imageList = [];

			foreach ($selectionResult as $row) {
				$image = $this->mapImage($row);
				$imageList[$image->getFilePath()] = $image;
			}

			$imageList = $this->filterBrokenFiles($imageList);
			return $this->sortFileList($imageList);
		}

		/** @inheritDoc */
		protected function getFromCache(array $cache) {
			return $this->sortFileList(parent::getFromCache($cache));
		}

		/** @inheritDoc */
		protected function saveValue() {
			$this->deleteCurrentRows();

			if (!is_array($this->value)) {
				return;
			}

			/** @var iUmiImageFile[] $filteredValueList */
			$filteredValueList = array_filter($this->value, function ($value) {
				return ($value instanceof iUmiImageFile && !$value->getIsBroken());
			});

			if (isEmptyArray($filteredValueList)) {
				return;
			}

			$tableName = $this->getTableName();
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `src`, `alt`, `title`, `ord`) VALUES
SQL;
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$connection = $this->getConnection();

			foreach ($filteredValueList as $key => $value) {
				$src = $connection->escape('.' . $value->getFilePath(true));
				$alt = $connection->escape($value->getAlt());
				$title = $connection->escape($value->getTitle());
				$ord = (int) $value->getOrder() ?: $this->getMaxOrder() + 1;
				$query .= sprintf("(%d, %d, '%s', '%s', '%s', %d),", $objectId, $fieldId, $src, $alt, $title, $ord);
			}

			$query = rtrim($query, ',') . ';';
			$connection->query($query);
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$oldValues = $this->value;

			if (umiCount($newValue) !== umiCount($oldValues)) {
				return true;
			}

			$oldFilesPath = [];

			/* @var iUmiImageFile $oldValue */
			foreach ($oldValues as $oldValue) {
				$oldFilesPath[$oldValue->getFilePath()] = $oldValue;
				$oldFilesPath[$oldValue->getFilePath(true)] = $oldValue;
			}

			foreach ($newValue as $key => $value) {
				if (!$value instanceof iUmiImageFile) {
					continue;
				}

				if (isset($oldFilesPath[$value->getFilePath()])) {
					$oldValue = $oldFilesPath[$value->getFilePath()];
				} else {
					return true;
				}

				if ($value->getFilePath() !== $oldValue->getFilePath()) {
					return true;
				}

				if ($value->getAlt() !== $oldValue->getAlt()) {
					return true;
				}

				if ($value->getTitle() !== $oldValue->getTitle()) {
					return true;
				}

				if ($value->getOrder() !== $oldValue->getOrder()) {
					return true;
				}
			}

			return false;
		}
	}
