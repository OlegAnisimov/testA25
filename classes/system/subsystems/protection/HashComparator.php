<?php
	namespace UmiCms\System\Protection;

	/**
	 * Класс сравнителя хэшей
	 * @package UmiCms\System\Protection
	 */
	class HashComparator implements iHashComparator {

		/** @inheritDoc */
		public function equals($knownHash, $userHash) {
			return function_exists('hash_equals')
				? hash_equals($knownHash, $userHash)
				: strcmp($knownHash, $userHash) == 0;
		}
	}