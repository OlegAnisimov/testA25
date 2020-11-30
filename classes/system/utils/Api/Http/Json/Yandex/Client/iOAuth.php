<?php

	namespace UmiCms\Classes\System\Utils\Api\Http\Json\Yandex\Client;

	use UmiCms\Classes\System\Utils\Api\Http\Exception;
	use UmiCms\Utils\Logger\iFactory as iLoggerFactory;

	/**
	 * Интерфейс клиента API Яндекс.OAuth
	 * @link https://tech.yandex.ru/oauth/
	 * @package UmiCms\Classes\System\Utils\Api\Http\Json\Yandex\Client;
	 */
	interface iOAuth {

		/**
		 * Конструктор
		 * @param iLoggerFactory $loggerFactory экземпляр фабрики логгеров
		 * @param \iConfiguration $configuration конфигурация
		 */
		public function __construct(iLoggerFactory $loggerFactory, \iConfiguration $configuration);

		/**
		 * Устанавливает идентификационные параметры приложения
		 * @param string $login логин (идентификатор)
		 * @param string $password пароль
		 * @return $this
		 */
		public function setAuth($login, $password);

		/**
		 * Возвращает авторизационный токен по коду, введенному пользователем
		 * @link https://tech.yandex.ru/oauth/doc/dg/reference/console-client-docpage/
		 * @param int $code числовой код
		 * @return string
		 * @throws Exception\BadRequest
		 * @throws Exception\BadResponse
		 */
		public function getTokenByUserCode($code);
	}
