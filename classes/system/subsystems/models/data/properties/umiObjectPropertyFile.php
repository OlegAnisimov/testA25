<?php

	use UmiCms\Service;

	/**
	 * Этот класс служит для управления полем объекта.
	 * Обрабатывает тип поля "Файл"
	 */
	class umiObjectPropertyFile extends umiObjectProperty {

		/** @ int SINGLE_FILE_ORDER индекс порядка отображения для одиночного файла */
		const SINGLE_FILE_ORDER = 1;

		/**
		 * @inheritDoc
		 * @throws Exception
		 */
		protected function loadValue() {
			$fileList = [];
			$cache = $this->getPropData();

			if (isset($cache['file_val']) && !isEmptyArray($cache['file_val'])) {
				return $this->getFromCache($cache['file_val']);
			}

			$connection = $this->getConnection();
			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();

			$selection =<<<SQL
SELECT `id`, `src`, `title`, `ord`
FROM `$tableName`
WHERE `obj_id` = $objectId AND `field_id` = $fieldId;
SQL;
			$result = $connection
				->queryResult($selection)
				->setFetchType(IQueryResult::FETCH_ASSOC);

			foreach ($result as $row) {
				$fileList[] = $this->mapFile($row);
			}

			return $this->filterBrokenFiles($fileList);
		}

		/**
		 * Возвращает список файлов из кэша
		 * @param array $cache кэш
		 * @return array
		 */
		protected function getFromCache(array $cache) {
			$imageList = [];

			foreach ($cache as $row) {
				$imageList[] = $this->mapFile($row);
			}

			return $this->filterBrokenFiles($imageList);
		}

		/**
		 * Формирует файл из его данных
		 * @param array|string $row данные файла
		 * @return iUmiFile
		 */
		protected function mapFile($row) {
			$file = $this->createSecureFile(self::unescapeFilePath($row['src']));
			$file->setId($row['id']);
			$file->setTitle($row['title']);
			$file->setOrder($row['ord']);

			return $file;
		}

		/**
		 * Отфильтровывает несуществующие файлы
		 * @param iUmiFile[] $fileList список файлов
		 * @return array
		 */
		protected function filterBrokenFiles(array $fileList) {
			$isAdminMode = Service::Request()->isAdmin();
			return array_filter($fileList, function($file) use ($isAdminMode) {
				/** @var iUmiFile $file */
				return !$file->getIsBroken() || $isAdminMode;
			});
		}

		/** @inheritDoc */
		protected function saveValue() {
			$this->deleteCurrentRows();

			if (!is_array($this->value)) {
				return;
			}

			$value = getFirstValue($this->value);
			$value = ($value instanceof iUmiFile) ? $value : $this->createSecureFile($value);

			if ($value->getIsBroken()) {
				return;
			}

			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$connection = $this->getConnection();
			$src = $connection->escape('.' . $value->getFilePath(true));
			$title = $connection->escape($value->getTitle());
			$order = (int) $value->getOrder() ?: self::SINGLE_FILE_ORDER;

			$query = <<<SQL
INSERT INTO `$tableName` (`obj_id`, `field_id`, `src`, `title`, `ord`) VALUES
($objectId, $fieldId, '$src', '$title', $order)
SQL;
			$connection->query($query);
		}

		/**
		 * Сортирует список файлов
		 * @param iUmiFile[]|iUmiImageFile[] $fileList список файлов
		 * @return iUmiFile[]|iUmiImageFile[]
		 */
		protected function sortFileList(array $fileList) {
			usort($fileList, function($firstFile, $secondFile) {
				/** @var iUmiFile|iUmiImageFile $firstFile */
				/** @var iUmiFile|iUmiImageFile $secondFile */
				if ($firstFile->getOrder() === $secondFile->getOrder()) {
					return 0;
				}

				return ($firstFile->getOrder() < $secondFile->getOrder()) ? -1 : 1;
			});

			return $fileList;
		}

		/**
		 * Возвращает максимальное значение индекса сортировки
		 * среди файлов для текущего поля объекта
		 * @return int
		 * @throws databaseException
		 */
		public function getMaxOrder() {
			$tableName = $this->getTableName();
			$objectId = (int) $this->getObjectId();
			$fieldId = (int) $this->getFieldId();
			$query = <<<SQL
SELECT max(`ord`) as ord FROM `$tableName` WHERE `obj_id` = $objectId AND `field_id` = $fieldId;
SQL;
			$row = $this->getConnection()
				->queryResult($query)
				->setFetchType(IQueryResult::FETCH_ASSOC)
				->fetch();
			return (int) $row['ord'];
		}

		/** @inheritDoc */
		protected function isNeedToSave(array $newValue) {
			$oldValue = $this->prepareValue($this->value);
			$newValue = $this->prepareValue($newValue);

			$wasPathChanged = $this->getFilePathFromValue($oldValue) !== $this->getFilePathFromValue($newValue);
			$wasTitleChanged = $this->getTitleFromValue($oldValue) !== $this->getTitleFromValue($newValue);

			return $wasPathChanged || $wasTitleChanged;
		}

		/**
		 * Подготавливает переданное значение
		 * @param array|mixed $value значение поля
		 * @return iUmiFile|string
		 */
		private function prepareValue($value) {
			return isset($value[0]) ? $value[0] : '';
		}

		/**
		 * Возвращает путь к файлу из переданного значения
		 * @param array|mixed $value значение поля
		 * @return mixed|string
		 */
		private function getFilePathFromValue($value) {
			return ($value instanceof iUmiFile) ? $value->getFilePath() : $value;
		}

		/**
		 * Возвращает title файла из переданного значения
		 * @param array|mixed $value значение поля
		 * @return mixed|string
		 */
		private function getTitleFromValue($value) {
			return ($value instanceof iUmiFile) ? $value->getTitle() : $value;
		}

		/**
		 * Возвращает безопасные (не php) файл
		 * @param string $path
		 * @return iUmiFile
		 */
		private function createSecureFile($path) {
			return Service::FileFactory()->createSecure($path);
		}
	}
