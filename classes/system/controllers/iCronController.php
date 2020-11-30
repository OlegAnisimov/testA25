<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iUmiCron as iExecutor;
	use UmiCms\Classes\System\MiddleWares\iAuth;
	use \iPermissionsCollection as iPermissions;

	/**
	 * Интерфейс контоллера cron.php
	 * @package UmiCms\Classes\System\Controllers
	 */
	interface iCronController extends iController, iAuth{

		/**
		 * Устанавливает исполнителя крона
		 * @param iExecutor $executor исполнитель крона
		 * @return $this
		 */
		public function setExecutor(iExecutor $executor);

		/**
		 * Устанавливает фасад прав
		 * @param iPermissions $permissions фасад прав
		 * @return $this
		 */
		public function setPermissions(iPermissions $permissions);
	}