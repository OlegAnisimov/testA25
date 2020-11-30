<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet	version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="result[@module = 'dispatches'][@method = 'unsubscribe']">
		<xsl:apply-templates select="document('udata://dispatches/unsubscribe/')/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'dispatches'][@method = 'unsubscribe']">
		<p><xsl:value-of select="." /></p>
	</xsl:template>

</xsl:stylesheet>