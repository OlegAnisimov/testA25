<?php
	namespace UmiCms\System\Orm\Entity\Map;

	/**
	 * Класс схемы карты фильтров
	 * @package UmiCms\System\Orm\Entity\Map
	 */
	abstract class Filter {

		/** @const string COMPARE_TYPE_EQUALS тип сравнения "равно" */
		const COMPARE_TYPE_EQUALS = 'eq';

		/** @const string COMPARE_TYPE_NOT_EQUALS тип сравнения "не равно" */
		const COMPARE_TYPE_NOT_EQUALS = 'ne';

		/** @const string COMPARE_TYPE_LIKE тип сравнения "похоже" */
		const COMPARE_TYPE_LIKE = 'like';

		/** @const string COMPARE_TYPE_NOT_LIKE тип сравнения "не похоже" */
		const COMPARE_TYPE_NOT_LIKE = 'not_like';

		/** @const string COMPARE_TYPE_IN_LIST тип сравнения "входит в список" */
		const COMPARE_TYPE_IN_LIST = 'in_list';

		/** @const string COMPARE_TYPE_IN_LIST тип сравнения "не входит в список" */
		const COMPARE_TYPE_NOT_IN_LIST = 'not_in_list';

		/** @const string COMPARE_TYPE_IS_NULL тип сравнения "не имеет значения" */
		const COMPARE_TYPE_IS_NULL = 'is_null';

		/** @const string COMPARE_TYPE_IS_NOT_NULL тип сравнения "имеет значения" */
		const COMPARE_TYPE_IS_NOT_NULL = 'is_not_null';

		/** @const string COMPARE_TYPE_LESS тип сравнения "меньше" */
		const COMPARE_TYPE_LESS = 'less';

		/** @const string COMPARE_TYPE_LESS_OR_EQUALS тип сравнения "меньше или равно" */
		const COMPARE_TYPE_LESS_OR_EQUALS = 'less_or_eq';

		/** @const string COMPARE_TYPE_MORE тип сравнения "больше" */
		const COMPARE_TYPE_MORE = 'more';

		/** @const string COMPARE_TYPE_MORE_OR_EQUALS тип сравнения "больше или равно" */
		const COMPARE_TYPE_MORE_OR_EQUALS = 'more_or_eq';

		/** @const string COMPARE_TYPE_MORE тип сравнения "между" */
		const COMPARE_TYPE_BETWEEN = 'between';
	}