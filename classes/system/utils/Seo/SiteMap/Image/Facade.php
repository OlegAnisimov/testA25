<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\Facade as AbstractFacade;

	/**
	 * Класс фасада изображений
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Facade extends AbstractFacade implements iFacade {

		/** @var iExtractor $imageExtractor извлекатель изображений */
		private $imageExtractor;

		/** @inheritDoc */
		public function setImageExtractor(iExtractor $imageExtractor) : iFacade {
			$this->imageExtractor = $imageExtractor;
			return $this;
		}

		/** @inheritDoc */
		public function create(array $attributeList = []) {
			if (!isset($attributeList[iMapper::LOCATION_ID])) {
				throw new \ErrorException('Location id expected');
			}

			if (!isset($attributeList[iMapper::DOMAIN_ID])) {
				throw new \ErrorException('Domain id expected');
			}

			if (!isset($attributeList[iMapper::LINK])) {
				throw new \ErrorException('Source expected');
			}

			return parent::create($attributeList);
		}

		/** @inheritDoc */
		public function createByLocation(iLocation $location) : array {
			$domain = $location->getDomain();
			$imageList = [];
			foreach ($this->imageExtractor->extract($location) as $image) {
				$imageList[] = $this->create([
					iMapper::LOCATION_ID => $location->getId(),
					iMapper::DOMAIN_ID => $location->getDomainId(),
					iMapper::LINK => $image->getUrl($domain),
					iMapper::ALT => $image->getAlt(),
					iMapper::TITLE => $image->getTitle()
				]);
			}
			return $imageList;
		}

		/** @inheritDoc */
		public function deleteByDomain(int $id) : iFacade {
			$this->getRepository()
				->deleteByDomain($id);
			return $this;
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iImage;
		}
	}