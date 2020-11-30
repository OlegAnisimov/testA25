<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="result[@pageId][@request-uri = '/']" mode="header">
		<h1 class="best" umi:element-id="{@pageId}" umi:field-name="h1" umi:empty="&empty-page-name;">
			<xsl:value-of select="@header" />
		</h1>
	</xsl:template>


	<xsl:template match="result[page/@is-default = '1']" priority="1">
		<xsl:apply-templates select="document('usel://special-offers/?limit=&specials-limit;')" mode="special-offers" />
	</xsl:template>


	<xsl:template match="udata" mode="special-offers">
		<div class="catalog">
			<div class="objects" umi:element-id="{page[@parentId &gt; 0]/@parentId}" umi:module="catalog" umi:method="getObjectsList" umi:region="list" umi:add-method="none">
				<xsl:apply-templates select="page" mode="short-view">
					<xsl:with-param name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
				</xsl:apply-templates>
			</div>
		</div>
	</xsl:template>

</xsl:stylesheet>