<?php

	namespace UmiCms\System\Cookies;

	/**
	 * Интерфейс куки
	 * @package UmiCms\System\Cookies
	 */
	interface iCookie {

		/** @var string SAME_SITE_NONE @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite */
		const SAME_SITE_NONE = 'None';

		/** @var string SAME_SITE_STRICT @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite */
		const SAME_SITE_STRICT = 'Strict';

		/** @var string SAME_SITE_LAX @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite */
		const SAME_SITE_LAX = 'Lax';

		/** @var string[] SAME_SITE_WHITE_LIST белый список значений для параметра SameSite */
		const SAME_SITE_WHITE_LIST = [
			self::SAME_SITE_NONE,
			self::SAME_SITE_LAX,
			self::SAME_SITE_STRICT
		];

		/**
		 * Конструктор
		 * @param string $name название
		 * @param mixed $value значение
		 * @param int $expirationTime время, когда срок действия истекает
		 */
		public function __construct($name, $value = '', $expirationTime = 0);

		/**
		 * Возвращает название
		 * @return string
		 */
		public function getName();

		/**
		 * Возвращает значение
		 * @return mixed
		 */
		public function getValue();

		/**
		 * Устанавливает значение
		 * @param mixed $value значение
		 * @return iCookie
		 */
		public function setValue($value);

		/**
		 * время, когда срок действия истекает
		 * @return int
		 */
		public function getExpirationTime();

		/**
		 * Устанавливает время, когда срок действия истекает
		 * @param int $time время
		 * @return iCookie
		 */
		public function setExpirationTime($time);

		/**
		 * Возвращает uri, в рамках которого будет действовать кука
		 * @return string
		 */
		public function getPath();

		/**
		 * Устанавливает uri, в рамках которого будет действовать кука
		 * @param string $path uri
		 * @return iCookie
		 */
		public function setPath($path);

		/**
		 * Возвращает домен (поддомен), в рамках которого будет действовать кука
		 * @return string
		 */
		public function getDomain();

		/**
		 * Устанавливает домен (поддомен), в рамках которого будет действовать кука
		 * @param string|null $domain
		 * @return iCookie
		 */
		public function setDomain($domain);

		/**
		 * Определяет, что куку можно использовать только по https
		 * @return bool
		 */
		public function isSecure();

		/**
		 * Устанавливает флаг, что куку можно использовать только по https
		 * @param bool $flag
		 * @return iCookie
		 */
		public function setSecureFlag($flag);

		/**
		 * Определяет, что кука будет доступна только через протокол HTTP, то есть к ней не будет
		 * доступа из javascript
		 * @return bool
		 */
		public function isForHttpOnly();

		/**
		 * Устанавливает флаг, что кука будет доступна только через протокол HTTP, то есть к ней не будет
		 * доступа из javascript
		 * @param bool $flag
		 * @return iCookie
		 */
		public function setHttpOnlyFlag($flag);

		/**
		 * Возвращает атрибут "SameSite"
		 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
		 * @return string
		 */
		public function getSameSite() : string;

		/**
		 * Устанавливает атрибут "SameSite"
		 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
		 * @param string $value значение атрибута (None/Strict/Lax)
		 * @return $this|iCookie
		 * @throws \wrongParamException
		 */
		public function setSameSite(string $value) : iCookie;
	}
