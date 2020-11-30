<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">


	<!-- Header menu -->
	<xsl:template match="udata" mode="info-pages-head">
		<ul umi:element-id="0" umi:module="content" umi:add-method="none" umi:region="list" umi:sortable="sortable">
			<xsl:apply-templates select="page" mode="info-pages-head" />
		</ul>
	</xsl:template>

	<xsl:template match="page" mode="info-pages-head">
		<li umi:element-id="{@id}" umi:region="row">
			<a href="{@link}" umi:field-name="name" umi:empty="&empty-section-name;" umi:delete="delete">
				<xsl:value-of select="name" />
			</a>
		</li>
	</xsl:template>

	<xsl:template match="page[@alt-name = 'help']" mode="info-pages-head">
		<li umi:element-id="{@id}" umi:region="row">
			<a href="{@link}" class="help" umi:field-name="name" umi:empty="&empty-section-name;" umi:delete="delete">
				<xsl:value-of select="name" />
			</a>
		</li>
	</xsl:template>


	<!-- Top menu -->
	<xsl:template match="udata[@method = 'menu']" mode="top_menu">
		<div id="top_menu" umi:element-id="0" umi:module="content" umi:region="list" umi:sortable="sortable" umi:add-method="popup">
			<xsl:apply-templates select="items/item" mode="top_menu" />
		</div>
	</xsl:template>
	
	<xsl:template match="udata[@method = 'menu']//page[@type-id='71']" mode="top_menu">
	123
	</xsl:template>

	<xsl:template match="item" mode="top_menu">
		<a href="{@link}" umi:element-id="{@id}" umi:region="row" umi:field-name="name" umi:empty="&empty-section-name;" umi:delete="delete" class="b_a">
			<xsl:value-of select="." />
		</a>
	</xsl:template>


	<!-- Footer menu -->
	<xsl:template match="udata" mode="info-pages-foot">
		<div class="links" umi:element-id="0" umi:module="content" umi:add-method="none" umi:region="list" umi:sortable="sortable">
			<xsl:apply-templates select="page" mode="info-pages-foot" />
		</div>
	</xsl:template>

	<xsl:template match="page" mode="info-pages-foot">
		<a href="{@link}" umi:element-id="{@id}" umi:field-name="name" umi:empty="&empty-section-name;" umi:delete="delete" umi:region="row">
			<xsl:value-of select="name" />
		</a>
	</xsl:template>
</xsl:stylesheet>