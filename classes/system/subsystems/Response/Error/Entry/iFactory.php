<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Orm\Entity\iFactory as iAbstractFactory;

	/**
	 * Интерфейс фабрики записей об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	interface iFactory extends iAbstractFactory {

		/**
		 * @inheritDoc
		 * @return iEntry
		 */
		public function create();
	}