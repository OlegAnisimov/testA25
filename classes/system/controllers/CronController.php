<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iUmiCron as iExecutor;
	use \iPermissionsCollection as iPermissions;
	use UmiCms\Classes\System\MiddleWares\tAuth;

	/**
	 * Класс контоллера cron.php
	 * @package UmiCms\Classes\System\Controllers
	 */
	class CronController extends AbstractController implements iCronController {

		/** @var iExecutor $executor исполнитель крона */
		private $executor;

		/** @var iPermissions $permissions фасад прав */
		private $permissions;

		use tAuth;

		/** @inheritDoc */
		public function setExecutor(iExecutor $executor) {
			$this->executor = $executor;
			return $this;
		}

		/** @inheritDoc */
		public function setPermissions(iPermissions $permissions) {
			$this->permissions = $permissions;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$this->loginByEnvironment();
			$userId = $this->auth->getUserId();

			if (!$this->permissions->isAllowedMethod($userId, 'config', 'cron_http_execute')) {
				$this->buffer->crash('required_more_permissions', 403);
			}

			$this->buffer->contentType('text/plain');
			$comment = <<<END
This file should be executed by cron only. Please, run it via HTTP for test only.
Maximum priority level can accept values between "1" and "10", where "1" is maximum priority.


END;
			$this->buffer->push($comment);

			$modules = $this->request->Get()->get('module');
			$modules = $modules ? (array) $modules : [];

			$methods = $this->request->Get()->get('method');
			$methods = $methods ? (array) $methods : [];

			$this->executor->setModules($modules);
			$this->executor->setMethods($methods);
			$this->executor->run();

			$this->buffer->push($this->executor->getParsedLogs());
			$this->buffer->end();
		}
	}