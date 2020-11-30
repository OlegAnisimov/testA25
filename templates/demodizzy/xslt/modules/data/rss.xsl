<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="result[@method = 'rss' or @method = 'atom']">
		<xsl:value-of select="udata/error" />
	</xsl:template>

</xsl:stylesheet>