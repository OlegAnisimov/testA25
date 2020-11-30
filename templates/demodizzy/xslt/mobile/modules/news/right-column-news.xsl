<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="udata" mode="right-column-news" />

	<xsl:template match="udata[items]" mode="right-column-news">
		<xsl:variable name="category-name" select="document(concat('upage://', category_id))/udata/page/name" />

		<div class="infoblock">
			<div class="title">
				<h2 umi:element-id="{category_id}" umi:field-name="name">
					<xsl:value-of select="$category-name" />
				</h2>
			</div>
			<div class="body">
				<div class="in">
					<div umi:element-id="{category_id}" umi:region="list" umi:sortable="none"
						umi:module="news" umi:method="lastlist" umi:button-position="top right">
						<xsl:apply-templates select="items/item" mode="right-column-news" />
					</div>

					<a href="{archive_link}" class="archive">
						<xsl:text>&news-archive;</xsl:text>
					</a>
				</div>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="item" mode="right-column-news">
		<xsl:variable name="item-info" select="document(@xlink:href)" />

		<div class="news_item" umi:element-id="{@id}" umi:region="row">
			<span class="date" umi:field-name="publish_time" umi:empty="&empty-page-date;">
				<xsl:apply-templates select="$item-info//property[@name = 'publish_time']" />
			</span>

			<a href="{@link}" class="title" umi:field-name="name" umi:delete="delete" umi:empty="&empty-page-name;">
				<xsl:value-of select="." />
			</a>

			<div class="descr" umi:field-name="anons" umi:empty="&empty-page-anons;">
				<xsl:value-of select="$item-info//property[@name = 'anons']/value" disable-output-escaping="yes" />
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>