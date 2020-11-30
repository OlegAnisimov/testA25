<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:output encoding="utf-8" method="html" indent="yes"/>

<!--	<xsl:variable name="errors"	select="document('udata://system/listErrorMessages')/udata"/>

	<xsl:variable name="lang-prefix" select="/result/@pre-lang" />
	<xsl:variable name="document-page-id" select="/result/@pageId" />
	<xsl:variable name="document-title" select="/result/@title" />
	<xsl:variable name="user-type" select="/result/user/@type" />
	<xsl:variable name="user-stat" select="/result/user/@status" />
	<xsl:variable name="is-admin" select="$user-type = 'sv' or $user-type = 'admin'" />
	<xsl:variable name="request-uri" select="/result/@request-uri" />
	<xsl:variable name="domain" select="/result/@domain" />

	<xsl:variable name="site-info-id" select="document('upage://contacts')/udata/page/@id" />
	<xsl:variable name="site-info" select="document('upage://contacts')" />
	<xsl:variable name="cat-srch-id" select="document('upage://contacts')//property[@name = 'catalog_search_el']/value/page/@id" />

	<xsl:variable name="document-result" select="/result" />
	<xsl:variable name="user-id" select="/result/user/@id" />
	<xsl:variable name="user-info" select="document(concat('uobject://', $user-id))" />

	<xsl:variable name="module" select="/result/@module" />
	<xsl:variable name="method" select="/result/@method" />

	<xsl:variable name="is-two-columns" select="($module = 'emarket' and $method != 'compare' and $method != 'personal' and $method != 'ordersList') or ($module = 'appointment' and $method = 'page')" />

	<xsl:variable name="currency-prefix" select="document('udata://emarket/cart/')/udata/summary/price/@prefix" />
	<xsl:variable name="currency-suffix" select="document('udata://emarket/cart/')/udata/summary/price/@suffix" />

	<xsl:variable name="purchase-method" select="document('udata://emarket/getPurchaseLink')/udata" />-->

	<!--<xsl:param name="p">0</xsl:param>-->
	<!--<xsl:param name="subj">0</xsl:param>-->
<!--	<xsl:param name="catalog" />
	<xsl:param name="sort_field" />
	<xsl:param name="sort_direction" />
	<xsl:param name="search_string" />

	<xsl:include href="layouts/default.xsl" />
	<xsl:include href="library/common.xsl" />

	<xsl:include href="modules/appointment/common.xsl" />
	<xsl:include href="modules/content/common.xsl" />
	<xsl:include href="modules/users/common.xsl" />
	<xsl:include href="modules/catalog/common.xsl" />
	<xsl:include href="modules/data/common.xsl" />
	<xsl:include href="modules/emarket/common.xsl" />
	<xsl:include href="modules/search/common.xsl" />
	<xsl:include href="modules/news/common.xsl" />
	<xsl:include href="modules/comments/common.xsl" />
	<xsl:include href="modules/webforms/common.xsl" />
	<xsl:include href="modules/banners/common.xsl" />
	<xsl:include href="modules/blogs20/common.xsl" />
	<xsl:include href="modules/dispatches/common.xsl" />
	<xsl:include href="modules/faq/common.xsl" />
	<xsl:include href="modules/filemanager/common.xsl" />
	<xsl:include href="modules/forum/common.xsl" />
	<xsl:include href="modules/photoalbum/common.xsl" />
	<xsl:include href="modules/vote/common.xsl" />
	<xsl:include href="modules/menu/common.xsl" />
	<xsl:include href="modules/appointment/common.xsl" />-->

        <xsl:template match="/">
            <xsl:apply-templates select="udata/items/item" mode="np-news-list" />
            <div class="chk-for-more-news">
                <xsl:apply-templates select="udata/total" mode="np-news-list-pagi" />
            </div>
        </xsl:template>

        <xsl:template match="total" mode="np-news-list-pagi">
            <xsl:attribute name="data-page">false</xsl:attribute>
        </xsl:template>
        <xsl:template match="total[. &gt; ../per_page]" mode="np-news-list-pagi">
            <xsl:apply-templates select="document(concat('udata://system/numpages/', ., '/', ../per_page))/udata" mode="np-news-list-pagi" />
        </xsl:template>

        <xsl:template match="udata" mode="np-news-list-pagi">
            <xsl:choose>
                <xsl:when test="tonext_link">
                    <xsl:apply-templates select="tonext_link" mode="np-news-list-pagi" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="data-page">false</xsl:attribute>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:template>

        <xsl:template match="tonext_link" mode="np-news-list-pagi">
            <xsl:attribute name="data-page"><xsl:value-of select="@page-num" /></xsl:attribute>
        </xsl:template>

        <xsl:template match="item" mode="np-news-list">
            <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
            <hr class="margin-bottom" />
            <div class="blog-list clearfix">
                <div class="blog-list-image grid-40 tablet-grid-40">
                    <a href="{@link}" class="thumbnail light-bg">
                        <img src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'anons_pic']/value/@path, ')/240/200'))//src}" alt="" />
                        <span class="thumbnail-arrow light-color active-border">
                            <i class="icon-zoom-in"></i>
                        </span>
                    </a>
                </div>
                <div class="blog-list-details grid-60 tablet-grid-60">
                    <h2 class="blog-list-title">
                        <a href="{@link}" class="dark-color active-hover subheader-font">
                            <xsl:value-of select="$page-info//property[@name = 'h1']/value" />
                        </a>
                    </h2>
                    <div class="blog-list-tags middle-color">
                        <xsl:apply-templates select="$page-info//property[@name = 'subjects']/value/item" mode="np-news-list-subj" />
                    </div>

                    <div>
                        <xsl:value-of select="$page-info//property[@name = 'anons']/value" disable-output-escaping="yes" />
                    </div>

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
        </xsl:template>

</xsl:stylesheet>