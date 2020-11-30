<?php

	/** Реализация заглушки хранилища кеша */
	class nullCacheEngine implements iCacheEngine {

		/** @const string NAME название хранилища */
		const NAME = 'null';

		/** @inheritDoc */
		public function getName() {
			return self::NAME;
		}

		/** @inheritDoc */
		public function saveRawData($key, $data, $expire) {
			return true;
		}

		/** @inheritDoc */
		public function loadRawData($key) {
			return null;
		}

		/** @inheritDoc */
		public function delete($key) {
			return true;
		}

		/** @inheritDoc */
		public function flush() {
			return true;
		}

		/** @inheritDoc */
		public function getIsConnected() {
			return true;
		}
	}
