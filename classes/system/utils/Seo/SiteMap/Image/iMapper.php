<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\System\Orm\Entity\iMapper as iAbstractMapper;

	/**
	 * Интерфейс маппера изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	interface iMapper extends iAbstractMapper {

		/** @var string LOCATION_ID имя столбца в бд для хранения идентификатора адреса */
		const LOCATION_ID = 'location_id';

		/** @var string DOMAIN_ID имя столбца в бд для хранения идентификатора домена */
		const DOMAIN_ID = 'domain_id';

		/** @var string LINK имя столбца в бд для хранения ссылки */
		const LINK = 'link';

		/** @var string ALT имя столбца в бд для хранения альтернативного текста */
		const ALT = 'alt';

		/** @var string TITLE имя столбца в бд для хранения заголовка */
		const TITLE = 'title';
	}