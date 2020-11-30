<?php
	namespace UmiCms\System\Orm\Entity\Attribute;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\iMutator;
	use UmiCms\System\Orm\Entity\Mutator as AbstractMutator;

	/**
	 * Абстрактный класс мутатора атрибутов сущности
	 * @package UmiCms\System\Orm\Entity\Attribute
	 */
	abstract class Mutator extends AbstractMutator implements iMutator {

		/** @inheritDoc */
		protected function getSchema($name) {
			return $this->getMapper()->getAttributeSchema($name);
		}

		/** @inheritDoc */
		protected function getSchemaList() {
			return $this->getMapper()->getAttributeSchemaList();
		}

		/** @inheritDoc */
		protected function mutateBySchema(iEntity $entity, array $schema, $value) {
			list(, $mutator, $type) = $schema;
			settype($value, $type);
			$entity->$mutator($value);
			return $entity;
		}
	}