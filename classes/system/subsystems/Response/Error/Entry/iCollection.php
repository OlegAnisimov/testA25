<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Orm\Entity\iCollection as iAbstractCollection;

	/**
	 * Интерфейс коллекции записей об ответе с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	interface iCollection extends iAbstractCollection {

		/**
		 * @inheritDoc
		 * @return \UmiCms\System\Response\Error\Entry\iCollection
		 */
		public function slice($offset, $limit = null);
	}