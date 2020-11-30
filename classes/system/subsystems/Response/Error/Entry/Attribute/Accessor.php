<?php
	namespace UmiCms\System\Response\Error\Entry\Attribute;

	use UmiCms\System\Orm\Entity\iAccessor;
	use UmiCms\System\Orm\Entity\Attribute\Accessor as AbstractAccessor;

	/**
	 * Класс аксессора атрибутов записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry\Attribute
	 */
	class Accessor extends AbstractAccessor implements iAccessor {}