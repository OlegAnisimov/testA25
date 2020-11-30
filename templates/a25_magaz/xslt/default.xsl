<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:output encoding="utf-8" method="html" indent="yes"/>

	<xsl:variable name="errors"	select="document('udata://system/listErrorMessages')/udata"/>

	<xsl:variable name="lang-prefix" select="/result/@pre-lang" />
	<xsl:variable name="document-page-id" select="/result/@pageId" />
	<xsl:variable name="document-title" select="/result/@title" />
	<xsl:variable name="user-type" select="/result/user/@type" />
	<xsl:variable name="user-stat" select="/result/user/@status" />
	<xsl:variable name="is-admin" select="$user-type = 'sv' or $user-type = 'admin'" />
	<xsl:variable name="request-uri" select="/result/@request-uri" />
	<xsl:variable name="domain" select="/result/@domain" />
        <xsl:variable name="header" select="/result/@header"/>

	<xsl:variable name="site-info-id" select="document('upage://contacts')/udata/page/@id" />
	<xsl:variable name="site-info" select="document('upage://contacts')" />
	<xsl:variable name="cat-srch-id" select="$site-info//property[@name = 'catalog_search_el']/value/page/@id" />
        <xsl:variable name="site-bg">
            <xsl:choose>
                <xsl:when test="$site-info//property[@name='site_bg']/value">
                    <xsl:value-of select="document(concat('uobject://', $site-info//property[@name='site_bg']/value/item/@id, '.id_fona'))//value" />
                </xsl:when>
                <xsl:otherwise>bgrmb</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

	<xsl:variable name="document-result" select="/result" />
	<xsl:variable name="user-id" select="/result/user/@id" />
	<xsl:variable name="user-info" select="document(concat('uobject://', $user-id))" />

	<xsl:variable name="module" select="/result/@module" />
	<xsl:variable name="method" select="/result/@method" />

	<xsl:variable name="is-two-columns" select="($module = 'emarket' and $method != 'compare' and $method != 'personal' and $method != 'ordersList') or ($module = 'appointment' and $method = 'page')" />

	<xsl:variable name="currency-prefix" select="document('udata://emarket/cart/')/udata/summary/price/@prefix" />
	<xsl:variable name="currency-suffix" select="document('udata://emarket/cart/')/udata/summary/price/@suffix" />

	<xsl:variable name="purchase-method" select="document('udata://emarket/getPurchaseLink')/udata" />

	<xsl:param name="p">0</xsl:param>
	<xsl:param name="nolog">0</xsl:param>
	<xsl:param name="subj">0</xsl:param>
	<xsl:param name="catalog" />
	<xsl:param name="sort_field" />
	<xsl:param name="sort_direction" />
	<xsl:param name="search_string" />

	<xsl:include href="layouts/default.xsl" />
	<xsl:include href="library/common.xsl" />

	<!--<xsl:include href="modules/appointment/common.xsl" />-->
	<xsl:include href="modules/content/common.xsl" />
	<xsl:include href="modules/users/common.xsl" />
	<xsl:include href="modules/catalog/common.xsl" />
	<xsl:include href="modules/data/common.xsl" />
	<xsl:include href="modules/emarket/common.xsl" />
	<xsl:include href="modules/search/common.xsl" />
	<xsl:include href="modules/news/common.xsl" />
	<!--<xsl:include href="modules/comments/common.xsl" />-->
	<xsl:include href="modules/webforms/common.xsl" />
	<xsl:include href="modules/banners/common.xsl" />
	<!--<xsl:include href="modules/blogs20/common.xsl" />-->
	<xsl:include href="modules/dispatches/common.xsl" />
	<!--<xsl:include href="modules/faq/common.xsl" />-->
	<!--<xsl:include href="modules/filemanager/common.xsl" />-->
	<!--<xsl:include href="modules/forum/common.xsl" />-->
	<!--<xsl:include href="modules/photoalbum/common.xsl" />-->
	<!--<xsl:include href="modules/vote/common.xsl" />-->
	<xsl:include href="modules/menu/common.xsl" />
	<!--<xsl:include href="modules/appointment/common.xsl" />-->

</xsl:stylesheet>