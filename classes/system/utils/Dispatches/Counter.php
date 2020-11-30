<?php
	namespace UmiCms\Utils\Dispatches;

	/**
	 * Класс счетчика открытия рассылок
	 * @package UmiCms\Utils\Dispatches
	 */
	class Counter implements iCounter {

		/** @var \IConnection $connection подключение к базе данных */
		private $connection;

		/** @var \iConfiguration $config конфигурация */
		private $config;

		/** @inheritDoc */
		public function __construct(\IConnection $connection, \iConfiguration $config) {
			$this->connection = $connection;
			$this->config = $config;
		}

		/** @inheritDoc */
		public function countEntry($path) {
			$path = $this->connection->escape($path);
			$createTable = <<<SQL
CREATE TABLE IF NOT EXISTS `cms_stat_dispatches`
(
	`hash` Varchar(10) NOT NULL,
	`time` INT(11) NOT NULL
)
engine=innodb DEFAULT CHARSET=utf8;
SQL;
			$this->connection->query($createTable);

			$time = time();
			$insertEntry = <<<SQL
INSERT INTO `cms_stat_dispatches` (`hash`, `time`) VALUES('$path', $time);
SQL;
			$this->connection->query($insertEntry);
		}

		/** @inheritDoc */
		public function generateImage() {
			$imagePath = $this->config->includeParam('system.runtime-cache') . 'counter.gif';

			if (is_file($imagePath)) {
				return $imagePath;
			}

			$image = imagecreatetruecolor(1, 1);
			imagealphablending($image, true);
			$color = imagecolorallocate($image, 255, 255, 255);
			imagecolortransparent($image, $color);
			imagegif($image, $imagePath);
			return $imagePath;
		}
	}