<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image\Attribute;

	use UmiCms\System\Orm\iEntity;
	use UmiCms\System\Orm\Entity\iAccessor;
	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iMapper;
	use UmiCms\System\Orm\Entity\Attribute\Accessor as AbstractAccessor;

	/**
	 * Класс аксессора атрибутов изображения
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image\Attribute
	 */
	class Accessor extends AbstractAccessor implements iAccessor {

		/**
		 * @inheritDoc
		 * @param iImage $entity
		 */
		public function accessOne(iEntity $entity, $name) {
			/** @var iMapper $mapper */
			$mapper = $this->getMapper();

			if ($name === $mapper::ID) { // оптимизация
				return $entity->getId();
			}

			if ($name === $mapper::LOCATION_ID) { // оптимизация
				return $entity->getLocationId();
			}

			return parent::accessOne($entity, $name);
		}
	}