<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use UmiCms\System\Streams\Umap\iFacade as iUmapFacade;

	/**
	 * Интерфейс посредника в маршрутизации запросов по протоколу umap
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	interface iUmapRouter {

		/**
		 * Устанавливает фасад umap-маршрутизатора
		 * @param iUmapFacade $umapFacade фасад umap-маршрутизатора
		 * @return $this
		 */
		public function setUmapFacade(iUmapFacade $umapFacade);

		/**
		 * Обрабатывает запросы по протоколу Umap
		 * @link http://dev.docs.umi-cms.ru/shablony_i_makrosy/xslt-shablonizator_umi_cms/formirovanie_dannyh_na_servere_protokol_umap/
		 * @throws \Exception
		 */
		public function executeUmap();
	}