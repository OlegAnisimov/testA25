<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="result[@module = 'photoalbum'][@method = 'album']">
		<xsl:apply-templates select="document(concat('udata://photoalbum/album/',$document-page-id, '?extProps=h1,descr'))/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'photoalbum'][@method = 'album']">
		<div id="photoalbum" umi:method="album" umi:module="photoalbum" umi:element-id="{id}">
			<div class="descr" umi:element-id="{id}" umi:field-name="descr">
				<xsl:value-of select="document(concat('upage://',id,'.descr'))/udata/property/value" disable-output-escaping="yes" />
			</div>
			<div class="clear" />
		</div>
	</xsl:template>

	<xsl:template match="udata[@module = 'photoalbum'][@method = 'album'][total]">
		<div id="photoalbum" umi:method="album" umi:module="photoalbum" umi:element-id="{id}">
			<div class="descr" umi:element-id="{id}" umi:field-name="descr">
				<xsl:value-of select="document(concat('upage://',id,'.descr'))/udata/property/value" disable-output-escaping="yes" />
			</div>
			<xsl:apply-templates select="items/item" />
			<div class="clear" />
		</div>
		<xsl:apply-templates select="total" />
	</xsl:template>

	<xsl:template match="udata[@module = 'photoalbum' and @method = 'album']/items/item">
		<div class="gray_block" umi:element-id="{@id}">
			<a href="{@link}">
				<xsl:call-template name="catalog-thumbnail">
					<xsl:with-param name="element-id" select="@id" />
					<xsl:with-param name="field-name">photo</xsl:with-param>
					<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
					<xsl:with-param name="width">154</xsl:with-param>
					<xsl:with-param name="height">110</xsl:with-param>
				</xsl:call-template>
			</a>
			<div class="descr">
				<h3>
					<a href="{@link}" umi:delete="delete" umi:element-id="{@id}" umi:field-name="h1">
						<xsl:value-of select=".//property[@name = 'h1']/value" />
					</a>
				</h3>
				<div umi:element-id="{@id}" umi:field-name="descr">
					<xsl:value-of select=".//property[@name = 'descr']/value" disable-output-escaping="yes" />
				</div>
			</div>
			<div class="clear" />
		</div>
	</xsl:template>

</xsl:stylesheet>