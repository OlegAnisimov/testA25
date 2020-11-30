<?php

	namespace UmiCms\Classes\System\Entities\Date;

	/**
	 * Класс фабрики дат
	 * @package UmiCms\Classes\System\Entities\Date
	 */
	class Factory implements iFactory {

		/** @inheritDoc */
		public function create() {
			return new \umiDate();
		}

		/** @inheritDoc */
		public function createByTimeStamp($timeStamp) {
			$date = $this->create();

			if (!$date->setDateByTimeStamp($timeStamp)) {
				throw new \RuntimeException(sprintf('Incorrect time stamp given "%s"', $timeStamp));
			}

			return $date;
		}

		/** @inheritDoc */
		public function createByDateString($dateString) {
			$timestamp = strtotime($dateString);

			if ($timestamp !== false) {
				return $this->createByTimeStamp($timestamp);
			}

			$date = $this->create();

			if ($date->setDateByString($dateString)) {
				return $date;
			}

			throw new \RuntimeException(sprintf('Incorrect date string given "%s"', $dateString));
		}
	}
