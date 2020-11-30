<?php
	namespace UmiCms\System\Orm\Entity;

	/**
	 * Абстрактный класс маппера сущностей
	 * @package UmiCms\System\Orm\Entity
	 */
	abstract class Mapper implements iMapper {

		/** @inheritDoc */
		public function getAttributeSchemaList() {
			return [
				self::ID => [
					'getId',
					'setId',
					'int'
				]
			];
		}

		/** @inheritDoc */
		public function getAttributeList() {
			return array_keys($this->getAttributeSchemaList());
		}

		/** @inheritDoc */
		public function isExistsAttribute($name) {
			if (!is_string($name) && !is_int($name)) {
				return false;
			}

			return isset($this->getAttributeSchemaList()[$name]);
		}

		/** @inheritDoc */
		public function getAttributeSchema($name) {
			if (!$this->isExistsAttribute($name)) {
				throw new \ErrorException(sprintf('Incorrect attribute name given: "%s"', $name));
			}

			return $this->getAttributeSchemaList()[$name];
		}

		/** @inheritDoc */
		public function getRelationSchemaList() {
			return [];
		}

		/** @inheritDoc */
		public function getRelationList() {
			return array_keys($this->getRelationSchemaList());
		}

		/** @inheritDoc */
		public function isExistsRelation($name) {
			if (!is_string($name) && !is_int($name)) {
				return false;
			}

			return isset($this->getRelationSchemaList()[$name]);
		}

		/** @inheritDoc */
		public function getRelationSchema($name) {
			if (!$this->isExistsRelation($name)) {
				throw new \ErrorException(sprintf('Incorrect relation name given: "%s"', $name));
			}

			return $this->getRelationSchemaList()[$name];
		}
	}