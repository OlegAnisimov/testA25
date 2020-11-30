<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\System\Streams\Umap\iFacade as iUmapFacade;
	use UmiCms\Classes\System\Controllers\AbstractController;

	/**
	 * Трейт посредника в маршрутизации запросов по протоколу umap
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	trait tUmapRouter {

		/** @var iUmapFacade $umapFacade фасад umap-маршрутизатора */
		protected $umapFacade;

		/** @inheritDoc */
		public function setUmapFacade(iUmapFacade $umapFacade) {
			$this->umapFacade = $umapFacade;
			return $this;
		}

		/**
		 * @inheritDoc
		 * @throws \Exception
		 */
		public function executeUmap() {
			if (!$this->umapFacade->isEnabled()) {
				return;
			}

			/** @var AbstractController $this */
			$path = $this->getRequest()->getPath();
			$this->umapFacade->execute($path);
		}
	}