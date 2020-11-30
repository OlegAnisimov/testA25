<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="udata[@method='navibar']">
        <div class="page-block page-block-top light-bg grid-container">
            <xsl:if test="$module = 'catalog'">
                <a href="#sidebar-mobile" class="dark-color active-hover click-slide align-right float-right custom-slide">
                    <i class="icon-reorder" />
                </a>
            </xsl:if>
            <div class="breadcrumbs grid-100 middle-color" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <a href="/" class="dark-color active-hover" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <meta itemprop="position" content="1" />
                    <span itemprop="item" itemscope="" itemtype="http://schema.org/Thing" itemid="http://{$domain}">
                        <link itemprop="url" href="/" />
                        <meta itemprop="name" content="Главная" />
                        <xsl:text>Главная</xsl:text>
                    </span>
                </a>
                <xsl:apply-templates select="items/item" />
                <xsl:if test="not(items/item)">
                    <strong class="active-color" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <meta itemprop="position" content="2" />
                        <span itemprop="item" itemscope="" itemtype="http://schema.org/Thing" itemid="http://{$domain}{$request-uri}">
                            <meta itemprop="name" content="{$header}" />
                            <xsl:value-of select="$header" />
                        </span>
                    </strong>
                </xsl:if>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="udata[@method='navibar']/items/item">
        <a href="{@link}" class="dark-color active-hover" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="{position() + 1}" />
            <span itemprop="item" itemscope="" itemtype="http://schema.org/Thing" itemid="http://{$domain}{@link}">
                <link itemprop="url" href="{@link}" />
                <meta itemprop="name" content="{.}" />
                <xsl:value-of select="node()" />
            </span>
        </a>
    </xsl:template>

    <xsl:template match="udata[@method='navibar']/items/item[last()]">
        <strong class="active-color" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="{position() + 1}" />
            <span itemprop="item" itemscope="" itemtype="http://schema.org/Thing" itemid="http://{$domain}{$request-uri}">
                <meta itemprop="name" content="{.}" />
                <xsl:value-of select="node()" />
            </span>
        </strong>
    </xsl:template>

</xsl:stylesheet>