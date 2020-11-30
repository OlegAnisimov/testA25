<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="/result[@module = 'news'][@method = 'item']">
		<div umi:element-id="{$document-page-id}" umi:field-name="content" umi:empty="&empty-page-content;">
			<xsl:value-of select=".//property[@name = 'content']/value" disable-output-escaping="yes" />
		</div>
		
		<xsl:apply-templates select="document('udata://news/related_links')" />
		
		<a href="../">
			<xsl:text>&news-back;</xsl:text>
		</a>
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
	
	<xsl:template match="udata[@method = 'related_links']" />
	
	<xsl:template match="udata[@method = 'related_links' and count(items/item)]">
		<h4>
			<xsl:text>&news-realted;</xsl:text>
		</h4>

		<ul>
			<xsl:apply-templates select="items/item" mode="related" />
		</ul>
	</xsl:template>
	
	<xsl:template match="item" mode="related">
		<li umi:element-id="{@id}">
			<a href="{@link}" umi:field-name="news">
				<xsl:value-of select="." />
			</a>
		</li>
	</xsl:template>

</xsl:stylesheet>