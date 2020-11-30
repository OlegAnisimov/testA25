<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/result[@module = 'news'][@method = 'rubric']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="sidebar-shadow push-25"></div>
                    <!-- Sidebar  -->
                    <div class="sidebar cream-gradient grid-25 transition-all" id="sidebar-mobile">
                        <!-- Sidebar submenu box -->
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <xsl:apply-templates select="document('udata://news/get_news_subjs')/udata" mode="np-subj-list" />
                        </div><!-- END Sidebar submenu box -->
                    </div><!-- END Sidebar  -->

                    <!-- Content  -->
                    <div class="content-with-sidebar grid-75">
                        <div class="with-shadow grid-100 light-bg">
                            <div class="content-page grid-100 np-items-cont" itemscope="" itemtype="http://schema.org/ItemList">
                                <h1 class="active-color header-font with-border full" umi:element-id="{$document-page-id}" umi:field-name="h1" umi:empty="Заголовок">
                                    <xsl:value-of select=".//property[@name = 'h1']/value" />
                                </h1>

                                <xsl:apply-templates select="document('udata://news/get_news_list_by_subjs/')/udata" mode="np-news-list" />
                            </div>
                        </div>
                        <xsl:apply-templates select="document('udata://news/get_news_list_by_subjs/')/udata/total" mode="np-news-list-pagi" />
                    </div><!-- END Content  -->
                </div> <!-- END Page block  -->
            </section>
	</xsl:template>

	<xsl:template match="udata" mode="np-news-list">
            <xsl:apply-templates select="items/item" mode="np-news-list" />
	</xsl:template>

        <xsl:template match="item" mode="np-news-list">
            <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
            <div class="blog-list clearfix" itemprop="itemListElement" itemscope=""  itemtype="http://schema.org/NewsArticle">
                <meta itemprop="position" content="{position()}" />
                <!--<xsl:copy-of select="$page-info" />-->
                <link itemprop="url" href="{@link}" />
                <meta itemprop="datePublished" content="{document(concat('udata://system/convertDate/',  $page-info//property[@name = 'publish_time']/value/@unix-timestamp, '/(Y-m-d)'))/udata}" />
                <meta itemprop="dateModified" content="{document(concat('udata://system/convertDate/',  $page-info/page/@update-time, '/(Y-m-d)'))/udata}" />
                <div class="blog-list-image grid-40 tablet-grid-40">
                    <a href="{@link}" class="thumbnail light-bg">
                        <img itemprop="image" src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'anons_pic']/value/@path, ')/240/200'))//src}" alt="" />
                        <span class="thumbnail-arrow light-color active-border">
                            <i class="icon-zoom-in"></i>
                        </span>
                    </a>
                </div>
                <div class="blog-list-details grid-60 tablet-grid-60" itemprop="mainEntityOfPage">
                    <h2 class="blog-list-title">
                        <a href="{@link}" class="dark-color active-hover subheader-font" itemprop="headline name">
                            <xsl:value-of select="$page-info//property[@name = 'h1']/value" />
                        </a>
                    </h2>
                    <div class="blog-list-tags middle-color">
                        <xsl:apply-templates select="$page-info//property[@name = 'subjects']/value/item" mode="np-news-list-subj" />
                    </div>

                    <div itemprop="description">
                        <xsl:value-of select="$page-info//property[@name = 'anons']/value" disable-output-escaping="yes" />
                    </div>
                    
                    <xsl:call-template name="organization-microdata">
                        <xsl:with-param name="itemprop" select="'author publisher'" />
                    </xsl:call-template>

                    <div class="blog-list-actions middle-color">
                        <span class="hide-on-mobile">
                            <a class="middle-color dark-hover uppercase" href="{@link}">
                                <xsl:value-of select="document(concat('udata://system/convertDate/',  $page-info//property[@name = 'publish_time']/value/@unix-timestamp, '/(d.m.Y)'))/udata" />
                            </a>
                        </span>
                        <a class="blog-list-more middle-color dark-hover" href="{@link}">
                            <xsl:text>Подробнее </xsl:text>
                            <i class="icon-plus-sign"></i>
                        </a>
                    </div>
                </div>
            </div>

            <xsl:if test="position() != last()">
                <hr class="margin-bottom" />
            </xsl:if>
        </xsl:template>

        <xsl:template match="udata" mode="np-subj-list"/>
        <xsl:template match="udata[items/item]" mode="np-subj-list">
            <xsl:variable name="pg-lnk" select="document(concat('upage://', page_id))/udata/page/@link" />

            <nav class="submenu">
                <ul class="expandable-menu">
                    <li class="align-right back">
                        <a href="#sidebar-mobile" class="dark-color active-hover click-slide">
                            <i class="icon-chevron-right"></i>
                        </a>
                    </li>
                    <li class="expanded">
                        <a href="#" class="dark-color active-hover selected">Сюжеты</a>
                        <ul class="np-subj-select">
                            <li>
                                <a href="{$lang-prefix}{$pg-lnk}" data-id="0">
                                    <xsl:attribute name="class">
                                        <xsl:text>dark-color active-hover</xsl:text>
                                        <xsl:if test="$document-result/page/@id = page_id">
                                            <xsl:if test="$subj = '0'">
                                                <xsl:text> selected</xsl:text>
                                            </xsl:if>
                                        </xsl:if>
                                    </xsl:attribute>
                                    <b class="middle-color">&#8250; </b>
                                    <xsl:text>Все</xsl:text>
                                </a>
                            </li>
                            <xsl:apply-templates select="items/item" mode="np-subj-list">
                                <xsl:with-param name="lnk" select="$pg-lnk" />
                            </xsl:apply-templates>
                        </ul>
                    </li>
                </ul>
            </nav>
        </xsl:template>

        <xsl:template match="item" mode="np-subj-list">
            <xsl:param name="lnk" />
            <li>
                <a href="{$lang-prefix}{$lnk}?subj={@id}" data-id="{@id}">
                    <xsl:attribute name="class">
                        <xsl:text>dark-color active-hover</xsl:text>
                        <xsl:if test="@id = $subj">
                            <xsl:text> selected</xsl:text>
                        </xsl:if>
                    </xsl:attribute>
                    <b class="middle-color">&#8250; </b>
                    <xsl:value-of select="@name" />
                </a>
            </li>
        </xsl:template>

        <xsl:template match="item" mode="np-news-list-subj">
            <a href="?subj={@id}" class="middle-color dark-hover">
                <xsl:value-of select="@name" />
            </a>
            <xsl:if test="position() != last()">
                <xsl:text>, </xsl:text>
            </xsl:if>
        </xsl:template>


	<xsl:template match="item" mode="news-list">
		<div umi:element-id="{@id}" umi:region="row">
			<dt>
				<div class="date" umi:field-name="publish_time" umi:empty="&empty-page-date;">
					<xsl:apply-templates select=".//property[@name = 'publish_time']" />
				</div>

				<a href="{@link}" umi:field-name="name" umi:delete="delete" umi:empty="&empty-page-name;">
					<xsl:value-of select="node()" />
				</a>
			</dt>
			<dd umi:field-name="anons" umi:empty="&empty-page-anons;">
				<xsl:value-of select=".//property[@name = 'anons']/value" disable-output-escaping="yes" />
			</dd>
		</div>
	</xsl:template>

        <xsl:template match="udata" mode="mp-news">
            <!-- Blog items  -->
            <div class="blog-grid margin-bottom clearfix">
                <xsl:apply-templates select="items/item" mode="mp-news" />
            </div><!-- END Blog items  -->
        </xsl:template>

        <xsl:template match="item" mode="mp-news">
            <!-- Grid block item  -->
            <div class="blog-item grid-33 tablet-grid-33" umi:element-id="{@id}">
                <h3 class="blog-title subheader-font">
                    <a href="{@link}" class="dark-color active-hover" umi:field-name="h1" umi:empty="Заголовок">
                        <strong>
                            <xsl:value-of select=".//property[@name = 'h1']/value" />
                        </strong>
                    </a>
                </h3>

                <div class="blog-info middle-color">
                    <a href="{@link}" class="active-hover" umi:field-name="publish_time" umi:empty="Дата публикации">
                        <xsl:value-of select="document(concat('udata://system/convertDate/', @publish_time ,'/(d.m.Y)'))/udata" />
                    </a>
                </div>

                <a href="{@link}" class="thumbnail light-bg" umi:field-name="anons_pic" umi:empty="Изображение анонса">
                    <img src="{document(concat('udata://system/makeThumbnailFull/(', .//property[@name = 'anons_pic']/value/@path, ')/285/150'))//src}" alt="" />
                    <span class="thumbnail-arrow light-color active-border">
                        <i class="icon-zoom-in"></i>
                    </span>
                </a>

                <div class="blog-description dark-color" umi:field-name="anons" umi:empty="Анонс">
                    <xsl:value-of select=".//property[@name = 'anons']/value" disable-output-escaping="yes" />
                </div>
            </div> <!-- END Grid block item  -->
        </xsl:template>
</xsl:stylesheet>