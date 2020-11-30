<?php
	namespace UmiCms\Classes\System\Controllers;

	use \iUmiHierarchy as iPageFacade;

	/**
	 * Класс контроллера сокращателя адресов страниц
	 * @package UmiCms\Classes\System\Controllers
	 */
	class TinyUrlController extends AbstractController implements iTinyUrlController {

		/** @var iPageFacade $pageFacade фасад страниц */
		protected $pageFacade;

		/**
		 * Устанавливает фасад страниц
		 * @param iPageFacade $pageFacade фасад страниц
		 * @return $this
		 */
		public function setPageFacade(iPageFacade $pageFacade) {
			$this->pageFacade = $pageFacade;
			return $this;
		}

		/** @inheritDoc */
		public function execute() {
			parent::execute();

			if (!isset($this->parameters['id'])) {
				throw new \ErrorException('Incorrect router parameters given, id expected');
			}

			$id = (int) $this->parameters['id'];
			$url = $this->pageFacade->getPathById($id);

			if ($url) {
				$this->buffer->redirect($url);
			}

			$this->buffer->status(404);
			$this->buffer->end();
		}
	}