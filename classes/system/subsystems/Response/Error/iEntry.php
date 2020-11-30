<?php
	namespace UmiCms\System\Response\Error;

	use UmiCms\System\Orm\iEntity;

	/**
	 * Интерфейс записи об обработке ответа с ошибкой
	 * @package UmiCms\System\Response\Error
	 */
	interface iEntry extends iEntity {

		/**
		 * Конструктор
		 * @param \iDomainsCollection $domainCollection коллекция доменов
		 */
		public function __construct(\iDomainsCollection $domainCollection);

		/**
		 * Устанавливает адрес запроса
		 * @param string $url адрес запроса
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setUrl($url);

		/**
		 * Возвращает адрес запроса
		 * @return string
		 */
		public function getUrl();

		/**
		 * Устанавливает код статуса ответа
		 * @param int $code код статуса ответа
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setCode($code);

		/**
		 * Возвращает код статуса ответа
		 * @return int
		 */
		public function getCode();

		/**
		 * Устанавливает количество обращений
		 * @param int $count количество
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setHitsCount($count);

		/**
		 * Возвращает количество обращений
		 * @return int
		 */
		public function getHitsCount();

		/**
		 * Увеличивает количество обращений на одну единицу
		 * @return $this
		 * @throws \ErrorException
		 */
		public function incrementHitsCount();

		/**
		 * Возвращает идентификатор домена
		 * @return int
		 */
		public function getDomainId();

		/**
		 * Устанавливает идентификатор домена
		 * @param int $id идентификатор домена
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setDomainId($id);

		/**
		 * Возвращает время обновления записи
		 * @return int
		 */
		public function getUpdateTime();

		/**
		 * Устанавливает время обновления записи
		 * @param int $timestamp время обновления
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setUpdateTime($timestamp);
	}