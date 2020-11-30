<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="format-date">
		<xsl:param name="date" />
		<xsl:param name="pattern" select="'d.m.Y'" />
		<xsl:variable name="uri" select="concat('udata://system/convertDate/', $date, '/(', $pattern, ')')" />
		
		<xsl:value-of select="document($uri)/udata" />
	</xsl:template>
	
	<xsl:template match="property[@type = 'date']">
		<xsl:param name="pattern" select="'&default-date-format;'" />
		<xsl:call-template name="format-date">
			<xsl:with-param name="date" select="value/@unix-timestamp" />
			<xsl:with-param name="pattern" select="$pattern" />
		</xsl:call-template>
	</xsl:template>

</xsl:stylesheet>