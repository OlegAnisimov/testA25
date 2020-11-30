<?php
	namespace UmiCms\System\Response\Error\Entry\Attribute;

	use UmiCms\System\Orm\Entity\iMutator;
	use UmiCms\System\Orm\Entity\Attribute\Mutator as AbstractMutator;

	/**
	 * Класс мутатора атрибутов записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry\Attribute
	 */
	class Mutator extends AbstractMutator implements iMutator {}