<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">


	<xsl:template match="udata[@method = 'getCategoryList']">
		<ul class="tu" umi:element-id="{@category-id}" umi:region="list" umi:module="catalog" umi:sortable="sortable" umi:button-position="top right">
			<xsl:apply-templates select="//item" />
		</ul>
	</xsl:template>


	<xsl:template match="udata[@method = 'getCategoryList']//item">
		<li umi:element-id="{@id}" umi:region="row" class="lev_tu">
			<a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-section-name;" class="a_link">
				<xsl:value-of select="." />
			</a>
		</li>
	</xsl:template>

</xsl:stylesheet>