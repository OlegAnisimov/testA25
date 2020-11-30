<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<!-- Header menu -->
	<xsl:template match="udata[@module = 'menu']" mode="info-pages-head">
		<ul>
			<xsl:apply-templates select="item" mode="info-pages-head" />
		</ul>
	</xsl:template>

	<xsl:template match="udata[@module = 'menu']/item" mode="info-pages-head">
		<li>
			<a href="{@link}">
				<xsl:value-of select="node()" />
			</a>
		</li>
	</xsl:template>

	<xsl:template match="udata[@module = 'menu']/item[@link = '/help/']" mode="info-pages-head">
		<li>
			<a href="{@link}" class="help">
				<xsl:value-of select="node()" />
			</a>
		</li>
	</xsl:template>
	
</xsl:stylesheet>