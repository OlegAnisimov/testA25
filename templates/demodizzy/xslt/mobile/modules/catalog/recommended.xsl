<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="property[@name = '&property-recommended;'][count(value/page) &gt; 0]" />

	<xsl:template match="property[@name = '&property-recommended;']">
		<hr />
		<h4>
			<xsl:text>&recommended-items;:</xsl:text>
		</h4>
		
		<xsl:apply-templates select="value/page" mode="short-view">		
			<xsl:with-param name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
		</xsl:apply-templates>
		
		<div class="clear"></div>
	</xsl:template>
</xsl:stylesheet>