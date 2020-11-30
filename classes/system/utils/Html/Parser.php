<?php
	namespace UmiCms\Classes\System\Utils\Html;

	use UmiCms\Classes\System\Utils\DOM\Document\iFactory as iDocumentFactory;

	/**
	 * Класс html парсера
	 * @package UmiCms\Classes\System\Utils\Html
	 */
	class Parser implements iParser {

		/** @var iDocumentFactory $documentFactory фабрика DOM документов */
		private $documentFactory;

		/** @inheritDoc */
		public function __construct(iDocumentFactory $documentFactory) {
			$this->documentFactory = $documentFactory;
		}

		/** @inheritDoc */
		public function getImages(string $html) : array {
			return $this->getTagAttributes('img', $html);
		}

		/** @inheritDoc */
		public function getTagAttributes(string $tag, string $html) : array {
			$document = $this->documentFactory->create();
			$document->loadHTML("\xEF\xBB\xBF" . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);

			$tagList = $this->documentFactory
				->createParser($document)
				->query('//' . $tag);
			$attributeSet = [];

			foreach ($tagList as $tag) {
				$attributeList = [];

				/** @var \DOMAttr $attribute */
				foreach ($tag->attributes as $attribute) {
					$attributeList[$attribute->name] = html_entity_decode($attribute->value);
				}

				if ($attributeList) {
					$attributeSet[] = $attributeList;
				}
			}

			return $attributeSet;
		}

		/** @inheritDoc */
		public function replaceImages(array $tagsAttributes, string $html) : string {
			
			foreach ($tagsAttributes as $tagAttribute) {
				$replacement = $tagAttribute['replacement'];
				unset($tagAttribute['replacement']);
				$attributePattern = '';

				foreach ($tagAttribute as $attr => $value) {
					$attributePattern .= sprintf(' %s="%s"', $attr, quotemeta($value));
				}

				$pattern = <<<REGEXP
`(<img%s[^>]*/>)`
REGEXP;
				$html = preg_replace(sprintf($pattern, $attributePattern), $replacement, $html);
			}

			return $html;
		}
	}