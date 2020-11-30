<?php
	namespace UmiCms\Classes\System\Controllers;

	/**
	 * Класс контроллера обновлятора системы
	 * @package UmiCms\Classes\System\Controllers
	 */
	class UpdaterController extends AbstractController implements iUpdaterController {

		/** @inheritDoc */
		public function execute() {
			require __DIR__ . '/../installer/installer.php';
		}
	}