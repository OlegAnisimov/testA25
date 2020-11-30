<?php
	namespace UmiCms\System\Response\Error\Entry\Relation;

	use UmiCms\System\Orm\Entity\iMutator;
	use UmiCms\System\Orm\Entity\Relation\Mutator as AbstractMutator;

	/**
	 * Класс мутатора связей записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error\Entry\Relation
	 */
	class Mutator extends AbstractMutator implements iMutator {}