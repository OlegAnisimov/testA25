<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="result[@module = 'photoalbum'][@method = 'photo']">
		<div id="photo" class="gray_block">
			<xsl:call-template name="catalog-thumbnail">
				<xsl:with-param name="element-id" select="$document-page-id" />
				<xsl:with-param name="field-name">photo</xsl:with-param>
				<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
				<xsl:with-param name="width">495</xsl:with-param>
			</xsl:call-template>
			<xsl:apply-templates select="document(concat('udata://photoalbum/album/',page/@parentId,'//1000'))/udata/items/item[@id = $document-page-id]" mode="slider" />
		</div>
		<div>
			<xsl:value-of select="//property[@name = 'descr']/value" disable-output-escaping="yes" />
		</div>
		<div>
			<a href="{parents/page[last()]/@link}">Показать все фотографии</a>
		</div>
		<div class="social">
			<div class="plusone">
				<div class="g-plusone" data-size="small" data-count="true"></div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function(){ jQuery.getScript('//yandex.st/share/share.js', function() {
			new Ya.share({
				'element': 'ya_share1',
				'elementStyle': {
					'type': 'button',
					'linkIcon': true,
					'border': false,
					'quickServices': ['yaru', 'vkontakte', 'facebook', 'twitter', 'odnoklassniki', 'moimir', 'lj']
				},
				'popupStyle': {
					'copyPasteField': true
				}
			 });
					});
				});
			</script>
			<span id="ya_share1"></span>
		</div>
	</xsl:template>

</xsl:stylesheet>