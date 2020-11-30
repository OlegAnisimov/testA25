<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="result[@module = 'content']">
		<div umi:element-id="{$document-page-id}" umi:field-name="content" umi:empty="&empty-page-content;">
			<xsl:value-of select=".//property[@name = 'content']/value" disable-output-escaping="yes" />
		</div>
	</xsl:template>
	
	<xsl:template match="result" mode="header">
		<h1>
			<xsl:value-of select="@header" />
		</h1>
	</xsl:template>
	
	<xsl:template match="result[@pageId]" mode="header">
		<h1 umi:element-id="{@pageId}" umi:field-name="h1" umi:empty="&empty-page-name;">
			<xsl:value-of select="@header" />
		</h1>
	</xsl:template>
	
	<xsl:include href="menu.xsl" />
	<xsl:include href="404.xsl" />
	<xsl:include href="sitemap.xsl" />
	<xsl:include href="recentPages.xsl" />

</xsl:stylesheet>