<?php


namespace UmiCms\System\Admin;


use UmiCms\System\Cookies\iCookieJar;
use UmiCms\System\Request\Http\iGet;
use UmiCms\System\Request\Http\iPost;

/**
 * @package UmiCms\System\Admin
 */
class Skin implements iSkin {
	/** @var \iConfiguration */
	private $config;
	/** @var \iCmsController */
	private $controller;
	/** @var iCookieJar */
	private $cookieJar;
	/** @var iGet */
	private $getParams;
	/** @var iPost */
	private $postParams;

	/**
	 * @param \iConfiguration $config
	 * @param \iCmsController $controller
	 * @param iCookieJar $cookieJar
	 * @param iGet $getParams
	 * @param iPost $postParams
	 */
	public function __construct(
		\iConfiguration $config,
		\iCmsController $controller,
		iCookieJar $cookieJar,
		iGet $getParams,
		iPost $postParams) {

		$this->config = $config;
		$this->controller = $controller;
		$this->cookieJar = $cookieJar;
		$this->getParams = $getParams;
		$this->postParams = $postParams;
	}

	/**
	 * В качестве побочного эффекта может создать Cookie
	 * @return string
	 * @throws \wrongParamException
	 */
	public function name() {
		$casualSkins = $this->config->getList('casual-skins');
		$methodName = $this->controller->getCurrentModule() . '::' . $this->controller->getCurrentMethod();

		foreach ($casualSkins as $casualSkinName) {
			if (in_array($methodName, $this->config->get('casual-skins', $casualSkinName))) {
				return $casualSkinName;
			}
		}

		$skins = $this->config->get('system', 'skins');

		if ($this->getParams->isExist('skin_sel') || $this->postParams->isExist('skin_sel')) {
			$skin = $this->getParams->get('skin_sel') !== null ? $this->getParams->get('skin_sel') : $this->postParams->get('skin_sel');
			$this->cookieJar->set('skin_sel', $skin, time() + 3600 * 24 * 365);

			if (in_array($skin, $skins)) {
				return $skin;
			}
		}

		if ($this->cookieJar->get('skin_sel')) {
			if (in_array($this->cookieJar->get('skin_sel'), $skins)) {
				return $this->cookieJar->get('skin_sel');
			}
		}

		return $this->config->get('system', 'default-skin');
	}
}