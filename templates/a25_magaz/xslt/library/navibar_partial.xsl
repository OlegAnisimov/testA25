<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:variable name="page_header" select="/result/@header" />

    <xsl:template match="udata" mode="navibar_partial">
        <xsl:choose>
            <xsl:when test="count(items/item) = 1 and items/item[position() = 1]/@link = '/'">
            </xsl:when>
            <xsl:when test="count(items/item) = 0">
                <div class="breadcrumbs breadcrumbs_partial">
                    <xsl:call-template name="home" />
                    <xsl:value-of select="$page_header" />
                </div>
            </xsl:when>
            <xsl:otherwise>
                <div class="breadcrumbs breadcrumbs_partial">
                    <xsl:call-template name="home" />
                    <xsl:apply-templates select="items/item" mode="navibar_partial"/>
                    <br class="clearEnd" />
                </div>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="item" mode="navibar_partial">
        <a class="crumb" href="{@link}">
            <xsl:value-of select="text()" />
        </a>
        <xsl:text> / </xsl:text>
    </xsl:template>

    <xsl:template match="item[position() = last()]" mode="navibar_partial">
        <xsl:value-of select="text()" />
    </xsl:template>

    <xsl:template name="home">
        <a class="icon home" />
        <xsl:text> / </xsl:text>
    </xsl:template>

</xsl:stylesheet>