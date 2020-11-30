<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns="http://www.w3.org/1999/xhtml"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:xlink="http://www.w3.org/1999/xlink"
				exclude-result-prefixes="xsl date udt xlink">

	<xsl:template match="result[@module = 'dispatches'][@method = 'subscribe_do']">
		<xsl:apply-templates select="document('udata://dispatches/subscribe_do/')/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'dispatches'][@method = 'subscribe_do']">
		<xsl:apply-templates select="result" mode="subscribe_do" />
	</xsl:template>

	<xsl:template match="udata[@module = 'dispatches'][@method = 'subscribe_do'][unsubscribe_link]">
		<xsl:if test="$user-type = 'guest'">
			<p>&dispatch-you-to;.</p>
			<p>&dispatch-unsubscribe; <a href="{unsubscribe_link}">&dispatch-unsubscribe-part;</a>.</p>
		</xsl:if>
	</xsl:template>

	<xsl:template match="result" mode="subscribe_do">
		<xsl:choose>
			<xsl:when test="$user-type != 'guest'">
				<p>&dispatch-you-from;.</p>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="." />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="result[@class = 'error']" mode="subscribe_do">
		<p><xsl:value-of select="."/></p>
	</xsl:template>

	<xsl:template match="result[items]" mode="subscribe_do">
		<p>&dispatch-you-to;:</p>
		<ul><xsl:apply-templates select="items" mode="subscribe_do" /></ul>
	</xsl:template>

	<xsl:template match="items" mode="subscribe_do">
		<li><xsl:value-of select="." disable-output-escaping="yes" /></li>
	</xsl:template>

</xsl:stylesheet>