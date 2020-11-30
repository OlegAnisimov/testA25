<?php

	/** Команда удаления файлов */
	class DeleteFilesAction extends Action {

		/** @inheritDoc */
		public function execute() {
			$path = $this->getParam('target-directory');
			$pattern = $this->getParam('pattern');

			$directory = new umiDirectory($path);
			$directory->deleteFilesByPattern($pattern);
		}

		/** @inheritDoc */
		public function rollback() {
		}
	}
