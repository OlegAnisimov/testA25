<?php
	namespace UmiCms\Classes\System\Controllers;

	use \captchaDrawer as iGenerator;
	use \iUmiCaptcha as iCaptchaFacade;
	use \UmiCms\System\Session\iSession;

	/**
	 * Класс контроллера каптчи
	 * @package UmiCms\Classes\System\Controllers
	 */
	class CaptchaController extends AbstractController implements iCaptchaController {

		/** @var iSession $session сессия */
		private $session;

		/** @var iGenerator $generator генератор каптчи */
		private $generator;

		/** @inheritDoc */
		public function setSession(iSession $session) {
			$this->session = $session;
			return $this;
		}

		/** @inheritDoc */
		public function setGenerator(iCaptchaFacade $captchaFacade) {
			$this->generator = $captchaFacade::getDrawer();
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$hashOrHashList = $this->session->get('umi_captcha');
			$code = $this->generator->getRandomCode();
			$id = $this->request->Get()->get('id');

			if ($id !== null) {
				if (is_string($hashOrHashList)) {
					$hashOrHashList = [];
				}

				$hashOrHashList[$id] = md5($code);
			} else {
				$hashOrHashList = md5($code);
			}

			$this->session->set('umi_captcha', $hashOrHashList);
			$this->buffer->setHeader('X-Robots-Tag', 'none');
			$this->generator->draw($code);
		}
	}