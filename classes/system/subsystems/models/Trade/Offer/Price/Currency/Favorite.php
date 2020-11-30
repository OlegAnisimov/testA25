<?php
	namespace UmiCms\System\Trade\Offer\Price\Currency;

	/**
	 * Абстрактный класс любимой валюты
	 * @package UmiCms\System\Trade\Offer\Price\Currency
	 */
	abstract class Favorite implements iFavorite {

		/** @inheritDoc */
		abstract public function getId();

		/** @inheritDoc */
		abstract public function setId($id);
	}