<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\System\Orm\Entity\iMapper as iAbstractMapper;

	/**
	 * Интерфейс маппера адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	interface iMapper extends iAbstractMapper {

		/** @var string DOMAIN_ID имя столбца для хранения идентификатор домена */
		const DOMAIN_ID = 'domain_id';

		/** @var string LINK имя столбца для хранения ссылки  */
		const LINK = 'link';

		/** @var string SORT имя столбца для хранения индекса сортировки  */
		const SORT = 'sort';

		/** @var string priority имя столбца для хранения приоритета индексации */
		const PRIORITY = 'priority';

		/** @var string DATE_TIME имя столбца для хранения даты обновления страницы */
		const DATE_TIME = 'dt';

		/** @var string LEVEL имя столбца для хранения уровня вложенности страницы */
		const LEVEL = 'level';

		/** @var string LANGUAGE_ID имя столбца для хранения идентификатора языка */
		const LANGUAGE_ID = 'lang_id';

		/** @var string CHANGE_FREQUENCY имя столбца для хранения вероятной частоты изменения */
		const CHANGE_FREQUENCY = 'change_frequency';

		/** @var string DOMAIN имя связи - домен  */
		const DOMAIN = 'DOMAIN';

		/** @var string LANGUAGE имя связи - язык  */
		const LANGUAGE = 'LANGUAGE';

		/** @var string IMAGE_COLLECTION имя связи - коллекция изображений */
		const IMAGE_COLLECTION = 'image_collection';
	}