<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Location;

	use UmiCms\System\Orm\iEntity;
	use \iUmiEventPoint as iEvent;
	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\System\Orm\Entity\Facade as AbstractFacade;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iFacade as iImageFacade;

	/**
	 * Класс фасада адресов карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Location
	 */
	class Facade extends AbstractFacade implements iFacade {

		/** @var iImageFacade $imageFacade фасад изображений */
		private $imageFacade;

		/** @inheritDoc */
		public function create(array $attributeList = []) {
			if (!isset($attributeList[iMapper::DOMAIN_ID])) {
				throw new \ErrorException('Domain id expected');
			}

			if (!isset($attributeList[iMapper::LINK])) {
				throw new \ErrorException('Link expected');
			}

			if (!isset($attributeList[iMapper::SORT])) {
				throw new \ErrorException('Sort index expected');
			}

			if (!isset($attributeList[iMapper::PRIORITY])) {
				throw new \ErrorException('Priority expected');
			}

			if (!isset($attributeList[iMapper::DATE_TIME])) {
				throw new \ErrorException('Date time expected');
			}

			if (!isset($attributeList[iMapper::LEVEL])) {
				throw new \ErrorException('Level expected');
			}

			if (!isset($attributeList[iMapper::LANGUAGE_ID])) {
				throw new \ErrorException('Language id expected');
			}

			return parent::create($attributeList);
		}

		/** @inheritDoc */
		public function save(iEntity $location) {
			/** @var iLocation $location */
			parent::save($location);

			foreach ($location->getImageCollection() as $image) {
				$this->imageFacade->save($image);
			}

			return $this;
		}

		/** @inheritDoc */
		public function delete($id) {
			/** @var iLocation $location */
			$location = parent::get($id);

			if ($this->isValidEntity($location)) {
				$imageIdList = $location->getImageCollection()
					->extractId();
				$this->imageFacade->deleteList($imageIdList);
			}

			return parent::delete($id);
		}

		/** @inheritDoc */
		public function copy(iEntity $sourceLocation) {
			/** @var iLocation $sourceLocation */
			$attributeList = $this->extractAttributeList($sourceLocation);
			$locationCopy = $this->create($attributeList);

			try {
				foreach ($sourceLocation->getImageCollection() as $sourceImage) {
					/** @var iImage $imageCopy */
					$imageCopy = $this->imageFacade->copy($sourceImage);
					$imageCopy->setLocationId($locationCopy->getId());
					$this->imageFacade->save($imageCopy);
				}

			} catch (\Exception $exception) {
				if ($locationCopy instanceof iLocation) {
					$this->delete($locationCopy->getId());
				}

				throw $exception;
			}

			return $locationCopy;
		}

		/** @inheritDoc */
		public function createByEvent(iEvent $event) : iLocation {
			return $this->create([
				iMapper::ID => $event->getParam('id'),
				iMapper::DOMAIN_ID => $event->getParam('domainId'),
				iMapper::LINK => $event->getParam('link'),
				iMapper::SORT => $event->getParam('sort'),
				iMapper::PRIORITY => $event->getParam('pagePriority'),
				iMapper::DATE_TIME => $event->getParam('updateTime'),
				iMapper::LEVEL => $event->getParam('level'),
				iMapper::LANGUAGE_ID => $event->getParam('langId'),
				iMapper::CHANGE_FREQUENCY => $event->getParam('changeFrequency')
			]);
		}

		/** @inheritDoc */
		public function getIndexListByDomain(int $id) : array {
			return $this->getRepository()
				->getIndexListForDomain($id);
		}

		/** @inheritDoc */
		public function getCountByDomain(int $id) : int {
			return $this->getRepository()
				->getCountByDomain($id);
		}

		/** @inheritDoc */
		public function getCollectionByDomain(int $id) : iCollection {
			 $list = $this->getRepository()
				 ->getListByDomain($id);
			 return $this->mapCollection($list);
		}

		/** @inheritDoc */
		public function getCollectionByDomainAndSort(int $id, int $sort) : iCollection {
			$list = $this->getRepository()
				->getListByDomainAndSort($id, $sort);
			return $this->mapCollection($list);
		}

		/** @inheritDoc */
		public function deleteByDomain(int $id) : iFacade {
			$this->getRepository()
				->deleteByDomain($id);
			return $this;
		}

		/** @inheritDoc */
		public function setImageFacade(iImageFacade $imageFacade) : iFacade {
			$this->imageFacade = $imageFacade;
			return $this;
		}

		/** @inheritDoc */
		protected function isValidEntity($entity) {
			return $entity instanceof iLocation;
		}
	}