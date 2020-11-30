<?php

	interface iUmiMail {

		public function __construct($template = 'default');

		/**
		 * Определяет нужно ли разбирать контент письма, @see umiMail::parseContet()
		 * @return bool
		 */
		public function isNeedToParseContent();

		/**
		 * Устанавливает нужно ли разбирать контент письма
		 * @param bool $flag
		 * @return $this
		 */
		public function setNeedToParseContent($flag = true);

		public function addRecipient($recipientEmail, $recipientName = false);

		/**
		 * Определяет, есть ли у письма получатели
		 * @return boolean
		 */
		public function hasRecipients();

		public function setFrom($fromEmail, $fromName = false);

		public function setContent($contentString, $parseTplMacros = true);

		public function setTxtContent($sTxtContent);

		public function setSubject($subjectString);

		public function setPriorityLevel($priorityLevel = 'normal');

		public function setImportanceLevel($importanceLevel = 'normal');

		/**
		 * Устанавливает значение заголовка List-Unsubscribe согласно RFC8058
		 * @param string|array $listUnsubscribe ссылка и/или email для отписки от рассылки 
		 */
		public function setListUnsubscribe($listUnsubscribe);

		/**
		 * Устанавливает флаг необходимости сгенерировать текстовую версию из HTML версии письма
		 * @param bool $flag значение флага
		 */
		public function setGenerateTxtBody($flag);

		public function getHeaders($arrXHeaders = [], $bOverwrite = false);

		public function attachFile(iUmiFile $file);

		public function commit();

		public function send();

		public static function clearFilesCache();

		public static function checkEmail($emailString);
	}
