<?php
	namespace UmiCms\System\Utils\Emoji;

	/**
	 * Класс переводчика эмодзи
	 * @package UmiCms\System\Utils\Emoji
	 */
	class Translator implements iTranslator {

		/** @inheritDoc */
		public function fromUnicodeToShortName($unicode) {
			try {
				return $this->createClient()
					->toShort($unicode);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return $unicode;
			}
		}

		/** @inheritDoc */
		public function fromShortNameToUnicode($shortName) {
			try {
				return $this->createClient()
					->shortnameToUnicode($shortName);
			} catch (\Exception $exception) {
				\umiExceptionHandler::report($exception);
				return $shortName;
			}
		}

		/**
		 * Создает экземпляр Emojione Client
		 * @return \Emojione\Client
		 * @throws \Exception
		 */
		private function createClient() {
			if (class_exists('Emojione\Client')) {
				return new \Emojione\Client();
			}

			throw new \Exception('Emojione client not loaded');
		}
	}