<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">
    <xsl:template match="/result[@method = 'search_do']">
        <section class="page-content">
            <xsl:apply-templates select="document('udata://core/navibar')/udata" />
            <div class="page-block page-block-bottom cream-bg grid-container">
                <div class="sidebar-shadow push-25"></div>
                <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                    <div class="sidebar-box sidebar-top cream-gradient">
                        <xsl:apply-templates select="document('udata://menu/draw/(content_sidebar)')/udata" mode="content-p-sidebar" />
                    </div>
                </div>

                <div class="content-with-sidebar grid-75 grid-parent">
                    <div class="with-shadow grid-100 light-bg">
                        <div class="content-page grid-100 np-items-cont">
                            <h1 class="active-color header-font with-border full">Результаты поиска</h1>
                            <xsl:apply-templates select="document('udata://search/search_do')" />
                            <div umi:element-id="{page/@id}" umi:field-name="descr" umi:empty="Описание">
                                <xsl:value-of select=".//property[@name = 'descr']/value" disable-output-escaping="yes" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </xsl:template>

    <xsl:template match="udata[@method = 'search_do']">
        <form class="search" action="/search/search_do/" method="get">
            <input type="text" value="{$search_string}" name="search_string" class="textinputs"  x-webkit-speech="" speech="" />
            <input type="submit" class="button" value="&search;" />
        </form>
        <br />
        <p>
            <strong>
                <xsl:text>&search-founded-left; "</xsl:text>
                <xsl:value-of select="$search_string" />
                <xsl:text>" &search-founded-nothing;.</xsl:text>
            </strong>
        </p>
    </xsl:template>

    <xsl:template match="udata[@method = 'search_do' and count(items/item)]">
        <form class="search" action="/search/search_do/" method="get">
            <input type="text" value="{$search_string}" name="search_string" class="textinputs"  x-webkit-speech="" speech="" />
            <input type="submit" class="button" value="&search;" />
        </form>

        <p>
            <strong>
                <xsl:text>&search-founded-left; "</xsl:text>
                <xsl:value-of select="$search_string" />
                <xsl:text>" &search-founded-right;: </xsl:text>
                <xsl:value-of select="total" />
                <xsl:text>.</xsl:text>
            </strong>
        </p>

        <dl class="search">
            <xsl:apply-templates select="items/item" mode="search-result" />
        </dl>
        <xsl:apply-templates select="total" />
    </xsl:template>

    <xsl:template match="item" mode="search-result">
        <dt>
            <span>
                <xsl:value-of select="$p + position()" />.
            </span>
            <a href="{@link}" umi:element-id="{@id}" umi:field-name="name">
                <xsl:value-of select="@name" />
            </a>
        </dt>
        <dd>
            <xsl:value-of select="." disable-output-escaping="yes" />
        </dd>
    </xsl:template>
</xsl:stylesheet>