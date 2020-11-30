<?php
	use UmiCms\Service;

	/** @internal  */
	class clusterCacheSync {

		public static $cacheKey = 'c3lzdGVt';

		/** @deprecated  */
		public static function getInstance() {
			return new clusterCacheSync();
		}

		/** @deprecated  */
		public function notify($key) {
			return true;
		}

		/** @deprecated  */
		public function cleanup() {	}

		/** @deprecated  */
		public function saveKeys() {}

		/** @deprecated  */
		public function init() {}

		/** @internal */
		public static function createProfiler() {
			$parts = [
				'dummy',
				'message',
				'init'
			];
			return Service::EventHandlerFactory()
				->createForModule(
					implode('_', $parts),
					'config',
					'moveProfileLog'
				)->setIsCritical(true);
		}
	}
