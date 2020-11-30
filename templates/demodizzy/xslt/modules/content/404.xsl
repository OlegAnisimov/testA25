<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">


	<xsl:template match="/result[@method = 'notfound']" priority="1">
		<xsl:variable name="error-page" select="document(concat('uhttp://', @domain, @pre-lang, '/notfound/.xml'))/result/page" />
		
		<div class="notfound" umi:element-id="{$error-page/@id}" umi:field-name="content">
			<xsl:value-of select="$error-page//property[@name = 'content']/value" disable-output-escaping="yes" />
		</div>
	</xsl:template>
</xsl:stylesheet>