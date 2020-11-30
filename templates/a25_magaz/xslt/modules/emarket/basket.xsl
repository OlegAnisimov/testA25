<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="udata[@module = 'emarket' and @method = 'cart']" mode="basket">
		<div class="basket_info">
			<div>
				<a href="{$lang-prefix}/emarket/cart/">&basket;</a>
				<span class="basket_info_summary">
					<xsl:apply-templates select="summary" mode="basket" />
				</span>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="summary" mode="basket">
		<xsl:text>&basket-empty;</xsl:text>
	</xsl:template>
	
	<xsl:template match="summary[amount &gt; 0]" mode="basket">
		<span>
			<xsl:apply-templates select="amount" />
		</span>
		<xsl:text>&basket-items-text;</xsl:text>
		<xsl:text> </xsl:text>
		<xsl:apply-templates select="price/@prefix" />
		<xsl:apply-templates select="price/actual" />
		<xsl:apply-templates select="price/@suffix" />
		<xsl:text>.</xsl:text>
	</xsl:template>
	
	<xsl:template match="summary[amount &gt; 0]/price/@prefix">
		<xsl:value-of select="." />
		<xsl:text>&#160;</xsl:text>
	</xsl:template>
	
	<xsl:template match="summary[amount &gt; 0]/price/@suffix">
		<xsl:text>&#160;</xsl:text>
		<xsl:value-of select="." />
	</xsl:template>
	
</xsl:stylesheet>