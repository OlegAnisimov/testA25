<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

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


    <!-- мини меню-->
    <xsl:template match="udata" mode="mini-menu" />
    <xsl:template match="udata[item]" mode="mini-menu">
        <div class="top-menu-left">
            <ul>
                <xsl:apply-templates select="item" mode="mini-menu" />
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="mini-menu">
        <li>
            <a href="{@link}" class="dark-color">
                <xsl:value-of select="@name" />
            </a>
        </li>
    </xsl:template>


    <xsl:template match="udata" mode="main-menu"/>
    <xsl:template match="udata[item]" mode="main-menu">
        <ul class="main-menu-desktop dark-gradient transition-all" id="menu-mobile">
            <li class="middle-color light-hover home">
                <a href="/" class="main-menu-item transition-all">
                    <i class="icon-home"></i>
                </a>
            </li>

            <li class="middle-color light-hover back">
                <a href="#menu-mobile" class="main-menu-item click-slide">
                    <i class="icon-chevron-left"></i>
                </a>
            </li>

            <xsl:apply-templates select="item" mode="main-menu" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="main-menu">
        <li class="light-color active-hover">
            <a href="{@link}" class="main-menu-item transition-all">
                <xsl:value-of select="@name" />
            </a>
            <xsl:apply-templates select="items" mode="main-menu-sub" />
        </li>
    </xsl:template>

    <xsl:template match="items" mode="main-menu-sub">
        <ul class="mega-menu cream-bg">
            <li class="mega-menu-active cream-gradient"></li>
            <xsl:variable name="cnt" select="count(item)" />
            <xsl:apply-templates select="item[position() &lt; '6']" mode="main-menu-sub">
                <xsl:with-param name="cnt" select="$cnt" />
            </xsl:apply-templates>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="main-menu-sub">
        <xsl:param name="cnt" />
        <li class="mega-menu-box cust-cols-{$cnt}">
            <a href="{@link}" class="mega-menu-title active-color clearfix">
                <xsl:value-of select="@name" />
            </a>
            <xsl:apply-templates select="items" mode="main-menu-sub-1" />
        </li>
    </xsl:template>

    <xsl:template match="items" mode="main-menu-sub-1">
        <ul class="mega-menu-list">
            <xsl:apply-templates select="item" mode="main-menu-sub-1" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="main-menu-sub-1">
        <li>
            <a href="{@link}" class="dark-color active-hover">
                <xsl:value-of select="@name" />
            </a>
        </li>
    </xsl:template>


    <xsl:template match="udata" mode="footer-menu" />
    <xsl:template match="udata[item]" mode="footer-menu">
        <div class="grid-50 grid-parent">
            <xsl:apply-templates select="item[position() &lt; 4]" mode="footer-menu" />
        </div>
    </xsl:template>

    <xsl:template match="item" mode="footer-menu">
        <div class="grid-33 tablet-grid-33">
            <h3 class="light-color subheader-font">
                <strong>
                    <xsl:value-of select="@name" />
                </strong>
            </h3>
            <xsl:apply-templates select="items" mode="footer-menu-sub" />
        </div>
    </xsl:template>

    <xsl:template match="items" mode="footer-menu-sub">
        <ul class="middle-color">
            <xsl:apply-templates select="item" mode="footer-menu-sub" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="footer-menu-sub">
        <li class="light-hover">
            <a href="{@link}">
                <xsl:value-of select="@name" />
            </a>
        </li>
    </xsl:template>


    <xsl:template match="udata" mode="content-p-sidebar"/>
    <xsl:template match="udata[item]" mode="content-p-sidebar">
        <xsl:param name="expand-first" />
        <nav class="submenu">
            <ul class="expandable-menu">
                <li class="align-right back">
                    <a href="#sidebar-mobile" class="dark-color active-hover click-slide">
                        <i class="icon-chevron-right"></i>
                    </a>
                </li>
                <xsl:apply-templates select="item" mode="content-p-sidebar">
                    <xsl:with-param name="expand-first" select="$expand-first" />
                </xsl:apply-templates>
            </ul>
        </nav>
    </xsl:template>

    <xsl:template match="item" mode="content-p-sidebar">
        <xsl:param name="expand-first" />
        <li>
            <xsl:if test="$document-page-id = @id or $document-page-id = item/@id or $document-result/parents/page/@id = @id or ($expand-first = '1' and position() = '1')">
                <xsl:attribute name="class">expanded</xsl:attribute>
            </xsl:if>

            <xsl:if test="items/item">
                <span class="more_category">
                    <i class="icon-chevron-down"></i>
                </span>
            </xsl:if>

            <a href="{@link}" class="dark-color active-hover">
                <xsl:value-of select="@name" />
            </a>
            <xsl:apply-templates select="items" mode="content-p-sidebar-sub" />
        </li>
        <xsl:if test="position() != last()">
            <li class="sidebar-divider"></li>
        </xsl:if>
    </xsl:template>

    <xsl:template match="items" mode="content-p-sidebar-sub" />
    <xsl:template match="items[item]" mode="content-p-sidebar-sub">
        <ul>
            <xsl:apply-templates select="item" mode="content-p-sidebar-sub-item" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="content-p-sidebar-sub-item">
        <li>
            <a href="{@link}">
                <xsl:attribute name="class">
                    <xsl:text>dark-color active-hover</xsl:text>
                    <xsl:if test="(@id = $document-result/page/@id) or (@id = $document-result/parents/page/@id)">
                        <xsl:text> selected</xsl:text>
                    </xsl:if>
                </xsl:attribute>
                <b class="middle-color">&#8250;</b>&#160;<xsl:value-of select="node()" />
                <!--<xsl:apply-templates select="document(concat('udata://catalog/getObjectsList/0/', @id, '/0/1'))/udata" mode="count-objs" />-->
            </a>
        </li>
    </xsl:template>

</xsl:stylesheet>