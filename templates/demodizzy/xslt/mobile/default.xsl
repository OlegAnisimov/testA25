<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">
	
	<xsl:output encoding="utf-8" method="html" indent="yes"/>

	<xsl:variable name="errors"	select="document('udata://system/listErrorMessages')/udata"/>

	<xsl:variable name="lang-prefix" select="/result/@pre-lang" />
	<xsl:variable name="document-page-id" select="/result/@pageId" />
	<xsl:variable name="document-title" select="/result/@title" />
	<xsl:variable name="user-type" select="/result/user/@type" />
	<xsl:variable name="request-uri" select="/result/@request-uri" />
	<xsl:variable name="domain" select="/result/@domain" />
	<xsl:variable name="type_user" select="/result/user/@type" />
	
	<xsl:variable name="parent_link" select="/result/parents/page[last()]/@link" />
	
	<xsl:variable name="site-info-id" select="document('upage://contacts')/udata/page/@id" />
	<xsl:variable name="site-info" select="document('upage://contacts')//group[@name = 'site_info']/property" />
	
	<xsl:variable name="user-id" select="/result/user/@id" />
	<xsl:variable name="user-info" select="document(concat('uobject://', $user-id))" />
	
	<xsl:variable name="module" select="/result/@module" />
	<xsl:variable name="method" select="/result/@method" />
	
	<xsl:param name="p">0</xsl:param>
	<xsl:param name="search_string" />

	<xsl:include href="layouts/default.xsl" />
	<xsl:include href="library/common.xsl" />

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

</xsl:stylesheet>