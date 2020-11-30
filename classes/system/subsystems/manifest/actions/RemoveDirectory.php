<?php

	/** Команда рекурсивного удаления директории */
	class RemoveDirectoryAction extends Action {

		/** @inheritDoc */
		public function execute() {
			$targetDirectory = $this->getParam('target-directory');
			$this->removeDirectory($targetDirectory);
		}

		/** @inheritDoc */
		public function rollback() {
		}
	}
