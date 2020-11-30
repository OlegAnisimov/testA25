<?php
	namespace UmiCms\Classes\System\Utils\SiteMap;

	use \iConfiguration as iConfig;
	use \iUmiHierarchy as iPageFacade;
	use \iUmiHierarchyElement as iPage;
	use UmiCms\System\Events\iEventPointFactory as iEventFactory;
	use UmiCms\Classes\System\Utils\SiteMap\Image\iFacade as iImageFacade;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iFacade as iLocationFacade;

	/**
	 * Класс обновления карты сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap
	 */
	class Updater implements iUpdater {

		/** @var string DEFAULT_CHANGE_FREQUENCY вероятная частота изменения по-умолчанию */
		const DEFAULT_CHANGE_FREQUENCY = 'weekly';

		/** @var iLocationFacade $locationFacade фасад адресов карты сайта */
		private $locationFacade;

		/** @var iPageFacade $pageFacade фасад страниц */
		private $pageFacade;

		/** @var iEventFactory $eventFactory фабрика событий */
		private $eventFactory;

		/** @var iImageFacade $imageFacade фасад изображений карты сайта */
		private $imageFacade;

		/** @var iConfig $config конфигурация */
		private $config;

		/** @inheritDoc */
		public function __construct(
			iLocationFacade $locationFacade,
			iPageFacade $hierarchy,
			iEventFactory $eventFactory,
			iImageFacade $imageFacade,
			iConfig $config
		) {
			$this->locationFacade = $locationFacade;
			$this->pageFacade = $hierarchy;
			$this->eventFactory = $eventFactory;
			$this->imageFacade = $imageFacade;
			$this->config = $config;
		}

		/** @inheritDoc */
		public function update(iPage $page) {
			$pageId = (int) $page->getId();
			$link = $this->getLink($pageId);
			$updateTime = date('Y-m-d H:i:s', $page->getUpdateTime());
			$priority = (float) $this->getPriority($pageId);
			$maxLevel = (int) $this->getMaxLevel($page);
			$domainId = (int) $page->getDomainId();
			$langId = (int) $page->getLangId();
			$changeFrequency = (string) $this->config
				->get('site-map', 'site-map-url-change-frequency', self::DEFAULT_CHANGE_FREQUENCY);
			$robotsDeny = $this->getRobotsDeny($page);
			$isHiddenType = $this->isHiddenType($page);
			mt_srand();
			$sortIndex = mt_rand(0, 16);

			$this->delete($pageId);

			$event = $this->eventFactory->create('before_update_sitemap', 'before')
				->setParam('id', $pageId)
				->setParam('page', $page)
				->setParam('domainId', $domainId)
				->setParam('langId', $langId)
				->addRef('link', $link)
				->addRef('pagePriority', $priority)
				->addRef('changeFrequency', $changeFrequency)
				->setParam('updateTime', $updateTime)
				->setParam('level', $maxLevel)
				->setParam('sort', $sortIndex)
				->setParam('is_hidden_type', $isHiddenType)
				->addRef('robots_deny', $robotsDeny);
			$event->call();
			$event->setParam('link', $link)
				->setParam('pagePriority', $priority)
				->setParam('changeFrequency', $changeFrequency);
			$isHiddenType = $event->getParam('is_hidden_type');

			if ($page->getIsActive() && !$robotsDeny && !$page->getIsDeleted() && $link && !$isHiddenType) {
				$this->locationFacade->createByEvent($event);
			}

			return $this;
		}

		/** @inheritDoc */
		public function updateImages(iPage $page) : iUpdater {
			$location = $this->locationFacade->get($page->getId());

			if (!$location instanceof iLocation) {
				return $this;
			}

			$imageList = $this->imageFacade->createByLocation($location);

			$event = $this->eventFactory->create('update_sitemap_image', 'after')
				->setParam('location', $location)
				->setParam('image_list', $imageList);
			$event->call();

			return $this;
		}

		/** @inheritDoc */
		public function updateList(array $pageList) {
			foreach ($pageList as $page) {
				if (!$page instanceof iPage) {
					continue;
				}

				$this->update($page);
			}

			return $this;
		}

		/** @inheritDoc */
		public function updateWithImagesList(array $pageList) : iUpdater {
			foreach ($pageList as $page) {
				if (!$page instanceof iPage) {
					continue;
				}

				$this->updateImages($page);
			}

			return $this;
		}

		/** @inheritDoc */
		public function delete($pageId) {
			$this->locationFacade->delete($pageId);
			return $this;
		}

		/** @inheritDoc */
		public function deleteList(array $pageIdList) {
			if (empty($pageIdList)) {
				return $this;
			}

			$escapedIdList = array_map(function ($id) {
					return (int) $id;
			}, $pageIdList);

			$this->locationFacade->deleteList($escapedIdList);
			return $this;
		}

		/** @inheritDoc */
		public function deleteAll() {
			$this->imageFacade->deleteAll();
			$this->locationFacade->deleteAll();
			return $this;
		}

		/** @inheritDoc */
		public function deleteByDomain($id) {
			$this->locationFacade->deleteByDomain($id);
			return $this;
		}

		/** @inheritDoc */
		public function deleteImagesByDomain(int $id) : iUpdater {
			$this->imageFacade->deleteByDomain($id);
			return $this;
		}

		/**
		 * Определяет заблокирована ли страница в robots.txt
		 * @param iPage $page страница
		 * @return bool
		 */
		private function getRobotsDeny(iPage $page) {
			$parentIdList = $this->pageFacade->getAllParents($page->getId(), true);
			$parentList = $this->pageFacade->loadElements($parentIdList);

			foreach ($parentList as $parent) {
				if ($parent->getValue('robots_deny')) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Определяет принадлежит ли страница к типу, страницы которого нельзя отдавать
		 * @param iPage $page страница
		 * @return bool
		 */
		private function isHiddenType(iPage $page) : bool {
			return $page->getHierarchyType()->hidePages();
		}

		/**
		 * Возвращает ссылку на страницу
		 * @param int $pageId идентификатор страницы
		 * @return string
		 */
		private function getLink($pageId) {
			$oldValue = $this->pageFacade->forceAbsolutePath();

			$ignoreLangPrefix = false;
			$ignoreDefaultStatus = false;
			$ignoreCache = true;
			$link = $this->pageFacade->getPathById($pageId, $ignoreLangPrefix, $ignoreDefaultStatus, $ignoreCache);

			$this->pageFacade->forceAbsolutePath($oldValue);
			return $link;
		}

		/**
		 * Возвращает приоритет просмотра страницы поисковым роботом
		 * @param int $pageId идентификатор страницы
		 * @return float
		 * @throws \databaseException
		 */
		private function getPriority($pageId) {
			$pageId = (int) $pageId;
			$level = $this->pageFacade->getLevel($pageId);
			$pagePriority = round(1 / ($level + 1), 1);

			if ($pagePriority < 0.1) {
				$pagePriority = 0.1;
			}

			return $pagePriority;
		}

		/**
		 * Возвращает максимальный уровень вложенности относительно страницы
		 * @param iPage $page
		 * @return int
		 * @throws \publicAdminException
		 */
		private function getMaxLevel(iPage $page) {
			return $page->getIsDefault() ? 0 : (int) $this->pageFacade->getMaxDepth($page->getId(), 1);
		}
	}
