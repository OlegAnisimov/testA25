<?php
	namespace UmiCms\System\Orm\Entity\Relation;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\iMutator;
	use UmiCms\System\Orm\Entity\Mutator as AbstractMutator;

	/**
	 * Абстрактный класс мутатора связей сущности
	 * @package UmiCms\System\Orm\Entity\Relation
	 */
	abstract class Mutator extends AbstractMutator implements iMutator {

		/** @inheritDoc */
		protected function getSchema($name) {
			return $this->getMapper()->getRelationSchema($name);
		}

		/** @inheritDoc */
		protected function getSchemaList() {
			return $this->getMapper()->getRelationSchemaList();
		}

		/** @inheritDoc */
		protected function mutateBySchema(iEntity $entity, array $schema, $value) {
			list(,,,,$mutator) = $schema;
			$entity->$mutator($value);
			return $entity;
		}
	}