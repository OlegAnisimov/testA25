<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:umi="http://www.umi-cms.ru/TR/umi">

    <xsl:template match="result[@module = 'content'][@method = 'sitemap']" priority="1">
        <section class="page-content">
            <xsl:apply-templates select="document('udata://core/navibar')/udata" />

            <div class="page-block page-block-bottom cream-bg grid-container">
                <div class="sidebar-shadow push-25"></div>
                <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                    <div class="sidebar-box sidebar-top cream-gradient">
                        <xsl:choose>
                            <xsl:when test="page/@id = 8 or parents/page/@id = 8">
                                <xsl:apply-templates select="document('udata://menu/draw/1642')/udata" mode="content-p-sidebar">
                                    <xsl:with-param name="expand-first" select="'1'" />
                                </xsl:apply-templates>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:apply-templates select="document('udata://menu/draw/(content_sidebar)')/udata" mode="content-p-sidebar" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </div>
                </div>

                <div class="content-with-sidebar grid-75">
                    <div class="with-shadow grid-100 light-bg">
                        <div class="content-page grid-100">
                            <h1 class="active-color header-font with-border full">
                                Карта сайта
                            </h1>

                            <xsl:apply-templates select="document('udata://content/sitemap')" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </xsl:template>

    <xsl:template match="udata[@method = 'sitemap']">
        <xsl:apply-templates mode="sitemap" />
    </xsl:template>

    <xsl:template match="items" mode="sitemap">
        <ul umi:element-id="{parent::node()/@id}" class="test" umi:module="content" umi:method="sitemap" umi:region="list" umi:sortable="sortable">
            <xsl:apply-templates mode="sitemap" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="sitemap">
        <li umi:element-id="{@id}" umi:region="row">
            <a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-section-name;">
                <xsl:value-of select="@name" />
            </a>
            <xsl:apply-templates mode="sitemap" />
        </li>
    </xsl:template>

</xsl:stylesheet>