<?php

	namespace UmiCms\System\Data\Object\Property\Value;

	/**
	 * Этот класс служит для управления полем объекта.
	 * Обрабатывает тип поля "Набор файлов"
	 */
	class MultipleFile extends \umiObjectPropertyFile {

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		protected function loadValue() {
			$cache = $this->getPropData();

			if (isset($cache['file_val']) && !isEmptyArray($cache['file_val'])) {
				return $this->getFromCache($cache['file_val']);
			}

			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$selection = <<<SQL
	SELECT `id`, `src`, `title`, `ord`
	FROM `$tableName`
	WHERE `obj_id` = $objectId AND `field_id` = $fieldId;
SQL;

			$selectionResult = $this->getConnection()
				->queryResult($selection);
			$selectionResult->setFetchType(\IQueryResult::FETCH_ASSOC);

			if ($selectionResult->length() === 0) {
				return [];
			}

			$fileList = [];

			foreach ($selectionResult as $row) {
				$file = $this->mapFile($row);
				$fileList[$file->getFilePath()] = $file;
			}

			$fileList = $this->filterBrokenFiles($fileList);
			return $this->sortFileList($fileList);
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

			/** @var \iUmiFile[] $filteredValueList */
			$filteredValueList = array_filter($this->value, function ($value) {
				return ($value instanceof \iUmiFile && !$value->getIsBroken());
			});

			if (isEmptyArray($filteredValueList)) {
				return;
			}

			$tableName = $this->getTableName();
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `src`, `title`, `ord`) VALUES
SQL;
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$connection = $this->getConnection();

			foreach ($filteredValueList as $key => $value) {
				$src = $connection->escape('.' . $value->getFilePath(true));
				$ord = (int) $value->getOrder() ?: $this->getMaxOrder() + 1;
				$title = $connection->escape($value->getTitle());
				$query .= sprintf("(%d, %d, '%s', '%s', %d),", $objectId, $fieldId, $src, $title, $ord);
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

			/* @var \iUmiFile $oldValue */
			foreach ($oldValues as $oldValue) {
				$oldFilesPath[$oldValue->getFilePath()] = $oldValue;
				$oldFilesPath[$oldValue->getFilePath(true)] = $oldValue;
			}

			foreach ($newValue as $key => $value) {
				if (!$value instanceof \iUmiFile) {
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