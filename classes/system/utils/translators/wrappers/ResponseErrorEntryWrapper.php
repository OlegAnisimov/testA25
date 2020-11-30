<?php
	use UmiCms\Service;
	use UmiCms\System\Response\Error\iEntry;
	use UmiCms\System\Response\Error\Entry\iMapper;

	/** Класс xml транслятора (сериализатора) записи об обработке ответа с ошибкой */
	class ResponseErrorEntryWrapper extends translatorWrapper {

		/**
		 * @inheritDoc
		 * @param iEntry $object запись об обработке ответа с ошибкой
		 * @return array
		 * @throws \coreException
		 * @throws \ErrorException
		 */
		public function translate($object) {
			return $this->translateData($object);
		}

		/**
		 * Преобразует запись об обработке ответа с ошибкой в массив с разметкой для последующей сериализации в xml
		 * @param iEntry $entry запись об обработке ответа с ошибкой
		 * @return array
		 * @throws ErrorException
		 * @throws coreException
		 */
		protected function translateData(iEntry $entry) {
			$result = [];

			foreach (Service::ResponseErrorEntryFacade()->extractPropertyList($entry) as $name => $value) {
				if ($name === iMapper::UPDATE_TIME) {
					$value = Service::DateFactory()
						->createByTimeStamp($value)
						->getFormattedDate();
				}

				if (!is_object($value)) {
					$result[sprintf('@%s', $name)] = $value;
					continue;
				}

				$result[$name] = translatorWrapper::get($value)->translate($value);
			}

			return $result;
		}
	}