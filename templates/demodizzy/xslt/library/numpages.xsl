<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="total" />
	<xsl:template match="total[. &gt; ../per_page]">
		<xsl:apply-templates select="document(concat('udata://system/numpages/', ., '/', ../per_page))" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'numpages']" />
	<xsl:template match="udata[@method = 'numpages'][count(items)]">
		<div class="numpages">
			<div class="links">
				<xsl:if test="toprev_link and not(tonext_link)">
					<span>
						<xsl:text>|</xsl:text>
					</span>
				</xsl:if>
				<xsl:apply-templates select="toprev_link|tonext_link" />
			</div>
			<div class="pages">
				<xsl:apply-templates select="items/item" mode="numpages" />
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="item" mode="numpages">
		<a href="{@link}">
			<xsl:value-of select="." />
		</a>
	</xsl:template>
	
	<xsl:template match="item[@is-active = '1']" mode="numpages">
		<span>
			<xsl:value-of select="." />
		</span>
	</xsl:template>
	
	<xsl:template match="toprev_link">
		<a class="prev" href="{.}">
			<xsl:text>&previous-page;</xsl:text>
		</a>
	</xsl:template>
	
	<xsl:template match="tonext_link">
		<span>
			<xsl:text>|</xsl:text>
		</span>
		<a class="next" href="{.}">
			<xsl:text>&next-page;</xsl:text>
		</a>
	</xsl:template>

	<xsl:template match="item" mode="slider">
		<xsl:apply-templates select="preceding-sibling::item[1]" mode="slider_back" />
		<xsl:apply-templates select="following-sibling::item[1]" mode="slider_next" />
	</xsl:template>

	<xsl:template match="item" mode="slider_back">
		<a href="{@link}" title="&previous-page;" class="back" />
	</xsl:template>

	<xsl:template match="item" mode="slider_next">
		<a href="{@link}" title="&next-page;" class="next" />
	</xsl:template>

</xsl:stylesheet>