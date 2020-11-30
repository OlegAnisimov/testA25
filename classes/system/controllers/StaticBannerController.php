<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iCmsController as iModuleLoader;
	use \umiTemplaterPHP as iPhpTemplateEngine;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Класс контроллера загрузчика баннеров
	 * @package UmiCms\Classes\System\Controllers
	 */
	class StaticBannerController extends AbstractController implements iStaticBannerController {

		/** @var iModuleLoader $moduleLoader загрузчик модулей */
		private $moduleLoader;

		/** @var iPhpTemplateEngine $phpTemplateEngine php шаблонизатор */
		private $phpTemplateEngine;

		/** @var string TEMPLATE_PATH путь до шаблона  */
		const TEMPLATE_PATH = './styles/common/phtml/static_banner.phtml';

		/** @inheritDoc */
		public function setModuleLoader(iModuleLoader $moduleLoader) {
			$this->moduleLoader = $moduleLoader;
			return $this;
		}

		/** @inheritDoc */
		public function setPhpTemplateEngine(iTemplateEngineFactory $factory) {
			$this->phpTemplateEngine = $factory->createPhp(self::TEMPLATE_PATH);
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();
			$banners = $this->moduleLoader->getModule('banners');

			if (!$banners instanceof \banners) {
				$this->buffer->stop();
			}

			$get = $this->request->Get();
			$place = addslashes($get->get('place'));
			$currentElementId = (int) $get->get('current_element_id');

			/** @var \BannersMacros $banners */
			$banner = $banners->insert($place, 0, false, $currentElementId);
			$banner = trim($banner);
			$banner = str_replace('&amp;', '&', htmlspecialchars($banner));
			$banner = str_replace('\"', '"', $banner);

			$variables = [
				'place' => $place,
				'banner' => $banner
			];

			$content = $this->phpTemplateEngine->parse($variables);

			$this->buffer->contentType('text/javascript');
			$this->buffer->charset('utf-8');
			$this->buffer->push($content);
			$this->buffer->end();
		}
	}