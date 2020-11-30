<?php

	/** Быстрый csv импортер объектов */
	class QuickCsvObjectSplitter extends csvSplitter {

		/** @inheritDoc */
		protected $simpleAttributeList = ['id', 'name'];

		/** @inheritDoc */
		protected $specialAttributeList = ['type-id'];

		/** @inheritDoc */
		protected function resetState() {
			$sourceName = $this->sourceName;
			parent::resetState();
			$this->setSourceName($sourceName);
		}

		/** @inheritDoc */
		protected function createDocumentSkeleton() {
			parent::createDocumentSkeleton();
			$doc = $this->document;
			$rootNode = $doc->getElementsByTagName('umidump')->item(0);
			$rootNode->appendChild($doc->createElement('objects'));
		}

		/** @inheritDoc */
		protected function translateEntity($cells) {
			$typeId = $this->determineObjectType($cells);
			$this->determineEntityId($cells);
			$name = $this->getObjectName($cells);
			$this->initializeObject($typeId, $name);
			$this->translatePropertyList($cells, $typeId);
		}

		/** @inheritDoc */
		protected function isEntityChildNode($name) {
			return false;
		}

		/**
		 * Возвращает имя объекта
		 * @param string[] $cells CSV-ячейки
		 * @return string
		 */
		private function getObjectName($cells) {
			$key = array_search('name', $this->nameList);

			if ($key !== false && $cells[$key]) {
				return $cells[$key];
			}

			return '';
		}

		/**
		 * Определяет идентификатор объектного типа импортируемых объектов
		 * @param string[] $cells CSV-ячейки
		 * @return bool|int
		 */
		private function determineObjectType($cells) {
			$typeId = $this->determineEntityTypeFromExternalType($cells);

			$sourceId = $this->getSourceId();

			if ($typeId != $this->relations->getNewTypeIdRelation($sourceId, $typeId)) {
				$this->relations->setTypeIdRelation($sourceId, $typeId, $typeId);
			}

			return $typeId;
		}

		/**
		 * Создает узел для текущего объекта и заполняет значение атрибутов 'type-id' и "name"
		 * @param int $typeId идентификатор типа объекта
		 * @param string $name имя объекта
		 */
		private function initializeObject($typeId, $name) {
			$this->entity = $this->document->createElement('object');
			$this->entity->setAttribute('type-id', $typeId);
			$this->entity->setAttribute('name', $name);

			$objects = $this->document->getElementsByTagName('objects')->item(0);
			$objects->appendChild($this->entity);

			$this->group = $this->document->createElement('group');
			$this->group->setAttribute('name', 'newGroup');

			$propertiesNode = $this->document->createElement('properties');
			$propertiesNode->appendChild($this->group);
			$this->entity->appendChild($propertiesNode);
		}
	}
