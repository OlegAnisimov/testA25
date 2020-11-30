<?php
	namespace UmiCms\System\Cookies;

	use UmiCms\System\Session\iSession;
	use UmiCms\System\Hierarchy\Domain\iDetector;

	/**
	 * Интерфейс опций инициализации кук
	 * @package UmiCms\System\Cookies
	 */
	interface iOptions {

		/** @var string SERVICE_NAME имя сервиса */
		const SERVICE_NAME = 'CookieOptions';

		/**
		 * Конструктор
		 * @param iDetector $domainDetector определитель домена
		 */
		public function __construct(iDetector $domainDetector);

		/**
		 * Возвращает опции инициализации конкретной куки
		 * @param iCookie $cookie кука
		 * @return array
		 */
		public function getCustom(iCookie $cookie) : array;

		/**
		 * Возвращает опции инициализации по-умолчанию
		 * @param iSession $session сессия
		 * @return array
		 */
		public function getDefault(iSession $session) : array;
	}