<?php
	namespace UmiCms\Classes\System\Utils\SiteMap\Image;

	use UmiCms\Classes\System\Utils\SiteMap\iImage;
	use UmiCms\Classes\System\Utils\SiteMap\iLocation;
	use UmiCms\Classes\System\Utils\SiteMap\Generator as BaseGenerator;
	use UmiCms\Classes\System\Utils\SiteMap\Location\iCollection as iLocationCollection;

	/**
	 * Класс генератора карты изображений сайта
	 * @package UmiCms\Classes\System\Utils\SiteMap\Image
	 */
	class Generator extends BaseGenerator implements iGenerator {

		/** @inheritDoc */
		protected function getContainerTags() : array {
			return [
				'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
						xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">',
				'</urlset>',
			];
		}

		/** @inheritDoc */
		protected function getSiteIndexLocation(\iDomain $domain, int $sort) : string {
			return sprintf('%s://%s/sitemap-images%d.xml', $domain->getProtocol(), $domain->getHost(), $sort);
		}

		/** @inheritDoc */
		protected function getLocationCollection(int $domainId, int $sort = null) : iLocationCollection {
			$collection = parent::getLocationCollection($domainId, $sort);
			$this->locationFacade->loadRelations($collection);
			return $collection;
		}

		/**
		 * @inheritDoc
		 * @throws \ErrorException
		 * @throws \ReflectionException
		 */
		protected function getLocationXml(iLocation $location) : \DOMDocument {
			$document = parent::getLocationXml($location);
			$url = $document->getElementsByTagName('url')->item(0);

			/** @var iImage $image */
			foreach ($location->getImageCollection() as $image) {
				$imageContainer = $document->createElement('image:image');
				$imageLocation = $document->createElement('image:loc', $image->getLink());
				$imageContainer->appendChild($imageLocation);

				if ($image->getAlt()) {
					$caption = $document->createCDATASection($image->getAlt());
					$imageCaption = $document->createElement('image:caption');
					$imageCaption->appendChild($caption);
					$imageContainer->appendChild($imageCaption);
				}

				if ($image->getTitle()) {
					$title = $document->createCDATASection($image->getTitle());
					$imageTitle = $document->createElement('image:title');
					$imageTitle->appendChild($title);
					$imageContainer->appendChild($imageTitle);
				}

				$url->appendChild($imageContainer);
			}

			return $document;
		}
	}