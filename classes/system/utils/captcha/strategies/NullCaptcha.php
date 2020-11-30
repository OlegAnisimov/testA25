<?php

	namespace UmiCms\Classes\System\Utils\Captcha\Strategies;

	/** Стратегия Капчи Null object. */
	class NullCaptcha extends CaptchaStrategy {

		/** @inheritDoc */
		public function generate($template, $inputId, $captchaHash, $captchaId) {
			return '';
		}

		/** @inheritDoc */
		public function isValid() {
			return true;
		}

		/** @inheritDoc */
		public function isRequired() {
			return false;
		}

		/** @inheritDoc */
		public function getName() {
			return 'null-captcha';
		}
	}
