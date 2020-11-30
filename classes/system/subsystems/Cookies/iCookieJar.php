<?php

	namespace UmiCms\System\Cookies;

	use UmiCms\System\Request\Http\iCookies;
	use UmiCms\System\Protection\iEncrypter;
	use UmiCms\System\Session\iSession;

	/**
	 * Интерфейс фасада для работ с куками
	 * @examples:
	 *
	 * 1) Получить значение куки от клиента: CookieJar->get('foo');
	 * 2) Установить куку для ответа клиенту: CookieJar->set('foo', 'bar', 1488);
	 * 3) Пришла ли кука от клиента: CookieJar->isExists('baz);
	 * 4) Удалить куку: CookieJar->remove('baz);
	 *
	 * @package UmiCms\System\Cookies
	 */
	interface iCookieJar {

		/**
		 * Конструктор
		 * @param iFactory $factory экземпляр класса фабрики кук
		 * @param iResponsePool $responsePool экземпляр класса списка кук, которые требуется отправить клиенту
		 * @param iCookies $requestCookies экземпляр класса контейнера кук запроса
		 * @param iEncrypter $cryptographer шифровальщик
		 * @param iOptions $options опции инициализации
		 */
		public function __construct(
			iFactory $factory,
			iResponsePool $responsePool,
			iCookies $requestCookies,
			iEncrypter $cryptographer,
			iOptions $options
		);

		/**
		 * Возвращает значение куки из http запроса
		 * @param string $name имя куки
		 * @return string|null
		 */
		public function get($name);

		/**
		 * Возвращает дешифрованное значение куки из http запроса
		 * @param string $name имя куки
		 * @return string|null
		 */
		public function getDecrypted($name);

		/**
		 * Устанавливает куку
		 * @param string $name имя куки
		 * @param string $value значение куки
		 * @param int $expireTime время, когда срок действия куки истекает
		 * @return iCookie
		 * @throws \wrongParamException
		 */
		public function set($name, $value = '', $expireTime = 0);

		/**
		 * Устанавливает зашифрованную куку
		 * @param string $name имя куки
		 * @param string $value значение куки
		 * @param int $expireTime время, когда срок действия куки истекает
		 * @return iCookie
		 * @throws \wrongParamException
		 * @throws \ErrorException
		 */
		public function setEncrypted($name, $value = '', $expireTime = 0);

		/**
		 * Устанавливает куку на основе данных заголовка куки
		 * @param string $header заголовок куки
		 * @return iCookie
		 * @throws \wrongParamException
		 */
		public function setFromHeader($header);

		/**
		 * Определяет была ли передана кука в http запросе
		 * @param string $name имя куки
		 * @return bool
		 */
		public function isExists($name);

		/**
		 * Удаляет куку из контейнера кук http запроса и устанавливает куку с отрицательным именем жизни
		 * @param string $name имя куки
		 * @return iCookieJar
		 */
		public function remove($name);

		/**
		 * Возвращает экземпляр класса списка кук, которые требуется отправить клиенту
		 * @return iResponsePool
		 */
		public function getResponsePool();

		/**
		 * Возвращает опции куки сессии
		 * @param iSession $session сессия
		 * @return array
		 */
		public function getSessionOptions(iSession $session) : array;

		/**
		 * Возвращает опции куки
		 * @param iCookie $cookie кука
		 * @return array
		 */
		public function getCookieOptions(iCookie $cookie) : array;
	}
