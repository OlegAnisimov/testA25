<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/result[@module = 'news'][@method = 'rubric']">
		<xsl:apply-templates select="document('udata://news/lastlist/?extProps=publish_time,anons')" />
	</xsl:template>

	<xsl:template match="udata[@method = 'lastlist']">
		<dl class="news" umi:button-position="top right" umi:element-id="{$document-page-id}"
			umi:region="list" umi:module="news" umi:method="lastlist" umi:sortable="sortable">
			<xsl:apply-templates select="items/item" mode="news-list" />
		</dl>
		<xsl:apply-templates select="total" />
	</xsl:template>


	<xsl:template match="item" mode="news-list">
		<div umi:element-id="{@id}" umi:region="row">
			<dt>
				<div class="date" umi:field-name="publish_time" umi:empty="&empty-page-date;">
					<xsl:apply-templates select=".//property[@name = 'publish_time']" />
				</div>
				
				<a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-page-name;">
					<xsl:value-of select="node()" />
				</a>
			</dt>
			<dd umi:field-name="anons" umi:empty="&empty-page-anons;">
				<xsl:value-of select=".//property[@name = 'anons']/value" disable-output-escaping="yes" />
			</dd>
		</div>
	</xsl:template>
</xsl:stylesheet>