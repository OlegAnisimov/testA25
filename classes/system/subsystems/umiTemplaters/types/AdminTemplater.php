<?php

namespace UmiCms\System\Templater;

use UmiCms\System\Admin\iSkin;
use UmiCms\System\Auth\iAuth;

/**
 * @package UmiCms\System\Templater
 */
class AdminTemplater extends \umiTemplaterXSLT {

	/** @var iSkin */
	private $skin;
	/** @var \iConfiguration */
	private $config;
	/** @var \iPermissionsCollection */
	private $permissions;
	/** @var iAuth */
	private $auth;
	/** @var \icmsController */
	private $controller;

	/**
	 * @param iSkin $skin
	 * @param \iConfiguration $config
	 * @param \iPermissionsCollection $permissions
	 * @param iAuth $auth
	 * @param \iCmsController $controller
	 */
	public function __construct(
		iSkin $skin, \iConfiguration $config, \iPermissionsCollection $permissions, iAuth $auth, \iCmsController $controller
	) {

		$this->skin = $skin;
		$this->config = $config;
		$this->permissions = $permissions;
		$this->auth = $auth;
		$this->controller = $controller;
	}

	/** @inheritDoc */
	public function getTemplatesSource() {
		if (!is_file($this->templatePath())) {
			throw new \coreException('Template "' . $this->templatePath() . '" not found.');
		}

		return $this->templatePath();
	}

	/** @return string */
	private function templatePath() {
		return $this->skinPath() . $this->fileName();
	}

	/** @return string */
	private function skinPath() {
		return $this->config->includeParam('templates.skins', ['skin' => $this->skin->name()]);
	}

	/** @return string */
	private function fileName() {
		$isAllowed = $this->permissions->isAllowedMethod(
			$this->auth->getUserId(),
			$this->controller->getCurrentModule(),
			$this->controller->getCurrentMethod()
		);

		if ($this->permissions->isAdmin(false, true) && $isAllowed) {
			return $this->config->get('includes', 'templates.admin.entry');
		}

		return $this->config->get('includes', 'templates.admin.login');
	}
}
