<?php
	namespace UmiCms\System\Events;

	/**
	 * Интерфейс обработчика события
	 * @package UmiCms\System\Events
	 */
	interface iHandler {

		/**
		 * Устанавливает идентификатор события
		 * @param string $id
		 * @return $this|iHandler
		 */
		public function setEventId(string $id) : iHandler;

		/**
		 * Возвращает идентификатор события
		 * @return string|null строковой id события
		 */
		public function getEventId() : ?string;

		/**
		 * Устанавливает критичность обработчика события.
		 * Если событие критично, то при возникновении любого исключения в этом обработчике,
		 * цепочка вызова обработчиков событий будет прервана.
		 * @param bool|mixed $isCritical = false критичность обработчика
		 * @return iHandler
		 */
		public function setIsCritical($isCritical = false) : iHandler;

		/**
		 * Получить критичность обработчика события
		 * @return bool критичность обработчика события
		 */
		public function getIsCritical() : bool;

		/**
		 * Устанавливает приоритет обработчика события.
		 * @param int|mixed $priority = 5 приоритет от 0 до 9
		 * @return iHandler
		 * @throws \coreException
		 */
		public function setPriority($priority = 5) : iHandler;

		/**
		 * Возвращает приоритет обработчика событий
		 * @return int
		 */
		public function getPriority() : int;

		/**
		 * Определяет разрешено ли выполнять обработчик
		 * @param array $control контрольные параметры
		 * @return bool
		 *@example [
		 *		'modules' => [
		 * 			'news'
		 * 		],
		 * 		'methods' => [
		 * 			'news::feedsImportListener'
		 * 		]
		 * ]
		 */
		public function isAllowed(array $control) : bool;
	}