<?php


	/**
	 * Этот класс служит для управления полем объекта.
	 * Обрабатывает тип поля "Изображение"
	 */
	class umiObjectPropertyImgFile extends umiObjectPropertyFile {

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
			$result = $this->getConnection()
				->queryResult($selection)
				->setFetchType(IQueryResult::FETCH_ASSOC);
			$imageList = [];

			foreach ($result as $row) {
				$imageList[] = $this->mapImage($row);
			}

			return $this->filterBrokenFiles($imageList);
		}

		/**
		 * Возвращает список изображений из кэша
		 * @param array $cache
		 * @return array
		 */
		protected function getFromCache(array $cache) {
			$imageList = [];

			foreach ($cache as $row) {
				$imageList[] = $this->mapImage($row);
			}

			return $this->filterBrokenFiles($imageList);
		}

		/**
		 * Формирует изображение из его данных
		 * @param array $row данные изображения
		 * @return iUmiImageFile
		 */
		protected function mapImage(array $row) {
			$src = self::unescapeFilePath($row['src']);
			$image = new umiImageFile($src);
			$image->setId($row['id']);
			$image->setAlt($row['alt']);
			$image->setTitle($row['title']);
			$image->setOrder($row['ord']);
			return $image;
		}

		/** @inheritDoc */
		protected function saveValue() {
			$this->deleteCurrentRows();

			if (!is_array($this->value)) {
				return;
			}

			$value = getFirstValue($this->value);
			$value = ($value instanceof iUmiFile) ? $value : new umiImageFile($value);

			if ($value->getIsBroken()) {
				return;
			}

			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$connection = $this->getConnection();
			$src = $connection->escape('.' . $value->getFilePath(true));
			$alt = $connection->escape($value->getAlt());
			$title = $connection->escape($value->getTitle());
			$order = (int) $value->getOrder() ?: self::SINGLE_FILE_ORDER;
			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `src`, `alt`, `title`, `ord`) VALUES
($objectId, $fieldId, '$src', '$alt', '$title', $order)
SQL;
			$connection->query($query);
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$oldValue = $this->value;
			$newValue = array_filter(
				$newValue,
				function ($value) {
					$filePath = ($value instanceof iUmiImageFile) ? $value->getFilePath() : (string) $value;
					return @is_file($filePath);
				}
			);

			switch (true) {
				case empty($oldValue) && empty($newValue) : {
					return false;
				}
				case empty($oldValue) && !empty($newValue) : {
					return true;
				}
				case !empty($oldValue) && empty($newValue) : {
					return true;
				}
				default : {
					$oldValue = array_shift($oldValue);
					$oldFilePath = ($oldValue instanceof iUmiImageFile) ? $oldValue->getFilePath() : (string) $oldValue;

					$newValue = array_shift($newValue);
					$newFilePath = ($newValue instanceof iUmiImageFile) ? $newValue->getFilePath() : (string) $newValue;

					if ($oldFilePath !== $newFilePath) {
						return true;
					}

					$oldAlt = ($oldValue instanceof iUmiImageFile) ? $oldValue->getAlt() : '';
					$newAlt = ($newValue instanceof iUmiImageFile) ? $newValue->getAlt() : '';

					if ($oldAlt !== $newAlt) {
						return true;
					}

					$oldTitle = ($oldValue instanceof iUmiImageFile) ? $oldValue->getTitle() : '';
					$newTitle = ($newValue instanceof iUmiImageFile) ? $newValue->getTitle() : '';

					if ($oldTitle !== $newTitle) {
						return true;
					}

					$oldOrder = ($oldValue instanceof iUmiImageFile) ? $oldValue->getOrder() : self::SINGLE_FILE_ORDER;
					$newOrder = ($newValue instanceof iUmiImageFile) ? $newValue->getOrder() : self::SINGLE_FILE_ORDER;

					return $oldOrder !== $newOrder;
				}
			}
		}
	}
