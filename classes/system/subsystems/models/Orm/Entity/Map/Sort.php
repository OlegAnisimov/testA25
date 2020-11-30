<?php
	namespace UmiCms\System\Orm\Entity\Map;

	/**
	 * Класс схемы карты сортировки
	 * @package UmiCms\System\Orm\Entity\Map
	 */
	abstract class Sort {

		/** @const string SORT_TYPE_ASC тип сортировки "по возрастанию" */
		const SORT_TYPE_ASC = 'asc';

		/** @const string SORT_TYPE_DESC тип сортировки "по убыванию" */
		const SORT_TYPE_DESC = 'desc';

		/** @const string SORT_TYPE_RAND тип сортировки "случайная" */
		const SORT_TYPE_RAND = 'rand';
	}