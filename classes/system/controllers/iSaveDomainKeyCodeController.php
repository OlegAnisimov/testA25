<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iRegedit as iRegistry;
	use UmiCms\Utils\DomainKeyCode\iSaver;
	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDOMDocumentFactory;

	/**
	 * Интерфейс контроллера сохранения доменного ключа
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iSaveDomainKeyCodeController extends iController {

		/**
		 * Устанавливает реестр
		 * @param iRegistry $registry реестр
		 * @return $this
		 */
		public function setRegistry(iRegistry $registry);

		/**
		 * Устанавливает сохранитель доменного ключа
		 * @param iSaver $saver сохранитель доменного ключа
		 * @return $this
		 */
		public function setSaver(iSaver $saver);

		/**
		 * Устанавливает фабрику xml документов
		 * @param iDOMDocumentFactory $domDocumentFactory фабрика xml документов
		 * @return $this
		 */
		public function setDomDocumentFactory(iDOMDocumentFactory $domDocumentFactory);
	}