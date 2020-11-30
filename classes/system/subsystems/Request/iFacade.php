<?php

	namespace UmiCms\System\Request;

	use UmiCms\System\Request\Http\iCookies;
	use UmiCms\System\Request\Http\iFiles;
	use UmiCms\System\Request\Http\iGet;
	use UmiCms\System\Request\Http\iPost;
	use UmiCms\System\Request\Http\iRequest;
	use UmiCms\System\Request\Http\iServer;
	use UmiCms\System\Request\Mode\iDetector as ModeDetector;
	use UmiCms\System\Request\Path\iResolver as PathResolver;
	use UmiCms\Utils\Browser\iDetector as BrowserDetector;
	use UmiCms\Classes\System\PageNum\Agent\iFacade as iPageNumAgentFacade;

	/**
	 * Интерфейс фасада запроса
	 * @package UmiCms\System\Request
	 */
	interface iFacade {

		/**
		 * Конструктор
		 * @param iRequest $request класс http запроса
		 * @param BrowserDetector $browserDetector определитель параметров браузера
		 * @param ModeDetector $modeDetector определитель режима работы системы
		 * @param PathResolver $pathResolver распознаватель обрабатываемого пути
		 * @param iPageNumAgentFacade $pageNumAgentFacade фасад агентов пагинации
		 */
		public function __construct(
			iRequest $request,
			BrowserDetector $browserDetector,
			ModeDetector $modeDetector,
			PathResolver $pathResolver,
			iPageNumAgentFacade $pageNumAgentFacade
		);

		/**
		 * Возвращает контейнер кук запроса
		 * @return iCookies
		 */
		public function Cookies();

		/**
		 * Возвращает контейнер серверных переменных
		 * @return iServer
		 */
		public function Server();

		/**
		 * Возвращает контейнер POST параметров
		 * @return iPost
		 */
		public function Post();

		/**
		 * Возвращает контейнер GET параметров
		 * @return iGet
		 */
		public function Get();

		/**
		 * Возвращает контейнер загруженных файлов
		 * @return iFiles
		 */
		public function Files();

		/**
		 * Возвращает метод
		 * @return string
		 */
		public function method();

		/**
		 * Определяет, что запрос произведен методом "POST"
		 * @return bool
		 */
		public function isPost();

		/**
		 * Определяет, что запрос произведен методом "GET"
		 * @return bool
		 */
		public function isGet();

		/**
		 * Определяет работает ли система в режиме панели администрирования
		 * @return bool
		 */
		public function isAdmin();

		/**
		 * Определяет, что система не работает в режиме панели администрирования
		 * @return bool
		 */
		public function isNotAdmin();

		/**
		 * Определяет работает ли система в режиме сайта
		 * @return bool
		 */
		public function isSite();

		/**
		 * Определяет работает ли система в режиме консоли
		 * @return bool
		 */
		public function isCli();

		/**
		 * Определяет режим работы системы
		 * @return string
		 */
		public function mode();

		/**
		 * Возвращает хост
		 * @return string
		 */
		public function host();

		/**
		 * Определяет сделан ли запрос по протоколу https
		 * @return bool
		 */
		public function isHttps() : bool;

		/**
		 * Возвращает uri запроса, @see .htaccess
		 * @return string
		 */
		public function getPath();

		/**
		 * Возвращает номер страницы в рамках пагинации
		 * @return int
		 * @throws \Exception
		 */
		public function pageNumber() : int;

		/**
		 * Определяет задан ли номер страницы в рамках пагинации
		 * @return bool
		 * @throws \Exception
		 */
		public function issetPageNumber() : bool;

		/**
		 * Очищает адрес страницы от параметров пагинации
		 * @param string $uri адрес страницы
		 * @return string
		 * @throws \Exception
		 */
		public function removePageNumber(string $uri) : string;

		/**
		 * Добавляет номер страницы к адресу
		 * @param string $uri адрес
		 * @param int $number номер страницы
		 * @return string
		 * @throws \Exception
		 */
		public function appendPageNumber(string $uri, int $number) : string;

		/**
		 * Возвращает uri без номера страницы в рамках пагинации
		 * @return string
		 * @throws \Exception
		 */
		public function uriWithoutPageNumber() : string;

		/**
		 * Возвращает uri запроса без формата ответа (xml или json) и параметра пагинации
		 * @return string
		 */
		public function getCleanPath();

		/**
		 * Возвращает части пути
		 * @return string[]
		 */
		public function getPathParts();

		/**
		 * Возвращает части пути без формата ответа (xml или json) и параметра пагинации
		 * @return string[]
		 */
		public function getCleanPathParts();

		/**
		 * Возвращает первую часть адреса
		 * @return string
		 */
		public function getFirstPart() : string;

		/**
		 * Определяет запрошен ли поток
		 * @return bool
		 */
		public function isStream();

		/**
		 * Возвращает схему потока
		 * @return string|null
		 */
		public function getStreamScheme();

		/**
		 * Определяет запрошен ли json
		 * @return bool
		 */
		public function isJson();

		/**
		 * Определяет запрошен ли xml
		 * @return bool
		 */
		public function isXml();

		/**
		 * Определяет запрошен ли html, то есть страница сайта
		 * @return bool
		 */
		public function isHtml();

		/**
		 * Определяет запрошена ли мобильная версия
		 * @return bool
		 */
		public function isMobile();

		/**
		 * Определяет обрабатывается ли запрос локальным сервером
		 * @return bool
		 */
		public function isLocal();

		/**
		 * Возвращает название браузера
		 * @return string
		 */
		public function getBrowser();

		/**
		 * Возвращает название операционной системы
		 * @return string
		 */
		public function getPlatform();

		/**
		 * Определяет сделан ли запрос ботом
		 * @return bool
		 */
		public function isRobot();

		/**
		 * Определяет запрошен ли стек вызовов протоколов
		 * @return bool
		 */
		public function isStreamCallStack();

		/**
		 * Возвращает user agent
		 * @return string
		 */
		public function userAgent();

		/**
		 * Возвращает ip адрес отправителя запроса
		 * @return string
		 */
		public function remoteAddress();
		
		/**
		 * Возвращает значение заголовка HTTP_REFERER
		 * @return string
		 */
		public function referrer();

		/**
		 * Возвращает значение заголовка HTTP_ORIGIN
		 * @return string|null
		 */
		public function origin() : ?string;

		/**
		 * Возвращает ip адрес сервера
		 * @return string
		 */
		public function serverAddress();

		/**
		 * Возвращает uri
		 * @return string
		 */
		public function uri();

		/**
		 * Возвращает query
		 * @return string
		 */
		public function query();

		/**
		 * Возвращает время запроса
		 * @return int
		 */
		public function time() : int;

		/**
		 * Возвращает корневую папку
		 * @return string
		 */
		public function documentRoot();

		/**
		 * Возвращает хеш от query
		 * @return string
		 */
		public function queryHash();

		/**
		 * Возвращает необработанные данные тела запроса
		 * @return string
		 */
		public function getRawBody();

		/**
		 * Определяет отправлен ли запрос через ajax
		 * @return bool
		 */
		public function isAjax();

		/**
		 * Определяет отправлен ли запрос от приложения "UMI.Manager"
		 * @return bool
		 */
		public function isUmiManager();
	}
