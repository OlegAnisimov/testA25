<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:include href="errors.xsl" />
	<xsl:include href="date.xsl" />
	<xsl:include href="captcha.xsl" />
	<xsl:include href="numpages.xsl" />
	<xsl:include href="thumbnails.xsl" />

	<xsl:template match="/">
		<xsl:choose>
			<xsl:when test="count(udata) = 0">
				<xsl:apply-templates select="." mode="layout" />
			</xsl:when>

			<xsl:otherwise>
				<xsl:apply-templates />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="result">
		<p>
			<xsl:text>Debug message for UC-pages.</xsl:text>
		</p>
	</xsl:template>
</xsl:stylesheet>