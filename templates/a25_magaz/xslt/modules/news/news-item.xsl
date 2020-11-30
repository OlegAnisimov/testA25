<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:umi="http://www.umi-cms.ru/TR/umi"
                xmlns:xlink="http://www.w3.org/TR/xlink">

    <xsl:template match="/result[@module = 'news'][@method = 'item']">
        <section class="page-content">
            <xsl:apply-templates select="document('udata://core/navibar')/udata" />

            <div class="page-block page-block-bottom cream-bg grid-container" itemscope="" itemtype="http://schema.org/NewsArticle">
                <div class="sidebar-shadow push-25" />
                <link itemprop="url" href="{page/@link}" />
                <div class="sidebar cream-gradient grid-25 transition-all" id="sidebar-mobile">
                    <div class="sidebar-box sidebar-top cream-gradient">
                        <xsl:apply-templates select="document(concat('udata://news/get_news_subjs/', parents/page/@id))/udata" mode="np-subj-list" />
                    </div>
                </div>

                <div class="content-with-sidebar grid-75" umi:element-id="{$document-page-id}" itemprop="mainEntityOfPage">
                    <div class="with-shadow grid-100 light-bg">
                        <div class="content-page content-holder grid-100">
                            <a href="{.//property[@name='publish_pic']/value}" class="fancybox thumbnail light-bg margin-bottom" title="" umi:field-name="publish_pic" umi:empty="Изображение публикации">
                                <xsl:variable name="img">
                                    <xsl:choose>
                                        <xsl:when test=".//property[@name='publish_pic']/value">
                                            <xsl:value-of select=".//property[@name='publish_pic']/value/@path" />
                                        </xsl:when>
                                        <xsl:otherwise>.&empty-photo;</xsl:otherwise>
                                    </xsl:choose>
                                </xsl:variable>
                                <img src="{document(concat('udata://system/makeThumbnailFull/(', .//property[@name='publish_pic']/value/@path, ')/660/200'))//src}" alt="" itemprop="image" />
                                <span class="thumbnail-arrow light-color active-border">
                                    <i class="icon-zoom-in" />
                                </span>
                            </a>

                            <h1 class="block-header header-font dark-color" umi:field-name="h1" umi:empty="Заголовок" itemprop="headline name">
                                <xsl:value-of select=".//property[@name='h1']/value" />
                            </h1>

                            <p class="blog-category dark-color active-hover" umi:field-name="subjects" umi:empty="Сюжеты">
                                <xsl:apply-templates select=".//property[@name = 'subjects']/value/item" mode="np-news-item-subj" />
                            </p>

                            <div class="blog-details middle-color clearfix">
                                <a class="middle-color dark-hover uppercase" href="#" umi:field-name="publish_time" umi:empty="Дата публикации">
                                    <xsl:value-of select="document(concat('udata://system/convertDate/',  .//property[@name = 'publish_time']/value/@unix-timestamp, '/(d.m.Y)'))/udata" />
                                    <meta itemprop="datePublished" content="{document(concat('udata://system/convertDate/',  .//property[@name = 'publish_time']/value/@unix-timestamp, '/(Y-m-d)'))/udata}" />
                                    <meta itemprop="dateModified" content="{document(concat('udata://system/convertDate/',  page/@update-time, '/(Y-m-d)'))/udata}" />
                                </a>
                            </div>

                            <div itemprop="articleBody">
                                <div umi:field-name="anons" umi:empty="Анонс" itemprop="description">
                                    <xsl:value-of select=".//property[@name = 'anons']/value" disable-output-escaping="yes" />
                                </div>
                                <div umi:field-name="content" umi:empty="Контент">
                                    <xsl:value-of select=".//property[@name = 'content']/value" disable-output-escaping="yes" />
                                </div>
                            </div>
                            <xsl:call-template name="organization-microdata">
                                <xsl:with-param name="itemprop" select="'author publisher'" />
                            </xsl:call-template>

                            <hr />
                            <div>
                                <xsl:value-of select="$site-info//property[@name='blok_dlya_vstavki_kommentariev']/value" disable-output-escaping="yes" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </xsl:template>

    <xsl:template match="item" mode="np-news-item-subj">
        <xsl:value-of select="@name" />
        <xsl:if test="position() != last()">
            <xsl:text>, </xsl:text>
        </xsl:if>
    </xsl:template>

    <xsl:template match="udata[@method = 'related_links']" />

    <xsl:template match="udata[@method = 'related_links' and count(items/item)]">
        <h4>
            <xsl:text>&news-realted;</xsl:text>
        </h4>

        <ul>
            <xsl:apply-templates select="items/item" mode="related" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="related">
        <li umi:element-id="{@id}">
            <a href="{@link}" umi:field-name="news">
                <xsl:value-of select="." />
            </a>
        </li>
    </xsl:template>

</xsl:stylesheet>