<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="udata[@method = 'price']">
		<xsl:apply-templates select="price" />
	</xsl:template>
	
	<xsl:template match="total-price">
		<xsl:value-of select="concat(@prefix, ' ', actual, ' ', @suffix)" />
	</xsl:template>

	<xsl:template match="price" mode="discounted-price">
		<xsl:value-of select="concat(@prefix, ' ', actual, ' ', @suffix)" />
	</xsl:template>

	<xsl:template match="price">
		<xsl:value-of select="concat(@prefix, ' ', original, ' ', @suffix)" />
	</xsl:template>

	<xsl:template match="price[not(original) or original = '']">
		<xsl:value-of select="concat(@prefix, ' ', actual, ' ', @suffix)" />
	</xsl:template>

</xsl:stylesheet>
