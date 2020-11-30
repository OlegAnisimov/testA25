<?php

	/** Быстрый csv импортер страниц */
	class QuickCsvPageSplitter extends csvSplitter {

		/** @inheritDoc */
		protected function resetState() {
			$sourceName = $this->sourceName;
			parent::resetState();
			$this->setSourceName($sourceName);
		}
	}
