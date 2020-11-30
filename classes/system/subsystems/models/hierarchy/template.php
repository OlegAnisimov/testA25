<?php

	use UmiCms\Service;

	/** Предоставляет доступ к свойствам шаблона дизайна */
	class template extends umiEntinty implements iTemplate {

		protected $store_type = 'template';

		private $name;

		private $filename;

		private $type;

		private $title;

		private $domain_id;

		private $lang_id;

		private $is_default;

		private $templatesDirectory;

		private $filePath;

		protected $resourcesDirectory;

		/** @inheritDoc */
		public function getName() {
			return $this->name;
		}

		/** @inheritDoc */
		public function getFilename() {
			return $this->filename;
		}

		/** @inheritDoc */
		public function getResourcesDirectory($httpMode = false) {
			if ($httpMode) {
				return $this->resourcesDirectory ? '/templates/' . $this->getName() . '/' : '/';
			}

			return $this->resourcesDirectory;
		}

		/** @inheritDoc */
		public function getCustomsDirectory() {
			$resourcesDirectory = $this->getResourcesDirectory();

			if (!is_dir($resourcesDirectory)) {
				return false;
			}

			$customDirectory = $resourcesDirectory . "classes/components/";
			$customDirectory = is_dir($customDirectory) ? $customDirectory : ($resourcesDirectory . "classes/modules/");

			return is_dir($customDirectory) ? $customDirectory : false;
		}

		/** @inheritDoc */
		public function getTemplatesDirectory() {
			return $this->templatesDirectory;
		}

		/** @inheritDoc */
		public function getFilePath() {
			return $this->filePath;
		}

		/** @inheritDoc */
		public function getType() {
			return $this->type;
		}

		/** @inheritDoc */
		public function getTitle() {
			return $this->title;
		}

		/** @inheritDoc */
		public function getDomainId() {
			return $this->domain_id;
		}

		/** @inheritDoc */
		public function getLangId() {
			return $this->lang_id;
		}

		/** @inheritDoc */
		public function getIsDefault() {
			return $this->is_default;
		}

		/** @inheritDoc */
		public function setName($name) {
			if ($this->getName() != $name) {
				$this->name = $name;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setFilename($filename) {
			if ($this->getFilename() != $filename) {
				$this->filename = $filename;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setTitle($title) {
			if ($this->getTitle() != $title) {
				$this->title = $title;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setType($type) {
			if ($this->getType() != $type) {
				$this->type = $type;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function setDomainId($domainId) {
			if (!Service::DomainCollection()->isExists($domainId)) {
				return false;
			}

			if ($this->getDomainId() != $domainId) {
				$this->domain_id = (int) $domainId;
				$this->setIsUpdated();
			}

			return true;
		}

		/** @inheritDoc */
		public function setLangId($langId) {
			if (!Service::LanguageCollection()->isExists($langId)) {
				return false;
			}

			if ($this->getLangId() != $langId) {
				$this->lang_id = (int) $langId;
				$this->setIsUpdated();
			}

			return true;
		}

		/** @inheritDoc */
		public function setIsDefault($isDefault) {
			$isDefault = (bool) $isDefault;

			if ($this->getIsDefault() != $isDefault) {
				$this->is_default = $isDefault;
				$this->setIsUpdated();
			}
		}

		/** @inheritDoc */
		public function getFileExtension() {
			switch ($this->getType()) {
				case 'xslt' : {
					return 'xsl';
				}
				case 'php' : {
					return 'phtml';
				}
				case 'tpls' : {
					return 'tpl';
				}
				default : {
					throw new coreException('Unsupported type given: ' . $this->getType());
				}
			}
		}

		/** @inheritDoc */
		public function getConfigPath() {
			$name = $this->getName();

			if (is_string($name) && !empty($name)) {
				return $this->resourcesDirectory . 'config.ini';
			}

			return null;
		}

		/** @inheritDoc */
		public function getUsedPages($limit = 0, $offset = 0) {
			$limitString = '';
			$connection = ConnectionPool::getInstance()->getConnection();
			$escapedLimit = $connection->escape($limit);
			$escapedOffset = $connection->escape($offset);

			if (is_numeric($limit) && $limit > 0) {
				$limitString = "LIMIT ${escapedOffset}, ${escapedLimit}";
			}

			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$sql = <<<QUERY
SELECT SQL_CALC_FOUND_ROWS
	     h.id,
       o.NAME
FROM   cms3_hierarchy h,
       cms3_objects o
WHERE  h.tpl_id $templateCondition
       AND o.id = h.obj_id
       AND h.is_deleted = '0'
       AND h.domain_id = $domainId
       ${limitString}
QUERY;
			$result = $connection->queryResult($sql);
			$result->setFetchType(IQueryResult::FETCH_ROW);

			$res = [];

			foreach ($result as $row) {
				list($id, $name) = $row;
				$res[] = [$id, $name];
			}

			return $res;
		}

		/** @inheritDoc */
		public function getTotalUsedPages() {
			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$query = <<<QUERY
SELECT count(`id`)
FROM   cms3_hierarchy h USE INDEX (PRIMARY)
WHERE  h.tpl_id $templateCondition
       AND h.is_deleted = '0'
       AND h.domain_id = $domainId
QUERY;
			$connection = ConnectionPool::getInstance()->getConnection();
			$result = $connection->queryResult($query);
			$result->setFetchType(IQueryResult::FETCH_ROW);
			$count = 0;

			if ($result->length() > 0) {
				$fetchResult = $result->fetch();
				$count = (int) array_shift($fetchResult);
			}

			return $count;
		}

		/** @inheritDoc */
		public function getTotalUsedPagesWithNameLike($name) {
			if ($name === null) {
				return $this->getTotalUsedPages();
			}

			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$connection = ConnectionPool::getInstance()->getConnection();
			$name = $connection->escape($name);
			$sql = <<<QUERY
SELECT count(h.id)
FROM   cms3_hierarchy h,
       cms3_objects o
WHERE  h.tpl_id $templateCondition
       AND o.id = h.obj_id
       AND h.is_deleted = '0'
       AND h.domain_id = $domainId
       AND o.name LIKE "$name%"
QUERY;
			$row = $connection->queryResult($sql)
				->fetch();
			return (int) array_shift($row);
		}

		/** @inheritDoc */
		public function hasRelatedPages() {
			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$selection = <<<SQL
SELECT `id` FROM `cms3_hierarchy` h WHERE h.`tpl_id` $templateCondition 
	AND h.`is_deleted` = '0'
   	AND h.`domain_id` = $domainId
	LIMIT 0,1;
SQL;
			$result = ConnectionPool::getInstance()
				->getConnection()
				->queryResult($selection);
			return $result->length() === 1;
		}

		/** @inheritDoc */
		public function hasRelatedPagesWithNameLike($name) {
			if ($name === null) {
				return $this->hasRelatedPages();
			}

			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$connection = ConnectionPool::getInstance()->getConnection();
			$name = $connection->escape($name);
			$selection = <<<SQL
SELECT h.`id` FROM `cms3_hierarchy` h, `cms3_objects` o 
	WHERE h.`tpl_id` $templateCondition 
	AND o.`id` = h.`obj_id`
	AND h.`is_deleted` = '0'
   	AND h.`domain_id` = $domainId
   	AND o.`name` LIKE "$name%"
	LIMIT 0,1;
SQL;
			return $connection->queryResult($selection)->length() === 1;
		}

		/** @inheritDoc */
		public function getRelatedPages($limit = 0, $offset = 0) {
			$relatedPages = $this->getUsedPages($limit, $offset);
			$relatedPagesIdList = [];

			/** @var array $pageData */
			foreach ($relatedPages as $pageData) {
				if (isset($pageData[0]) && is_numeric($pageData[0])) {
					$relatedPagesIdList[] = $pageData[0];
				}
			}
			$hierarchy = umiHierarchy::getInstance();
			return $hierarchy->loadElements($relatedPagesIdList);
		}

		/** @inheritDoc */
		public function getRelatedPagesWithNameLike($name, $limit, $offset) {
			if ($name === null) {
				return $this->getRelatedPages($limit, $offset);
			}

			$templateId = (int) $this->getId();
			$templateCondition = $this->getTemplateCondition($templateId);
			$domainId = (int) $this->getDomainId();
			$connection = ConnectionPool::getInstance()->getConnection();
			$name = $connection->escape($name);
			$limit = (int) $limit;
			$offset = (int) $offset;
			$sql = <<<QUERY
SELECT h.id
FROM   cms3_hierarchy h,
       cms3_objects o
WHERE  h.tpl_id $templateCondition
       AND o.id = h.obj_id
       AND h.is_deleted = '0'
       AND h.domain_id = $domainId
       AND o.name LIKE "$name%"
LIMIT $offset, $limit
QUERY;
			$result = $connection->queryResult($sql)
				->setFetchType(IQueryResult::FETCH_ASSOC);
			$idList = [];

			foreach ($result as $row) {
				$idList[] = $row['id'];
			}

			return umiHierarchy::getInstance()->loadElements($idList);
		}

		/** @inheritDoc */
		public function setUsedPages($pages) {
			if ($pages === null) {
				return false;
			}

			$domainId = (int) $this->getDomainId();
			$defaultTplId = (int) templatesCollection::getInstance()
				->getDefaultTemplate($domainId, $this->lang_id)
				->getId();
			$templateId = (int) $this->getId();
			$sql = <<<SQL
UPDATE cms3_hierarchy
SET tpl_id = $defaultTplId
WHERE tpl_id = $templateId AND is_deleted = '0' AND domain_id = $domainId
SQL;
			ConnectionPool::getInstance()
				->getConnection()
				->query($sql);
			$hierarchy = umiHierarchy::getInstance();

			if (!is_array($pages)) {
				return false;
			}

			if (is_array($pages) && !empty($pages)) {
				foreach ($pages as $elementId) {
					$page = $hierarchy->getElement($elementId);
					if ($page instanceof iUmiHierarchyElement) {
						$page->setTplId($this->id);
						$page->commit();
						unset($page);
						$hierarchy->unloadElement($elementId);
					}
				}
			}

			return true;
		}

		/** @inheritDoc */
		protected function loadInfo($row = false) {
			if (!is_array($row) || count($row) < 8) {
				$connection = ConnectionPool::getInstance()->getConnection();
				$escapedId = (int) $this->getId();
				$sql = <<<SQL
SELECT id, name, filename, type, title, domain_id, lang_id, is_default 
FROM cms3_templates WHERE id = $escapedId LIMIT 0,1
SQL;
				$result = $connection->queryResult($sql);
				$result->setFetchType(IQueryResult::FETCH_ROW);
				$row = $result->fetch();
			}

			if (!is_array($row) || count($row) < 8) {
				return false;
			}

			list($id, $name, $filename, $type, $title, $domainId, $langId, $isDefault) = $row;
			$this->name = (string) $name;
			$this->filename = $filename;
			$this->type = (string) $type;
			$this->title = $title;
			$this->domain_id = (int) $domainId;
			$this->lang_id = (int) $langId;
			$this->is_default = (bool) $isDefault;

			if (!empty($this->filename)) {
				// определяем полный путь к шаблону, а так же путь к директории с ресурсами
				$templateExt = pathinfo($this->filename, PATHINFO_EXTENSION);

				if ($this->type === '') {
					switch(mb_strtolower($templateExt)) {
						case 'xsl':
							$this->type = 'xslt';
							break;
						case 'tpl':
							$this->type = 'tpls';
							break;
						case 'phtml':
							$this->type = 'php';
							break;
						default:
							$this->type = $templateExt !== '' ? $templateExt : 'xslt';
					}
				}

				$config = mainConfiguration::getInstance();

				// TODO: refactoring
				if ($this->type == 'xslt') {
					$this->templatesDirectory = $templateDir = $config->includeParam('templates.xsl');
				} elseif ($this->type == 'tpls') {
					$this->templatesDirectory = $config->includeParam('templates.tpl');
					$templateDir = $this->templatesDirectory . 'content/';
				} elseif ($this->type == 'php') {
					$this->templatesDirectory = $config->includeParam('templates.php');
					$templateDir = $this->templatesDirectory . 'content/';
				} else {
					$this->templatesDirectory = $templateDir = $config->includeParam('templates.' . $this->type) . '/';
				}

				if ($this->name !== '') {
					$this->resourcesDirectory = CURRENT_WORKING_DIR . '/templates/' . $this->name . '/';
					$templateDir = $this->templatesDirectory = $this->resourcesDirectory . $this->type . '/';
					if ($this->type == 'tpls') {
						$templateDir = $this->templatesDirectory . 'content/';
					}
				}

				// mobile mode template
				if (Service::Request()->isMobile() && is_file($templateDir . 'mobile/' . $this->filename)) {
					$this->filePath = $templateDir . 'mobile/' . $this->filename;
				} else {
					// standart mode template
					$this->filePath = $templateDir . $this->filename;
				}
			}

			return true;
		}

		/**
		 * Сохраняет изменения в БД
		 * @return bool true, если не возникло ошибки
		 */
		protected function save() {
			$name = self::filterInputString($this->name);
			$filename = self::filterInputString($this->filename);
			$type = self::filterInputString($this->type);
			$title = self::filterInputString($this->title);
			$domainId = (int) $this->domain_id;
			$langId = (int) $this->lang_id;
			$isDefault = (int) $this->is_default;
			$connection = ConnectionPool::getInstance()->getConnection();
			$sql = <<<SQL
UPDATE cms3_templates
SET name = '{$name}', filename = '{$filename}', type = '{$type}', title = '{$title}',
    domain_id = '{$domainId}', lang_id = '{$langId}', is_default = '{$isDefault}'
WHERE id = '{$this->id}'
SQL;
			$connection->query($sql);
			return true;
		}

		/**
		 * Возвращает выражение для условия запроса по эквивалентности идентификатору шаблона
		 * @param int $templateId идентификатор шаблона
		 * @return string
		 */
		private function getTemplateCondition($templateId) {
			return ($templateId > 0) ? sprintf('= %d', $templateId) : 'IS NULL';
		}
	}
