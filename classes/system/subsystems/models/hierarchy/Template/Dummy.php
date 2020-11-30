<?php
	namespace UmiCms\System\Hierarchy\Template;

	/**
	 * Класс заглушки шаблона сайта
	 * @package UmiCms\System\Hierarchy\Template
	 */
	class Dummy extends \template implements \iTemplate {

		use \UmiCms\System\Hierarchy\Domain\Detector\tInjector;
		use \UmiCms\System\Hierarchy\Language\Detector\tInjector;

		/** @var string DUMMY_ID идентификатор шаблона-заглушки */
		const DUMMY_ID = 'dummy';

		/** @inheritDoc */
		protected $resourcesDirectory;

		/** @inheritDoc */
		public function __construct($id = false, $row = false, $instantLoad = true, $savingInDestructor = true) {
			$this->resourcesDirectory = CURRENT_WORKING_DIR . '/templates/' . $this->getName() . '/';
		}

		/** @inheritDoc */
		public function getId() {
			return self::DUMMY_ID;
		}

		/** @inheritDoc */
		public function commit() {
			return false;
		}

		/** @inheritDoc */
		public function update() {
			return false;
		}

		/** @inheritDoc */
		public function getIsUpdated() {
			return false;
		}

		/** @inheritDoc */
		public function setIsUpdated($isUpdated = true) {
			return $this;
		}

		/** @inheritDoc */
		public function __clone() {
			//nothing
		}

		/** @inheritDoc */
		public function getName() {
			return $this->getId();
		}

		/** @inheritDoc */
		public function getFilename() {
			return 'index.phtml';
		}

		/** @inheritDoc */
		public function getTemplatesDirectory() {
			return $this->getResourcesDirectory();
		}

		/** @inheritDoc */
		public function getFilePath() {
			return $this->getResourcesDirectory() . $this->getType() . '/';
		}

		/** @inheritDoc */
		public function getType() {
			return 'php';
		}

		/** @inheritDoc */
		public function getTitle() {
			return $this->translateLabel('label-no-template');
		}

		/** @inheritDoc */
		public function getDomainId() {
			return $this->getDomainDetector()
				->detectId();
		}

		/** @inheritDoc */
		public function getLangId() {
			return$this->getLanguageDetector()
				->detectId();
		}

		/** @inheritDoc */
		public function getIsDefault() {
			return false;
		}

		/** @inheritDoc */
		public function setName($name) {
			return false;
		}

		/** @inheritDoc */
		public function setUsedPages($pages) {
			return false;
		}

		/** @inheritDoc */
		protected function loadInfo($row = false) {
			return true;
		}

		/** @inheritDoc */
		protected function save() {
			return true;
		}
	}