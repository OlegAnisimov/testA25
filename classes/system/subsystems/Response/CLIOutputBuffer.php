<?php

	/** Класс буфера вывода для командной строки */
	class CLIOutputBuffer extends outputBuffer {

		/** @inheritDoc */
		public function send() {
			echo $this->buffer;
			$this->clear();
		}
	}
