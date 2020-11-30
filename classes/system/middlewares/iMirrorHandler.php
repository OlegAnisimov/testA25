<?php
	namespace UmiCms\Classes\System\MiddleWares;

	use \iDomainsCollection as iDomains;
	use UmiCms\System\Hierarchy\Domain\iDetector as iDomainDetector;

	/**
	 * Интерефейс посредника в обработке запроса с зеркала
	 * @package UmiCms\Classes\System\MiddleWares
	 */
	interface iMirrorHandler {

		/** @var int MODE_REDIRECT режим перенаправления с зеркала на текущий домен */
		const MODE_REDIRECT = 1;

		/** @var int MODE_CRASH режим прерывания выполнения скрипта, если запрошено неизвестное зеркало; */
		const MODE_CRASH = 2;

		/** @var int MODE_ADD_MIRROR режим добавления неизвестного зеркала в список зеркал текущего домена */
		const MODE_ADD_MIRROR = 3;

		/** @var int MODE_IGNORE режим бездействия */
		const MODE_IGNORE = 4;

		/**
		 * Устанавливает фасад доменов
		 * @param iDomains $domains фасад доменов
		 * @return $this
		 */
		public function setDomains(iDomains $domains);

		/**
		 * Устанавливает определитель домена
		 * @param iDomainDetector $domainDetector определитель домена
		 * @return $this
		 */
		public function setDomainDetector(iDomainDetector $domainDetector);

		/**
		 * Обрабатывает запрос, если он сделан с зеркала
		 * @param int $mode режим обработки запроса с зеркала
		 * @throws \coreException
		 */
		public function checkMirror($mode = self::MODE_REDIRECT);
	}