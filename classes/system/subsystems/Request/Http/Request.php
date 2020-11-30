<?php

	namespace UmiCms\System\Request\Http;

	/**
	 * Класс http запроса
	 * @package UmiCms\System\Request\Http
	 */
	class Request implements iRequest {

		/** @var iCookies $cookies контейнер кук запроса */
		private $cookies;

		/** @var iServer $server контейнер серверных переменных */
		private $server;

		/** @var iPost $post контейнер POST параметров */
		private $post;

		/** @var iGet $get контейнер GET параметров */
		private $get;

		/** @var iFiles $files контейнер загруженных файлов */
		private $files;

		/** @inheritDoc */
		public function __construct(iCookies $cookies, iServer $server, iPost $post, iGet $get, iFiles $files) {
			$this->cookies = $cookies;
			$this->server = $server;
			$this->post = $post;
			$this->get = $get;
			$this->files = $files;
		}

		/** @inheritDoc */
		public function Cookies() {
			return $this->cookies;
		}

		/** @inheritDoc */
		public function Server() {
			return $this->server;
		}

		/** @inheritDoc */
		public function Post() {
			return $this->post;
		}

		/** @inheritDoc */
		public function Get() {
			return $this->get;
		}

		/** @inheritDoc */
		public function Files() {
			return $this->files;
		}

		/** @inheritDoc */
		public function method() {
			return $this->Server()->get('REQUEST_METHOD');
		}

		/** @inheritDoc */
		public function isPost() {
			return $this->method() === 'POST';
		}

		/** @inheritDoc */
		public function isGet() {
			return $this->method() === 'GET';
		}

		/** @inheritDoc */
		public function host() {
			return $this->Server()->get('HTTP_HOST');
		}

		/** @inheritDoc */
		public function userAgent() {
			return $this->Server()->get('HTTP_USER_AGENT');
		}

		/** @inheritDoc */
		public function remoteAddress() {
			return $this->server->get('HTTP_X_REAL_IP') ?: $this->server->get('REMOTE_ADDR');
		}

		/** @inheritDoc */
		public function serverAddress() {
			return $this->Server()->get('SERVER_ADDR');
		}

		/** @inheritDoc */
		public function uri() {
			return $this->Server()->get('REQUEST_URI');
		}

		/** @inheritDoc */
		public function query() {
			return $this->Server()->get('QUERY_STRING');
		}

		/** @inheritDoc */
		public function referrer() {
			return $this->Server()->get('HTTP_REFERER');
		}

		/** @inheritDoc */
		public function origin() : ?string {
			return $this->Server()->get('HTTP_ORIGIN');
		}

		/** @inheritDoc */
		public function getRawBody() {
			return file_get_contents('php://input');
		}
	}
