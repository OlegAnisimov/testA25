<?php
	namespace UmiCms\System\Response\Error\Entry;

	use UmiCms\System\Orm\Entity\iMapper as iAbstractMapper;

	/**
	 * Интерфейс маппера записей об ответе с ошибкой
	 * @package UmiCms\System\Response\Error\Entry
	 */
	interface iMapper extends iAbstractMapper {

		/** @var string URL имя столбца в бд для хранения адреса запроса */
		const URL = 'url';

		/** @var string CODE имя столбца в бд для хранения кода ошибки */
		const CODE = 'code';

		/** @var string HITS_COUNT имя столбца в бд для количества обращений */
		const HITS_COUNT = 'hits_count';

		/** @var int DOMAIN_ID имя столбца в бд для идентификатор домена */
		const DOMAIN_ID = 'domain_id';

		/** @var int UPDATE_TIME имя столбца в бд для времени обновления */
		const UPDATE_TIME = 'update_time';
	}