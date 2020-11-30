<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
					xmlns="http://www.w3.org/1999/xhtml"
					xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
					xmlns:date="http://exslt.org/dates-and-times"
					xmlns:udt="http://umi-cms.ru/2007/UData/templates"
					xmlns:umi="http://www.umi-cms.ru/TR/umi"
					exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="result[@module = 'faq'][@method = 'question']">
		<xsl:apply-templates select="document('udata://faq/question/')/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'faq'][@method = 'question']">
		<h3>&comment-question;</h3>
		<div><xsl:value-of select="question" disable-output-escaping="yes" /></div>
		<h3>&comment-answer;</h3>
		<div umi:element-id="{$document-page-id}" umi:field-name="answer" umi:empty="&comment-answer;">
			<xsl:value-of select="answer" disable-output-escaping="yes"/>
		</div>
	</xsl:template>

</xsl:stylesheet>