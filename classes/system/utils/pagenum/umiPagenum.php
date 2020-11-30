<?php

	use UmiCms\Service;

	class umiPagenum implements iPagenum {

		/** @deprecated */
		public static $max_pages = 5;

		public static function generateNumPage(
			$total,
			$perPage,
			$template = 'default',
			$pageParam = 'p',
			$maxPages = false
		) {
			$perPage = (int) $perPage;
			$total = (int) $total;

			if ($perPage == 0) {
				$perPage = $total;
			}

			if (!$template) {
				$template = 'default';
			}

			if (!$pageParam) {
				$pageParam = 'p';
			}

			if ($maxPages === false) {
				$maxPages = (int) mainConfiguration::getInstance()
					->get('page-navigation', 'page-number-item-max-count') ?: self::$max_pages;
			}

			list(
				$template_block,
				$template_block_empty,
				$template_item,
				$template_item_a,
				$template_quant,
				$template_tobegin,
				$template_tobegin_a,
				$template_toend,
				$template_toend_a,
				$template_toprev,
				$template_toprev_a,
				$template_tonext,
				$template_tonext_a
				) = def_module::loadTemplates(
				'numpages/' . $template,
				'pages_block',
				'pages_block_empty',
				'pages_item',
				'pages_item_a',
				'pages_quant',
				'pages_tobegin',
				'pages_tobegin_a',
				'pages_toend',
				'pages_toend_a',
				'pages_toprev',
				'pages_toprev_a',
				'pages_tonext',
				'pages_tonext_a'
			);

			$isXslt = def_module::isXSLTResultMode();

			if (self::isCustomPageNumberParam($pageParam)) {
				$currentPage = (int) getRequest($pageParam);
			} else {
				$currentPage = Service::Request()->pageNumber();
			}

			if (self::isInvalidPage($total, $perPage, $currentPage)) {
				return $isXslt ? '' : $template_block_empty;
			}

			$block_arr = [];
			$pages = [];
			$pageCount = (int) ceil($total / $perPage);

			if (!$pageCount) {
				$pageCount = 1;
			}

			for ($i = 0; $i < $pageCount; $i++) {
				$line_arr = [];

				$n = $i + 1;

				if (($currentPage - $maxPages) >= $i) {
					continue;
				}

				if (($currentPage + $maxPages) <= $i) {
					break;
				}

				$tpl = ($i == $currentPage) ? $template_item_a : $template_item;

				$line_arr['attribute:link'] = self::getLocalUri($pageParam, $i);
				$line_arr['attribute:url'] = self::getUrl($pageParam, $i);
				$line_arr['attribute:page-num'] = $i;

				if ($currentPage == $i) {
					$line_arr['attribute:is-active'] = true;
				}

				$line_arr['node:num'] = $n;
				$line_arr['void:quant'] = (($i < (($currentPage + $maxPages) - 1)) && ($i < ($pageCount - 1)))
					? $template_quant
					: '';

				$pages[] = def_module::parseTemplate($tpl, $line_arr);
			}

			$block_arr['subnodes:items'] = $block_arr['void:pages'] = $pages;

			if (!$isXslt) {
				$block_arr['tobegin'] = ($currentPage == 0 || $pageCount <= 1) ? $template_tobegin_a : $template_tobegin;
				$block_arr['toprev'] = ($currentPage == 0 || $pageCount <= 1) ? $template_toprev_a : $template_toprev;
				$block_arr['toend'] = ($currentPage == ($pageCount - 1) || $pageCount <= 1)
					? $template_toend_a
					: $template_toend;
				$block_arr['tonext'] = ($currentPage == ($pageCount - 1) || $pageCount <= 1)
					? $template_tonext_a
					: $template_tonext;
			}

			if ($currentPage != 0) {
				$tobegin_link = self::getLocalUri($pageParam, 0);

				if ($isXslt) {
					$block_arr['tobegin_link'] = [
						'attribute:page-num' => 0,
						'attribute:url' => self::getUrl($pageParam, 0),
						'node:value' => $tobegin_link,
					];
				} else {
					$block_arr['tobegin_link'] = $tobegin_link;
				}
			}

			if ($currentPage < $pageCount - 1) {
				$toend_link = self::getLocalUri($pageParam, ($pageCount - 1));

				if ($isXslt) {
					$block_arr['toend_link'] = [
						'attribute:page-num' => $pageCount - 1,
						'attribute:url' => self::getUrl($pageParam, ($pageCount - 1)),
						'node:value' => $toend_link,
					];
				} else {
					$block_arr['toend_link'] = $toend_link;
				}
			}

			if ($currentPage - 1 >= 0) {
				$toprev_link = self::getLocalUri($pageParam, ($currentPage - 1));

				if ($isXslt) {
					$block_arr['toprev_link'] = [
						'attribute:page-num' => $currentPage - 1,
						'attribute:url' => self::getUrl($pageParam, ($currentPage - 1)),
						'node:value' => $toprev_link,
					];
				} else {
					$block_arr['toprev_link'] = $toprev_link;
				}
			}

			if ($currentPage < $pageCount - 1) {
				$tonext_link = self::getLocalUri($pageParam, ($currentPage + 1));

				if ($isXslt) {
					$block_arr['tonext_link'] = [
						'attribute:page-num' => $currentPage + 1,
						'attribute:url' => self::getUrl($pageParam, ($currentPage + 1)),
						'node:value' => $tonext_link,
					];
				} else {
					$block_arr['tonext_link'] = $tonext_link;
				}
			}

			$block_arr['current-page'] = (int) $currentPage;
			return def_module::parseTemplate($template_block, $block_arr);
		}

		/**
		 * Возвращает локальный адрес номера страницы
		 * @param string $pageParam имя параметра с номером страницы
		 * @param int $number номер страницы
		 * @return string
		 * @throws Exception
		 */
		private static function getLocalUri(string $pageParam, int $number) : string {
			if (self::isCustomPageNumberParam($pageParam)) {
				$getParams = self::getPreparedGetParams($pageParam);
				$queryString = self::getQueryString($getParams);
				return "?{$pageParam}=" . $number . $queryString;
			}

			$request = Service::Request();
			$originalUri = $request->uri();
			$uriWithNumber = $request->appendPageNumber($originalUri, $number);
			$originalUriContainer = Service::UrlFactory()->create($originalUri);
			$originalPath = $originalUriContainer->getPath();
			$uriWithNumber = str_replace($originalPath, '', $uriWithNumber);
			return $uriWithNumber;
		}

		/**
		 * Возвращает абсолютную ссылку с номером страницы
		 * @param string $pageParam имя параметра с номером страницы
		 * @param int $number номер страницы
		 * @return string
		 * @throws coreException
		 */
		private static function getUrl(string $pageParam, int $number) : string {
			$request = Service::Request();
			$originalUri = $request->uri();
			$host = Service::DomainDetector()->detectMirrorUrl();
			$originalUriContainer = Service::UrlFactory()->create($originalUri);
			$originalPath = $originalUriContainer->getPath();
			$cleanOriginalPath = $request->removePageNumber($originalPath);
			$localUri = self::getLocalUri($pageParam, $number);

			if (startsWith($localUri, $cleanOriginalPath)) {
				$localUri = str_replace($cleanOriginalPath, '', $localUri);
			}

			$uri = $cleanOriginalPath . $localUri;
			return $host . str_replace('//', '/', $uri);
		}

		/**
		 * Определяет был ли переопредел параметр номера страницы для метода generateNumPage
		 * @param string $pageParam имя параметра с номером страницы
		 * @return bool
		 */
		private static function isCustomPageNumberParam(string $pageParam) : bool {
			return $pageParam !== 'p';
		}

		public static function generateOrderBy($fieldName, $type_id, $template = 'default') {
			if (!$template) {
				$template = 'default';
			}

			list($template_block, $template_block_a) =
				def_module::loadTemplates('numpages/' . $template, 'order_by', 'order_by_a');

			if (!($type = umiObjectTypesCollection::getInstance()->getType($type_id))) {
				return '';
			}

			$block_arr = [];

			if (($field_id = $type->getFieldId($fieldName)) || ($fieldName == 'name')) {
				$params = $_GET;
				unset($params['umi_authorization']);
				unset($params['path']);

				if (array_key_exists('scheme', $params)) {
					unset($params['scheme']);
				}

				$order_filter = getArrayKey($params, 'order_filter');

				if (is_array($order_filter)) {
					$tpl = array_key_exists($fieldName, $order_filter) ? $template_block_a : $template_block;
				} else {
					$tpl = $template_block;
				}

				unset($params['order_filter']);
				$params['order_filter'][$fieldName] = 1;
				$params = self::protectParams($params);

				$q = umiCount($params) ? http_build_query($params, '', '&') : '';
				$q = urldecode($q);
				$q = str_replace(
					['%', '<', '>', '%3C', '%3E'],
					['&#037;', '&lt;', '&gt;', '&lt;', '&gt;'],
					$q
				);

				$block_arr['link'] = '?' . $q;

				if ($fieldName == 'name') {
					$block_arr['title'] = getLabel('field-name');
				} else {
					$block_arr['title'] = umiFieldsCollection::getInstance()->getField($field_id)->getTitle();
				}

				return def_module::parseTemplate($tpl, $block_arr);
			}

			return '';
		}

		/**
		 * Возвращает подготовленные get-параметры
		 * @param string $pageParam название параметра текущей страницы пагинации (по умолчанию 'p')
		 * @return mixed
		 */
		private static function getPreparedGetParams($pageParam) {
			$params = Service::Request()
				->Get()
				->getArrayCopy();
			$extraParams = [$pageParam, 'path', 'umi_authorization', 'scheme'];

			foreach ($extraParams as $extra) {
				unset($params[$extra]);
			}

			return self::protectParams($params);
		}

		protected static function protectParams($params) {
			foreach ($params as $i => $v) {
				if (is_array($v)) {
					$params[$i] = self::protectParams($v);
				} else {
					$v = htmlspecialchars($v);
					$params[$i] = str_replace(
						['%', '<', '>', '%3C', '%3E'],
						['&#037;', '&lt;', '&gt;', '&lt;', '&gt;'],
						$v
					);
				}
			}

			return $params;
		}

		/**
		 * Возвращает отформатированную строку запроса
		 * @param array $getParams get-параметры
		 * @return mixed|string
		 */
		private static function getQueryString($getParams) {
			$queryString = umiCount($getParams) ? '&' . http_build_query($getParams, '', '&') : '';

			if (!def_module::isXSLTResultMode()) {
				$queryString = str_replace('%', '&#37;', $queryString);
			}

			return str_replace(['<', '>', '%3C', '%3E'], ['&lt;', '&gt;', '&lt;', '&gt;'], $queryString);
		}

		/**
		 * Является ли запрошенная страница пагинации некорректной
		 * @param int $total общее число элементов
		 * @param int $perPage число элементов на одной странице
		 * @param int $currentPage текущая страница пагинации
		 * @return bool
		 */
		private static function isInvalidPage($total, $perPage, $currentPage) {
			if ($total <= 0) {
				return true;
			}

			if ($total <= $perPage) {
				return true;
			}

			return ($currentPage * $perPage) > $total;
		}
	}
