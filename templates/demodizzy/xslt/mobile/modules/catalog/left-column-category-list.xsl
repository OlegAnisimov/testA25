<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">


	<xsl:template match="udata[@method = 'getCategoryList']" mode="left-column">
		<ul class="catalog_menu" umi:button-position="bottom left"
			umi:element-id="{@category-id}" umi:region="list" umi:module="catalog" umi:sortable="sortable">
			<xsl:apply-templates select="//item" mode="left-column" />
		</ul>
	</xsl:template>


	<xsl:template match="udata[@method = 'getCategoryList']//item" mode="left-column">
		<li umi:element-id="{@id}" umi:region="row">
			<span class="row_span">
				<a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-section-name;">
					<xsl:value-of select="." />
				</a>
			</span>
			<xsl:apply-templates select="document(concat('udata://catalog/getCategoryList/void/', @id))" />
		</li>
	</xsl:template>

</xsl:stylesheet>