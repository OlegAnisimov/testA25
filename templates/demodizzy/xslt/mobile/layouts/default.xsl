<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/" mode="layout">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="keywords" content="{//meta/keywords}" />
				<meta name="description" content="{//meta/description}" />
				<title><xsl:value-of select="$document-title" /></title>
				<link  media="screen" rel="Stylesheet" type="text/css"  href="/templates/demodizzy/css/mobile.css" />
			</head>
			<body>
			<table>
				<tr>
					<td width="50%">
						<a href="/">
							<img width="90%" alt="" src="/images/cms/data/site_logo/logo.gif" />
						</a>
						<div class="phone">765-43-21</div>
					</td>
					<td><xsl:apply-templates select="document('udata://emarket/cart')" mode="basket" /></td>
				</tr>
			</table>
			<div><xsl:call-template name="search-form-left-column" /></div>
			<div>
				<xsl:choose>
					<xsl:when test="//page/@type-id = '44'">
						<xsl:apply-templates select="document('udata://catalog/getCategoryList/void/shop/')" mode="left-column" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:apply-templates select="$errors" />
						<xsl:apply-templates select="result" mode="header" />
						<div class="content">
							<xsl:apply-templates select="result" />
						</div>
					</xsl:otherwise>
				</xsl:choose>
			</div>

			<div class="links">
				<xsl:apply-templates select="document('udata://content/menu/(null)/1/')" mode="top_menu" />
			</div>
			<div class="links">
				<p><a href="{document('udata://content/getMobileModesList/')//item[@status='active']/@link}">&mobile-mode-off;</a></p>
			</div>
			</body>
		</html>
	</xsl:template>

</xsl:stylesheet>