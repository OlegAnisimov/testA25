<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="udata[@method='navibar']">
        <div class="breadcrumbs">
            <xsl:apply-templates select="items/item" />
        </div>
    </xsl:template>

    <xsl:template match="udata[@method='navibar']/items/item">
        <a class="crumb" href="{@link}"><xsl:value-of select="node()" /></a> >
    </xsl:template>

    <xsl:template match="udata[@method='navibar']/items/item[last()]">
        <span class="crumb"><xsl:value-of select="node()" /></span>
    </xsl:template>

</xsl:stylesheet>