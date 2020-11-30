<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet	version="1.0"
                xmlns="http://www.w3.org/1999/xhtml"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:date="http://exslt.org/dates-and-times"
                xmlns:udt="http://umi-cms.ru/2007/UData/templates"
                xmlns:xlink="http://www.w3.org/TR/xlink"
                xmlns:umi="http://www.umi-cms.ru/TR/umi"
                exclude-result-prefixes="xsl date udt xlink">

    <xsl:include href="fastInsert.xsl" />

        <xsl:template match="udata" mode="mp-banners">
            <div class="block-banners margin-bottom clearfix">
                <xsl:apply-templates select="banners[position() &lt; 4]" mode="mp-banners" />
            </div>
        </xsl:template>

        <xsl:template match="banners" mode="mp-banners">
            <div class="banner grid-33 tablet-grid-33" umi:object-id="{@id}">
                <a href="{banner/href}" class="thumbnail light-bg" umi:field-name="image" umi:empty="Изображение">
                    <xsl:if test="banner/@target = '_blank'">
                        <xsl:attribute name="target">_blank</xsl:attribute>
                    </xsl:if>
                    <img src="{document(concat('udata://system/makeThumbnailFull/(.', banner/source, ')/285/150'))//src}" alt="" />
                    <span class="thumbnail-arrow light-color active-border">
                        <i class="icon-zoom-in"></i>
                    </span>
                </a>
                <div umi:field-name="url" umi:empty="URL страницы" />
            </div>
        </xsl:template>

</xsl:stylesheet>