<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="result[@module = 'content'][@method = 'sitemap']" priority="1">
		<xsl:apply-templates select="document('udata://content/sitemap')" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'sitemap']">
		<xsl:apply-templates mode="sitemap" />
	</xsl:template>
	
	<xsl:template match="items" mode="sitemap">
		<ul umi:element-id="{parent::node()/@id}" class="test"
			umi:module="content" umi:method="sitemap" umi:region="list" umi:sortable="sortable">
			<xsl:apply-templates mode="sitemap" />
		</ul>
	</xsl:template>
	
	<xsl:template match="item" mode="sitemap">
		<li umi:element-id="{@id}" umi:region="row">
			<a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-section-name;">
				<xsl:value-of select="@name" />
			</a>
			<xsl:apply-templates mode="sitemap" />
		</li>
	</xsl:template>

</xsl:stylesheet>