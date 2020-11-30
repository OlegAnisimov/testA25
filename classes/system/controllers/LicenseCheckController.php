<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iRegedit as iRegistry;
	use \iDomainsCollection as iDomainFacade;
	use \umiTemplaterPHP as iPhpTemplateEngine;
	use UmiCms\Classes\System\Template\Engine\iFactory as iTemplateEngineFactory;

	/**
	 * Класс контроллера проверки лицензии
	 * @package UmiCms\Classes\System\Controllers
	 */
	class LicenseCheckController extends AbstractController implements iLicenseCheckController {

		/** @var iRegistry $registry реестр */
		private $registry;

		/** @var iDomainFacade $domainFacade фасад доменов */
		private $domainFacade;

		/** @var iPhpTemplateEngine $phpTemplateEngine php шаблонизатор */
		private $phpTemplateEngine;

		/** @var string TEMPLATE_PATH путь до шаблона  */
		const TEMPLATE_PATH = './styles/common/phtml/license_check.phtml';

		/** @inheritDoc */
		public function setRegistry(iRegistry $registry) {
			$this->registry = $registry;
			return $this;
		}

		/** @inheritDoc */
		public function setDomainFacade(iDomainFacade $domainFacade) {
			$this->domainFacade = $domainFacade;
			return $this;
		}

		/** @inheritDoc */
		public function setPhpTemplateEngine(iTemplateEngineFactory $factory) {
			$this->phpTemplateEngine = $factory->createPhp(self::TEMPLATE_PATH);
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			$variables = [
				'incorrect_key' => $this->registry->checkSelfKeycode(),
				'default_host' => $this->domainFacade->getDefaultDomain()->getHost(),
				'ip' => $this->request->serverAddress() ?: $this->request->documentRoot(),
				'host' => $this->request->host()
			];

			$content = $this->phpTemplateEngine->parse($variables);

			$this->buffer->contentType('application/javascript');
			$this->buffer->charset('utf-8');
			$this->buffer->setHeader('X-Robots-Tag', 'none');
			$this->buffer->push($content);
			$this->buffer->end();
		}
	}