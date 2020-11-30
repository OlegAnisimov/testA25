<?php
	namespace UmiCms\System\Response\Error\Entry\Relation;

	use UmiCms\System\Orm\Entity\iAccessor;
	use UmiCms\System\Orm\Entity\Relation\Accessor as AbstractAccessor;

	/**
	 * Класс аксессора связей записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry\Relation
	 */
	class Accessor extends AbstractAccessor implements iAccessor {}