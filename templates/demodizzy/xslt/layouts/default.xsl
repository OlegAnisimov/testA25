<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="/" mode="layout">
		<html>
			<head>
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="keywords" content="{//meta/keywords}" />
				<meta name="description" content="{//meta/description}" />
				<title><xsl:value-of select="$document-title" /></title>

				<xsl:if test="result/@pageId">
					<xsl:variable name="relCanonical" select="document(concat('udata://seo/getRelCanonical/void/', result/@pageId))/udata/@link" />
					<xsl:if test="$relCanonical">
						<link rel="canonical">
							<xsl:attribute name="href">
								<xsl:value-of select="$relCanonical" />
							</xsl:attribute>
						</link>
					</xsl:if>
				</xsl:if>

				<link rel="stylesheet" href="/templates/demodizzy/css/jquery-ui.css" />

				<xsl:value-of select="document('udata://system/includeQuickEditJs')/udata" disable-output-escaping="yes" />
				<script type="text/javascript" src="/templates/demodizzy/js/i18n.{result/@lang}.js"></script>
				<script type="text/javascript" charset="utf-8" src="/templates/demodizzy/js/__common.js"></script>

				<link type="text/css" rel="stylesheet" href="/templates/demodizzy/css/common.css" />

				<xsl:if test="$method = 'compare'">
					<link type="text/css" rel="stylesheet" href="/templates/demodizzy/css/two_left.css" />
				</xsl:if>
				<script type="text/javascript" src="http://apis.google.com/js/plusone.js">
					{lang: 'ru'}
				</script>
				<!-- social buttons-->
				<script type="text/javascript" src="https://vk.com/js/api/openapi.js"></script>
				<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
				<!-- /social buttons-->
				<link media="print" type="text/css" rel="stylesheet" href="/templates/demodizzy/css/print.css" />
			</head>

			<body>
				<xsl:attribute name="style">
					<xsl:value-of select="concat('background: url(', document($site-info[@name='site_theme']/value/item/@xlink:href)/udata//value, ') 50% 0% repeat-x #fff;')" />
				</xsl:attribute>
				
				<div id="main">
					<div id="head">
						<a id="logo" href="{result/@pre-lang}/" umi:element-id="{$site-info-id}" umi:field-name="site_logo">
							<xsl:attribute name="style">
								<xsl:value-of select="concat('background-image: url(', $site-info[@name = 'site_logo']/value, ')')" />
							</xsl:attribute>
						</a>
						<div class="site_info" itemscope="itemscope" itemtype="http://schema.org/Organization">
							<xsl:choose>
								<xsl:when test="$lang-prefix = '/en'">
									<xsl:apply-templates select="document('usel://info-pages')/udata" mode="info-pages-head" />
								</xsl:when>
								<xsl:otherwise>
									<xsl:apply-templates select="document('udata://menu/draw/hmenu')/udata" mode="info-pages-head" />
								</xsl:otherwise>
							</xsl:choose>
							<div class="work" umi:element-id="{$site-info-id}">
								<div itemprop="telephone" class="phone" umi:field-name="phone">
									<xsl:value-of select="$site-info[@name = 'phone']/value" />
								</div>
								<div umi:field-name="order_info_top">
									<xsl:value-of select="$site-info[@name = 'order_info_top']/value" disable-output-escaping="yes" />
								</div>
							</div>
						</div>
						<xsl:apply-templates select="document('udata://emarket/cart')" mode="basket" />
                        <div class="callback_form">
                            <h2><a href="#callback_form">Заказать звонок</a></h2>
                        </div>
					</div>
					<xsl:apply-templates select="document('udata://content/menu/(null)/1/')" mode="top_menu" />
					<div id="page">
						<div class="right">
							<xsl:apply-templates select="/result/user" />

							<xsl:choose>
								<xsl:when test="$is-two-columns">
									<div class="cards" umi:element-id="{$site-info-id}" umi:field-name="order_info_left">
										<xsl:value-of select="$site-info[@name = 'order_info_left']/value" disable-output-escaping="yes" />
									</div>
								</xsl:when>
								<xsl:otherwise>
                                    <xsl:apply-templates select="document('upage://(news1/speshite_akciya)')" mode="right-column-counter" />
									<xsl:apply-templates select="document('udata://news/lastlist/(news)')" mode="right-column-news" />
									<xsl:apply-templates select="document('udata://emarket/getCompareList')" />
									<xsl:apply-templates select="document('udata://vote/insertlast/')" />
									<xsl:apply-templates select="document('udata://dispatches/subscribe/')/udata" mode="right" />
									<xsl:apply-templates select="document('udata://content/getRecentPages////3')/udata" mode="right" />
									<xsl:apply-templates select="document('udata://banners/fastInsert/right_block/')/udata" mode="right" />
								</xsl:otherwise>
							</xsl:choose>
						</div>
						<xsl:if test="$is-two-columns = false()">	
							<div class="left">
								<xsl:call-template name="search-form-left-column" />

								<xsl:apply-templates select="document('udata://catalog/getCategoryList/void/shop//1/')" mode="left-column" />

								<div class="cards" umi:element-id="{$site-info-id}" umi:field-name="order_info_left">
									<xsl:value-of select="$site-info[@name = 'order_info_left']/value" disable-output-escaping="yes" />
								</div>
								<div class="hours" umi:element-id="{$site-info-id}" umi:field-name="payment_info_left">
									<xsl:value-of select="$site-info[@name = 'payment_info_left']/value" disable-output-escaping="yes" />
								</div>
								<div>
									<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FUMI.CMS&amp;width=188&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false&amp;height=293" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:188px; height:293px; margin: 50px 0 10px;" allowTransparency="true"></iframe>
									<!-- VK Widget -->
									<div id="vk_groups" style="margin-bottom: 10px;"></div>
									<script type="text/javascript">
										VK.Widgets.Group("vk_groups", {mode: 0, width: "188", height: "293"}, 23325076);
									</script>
								</div>
							</div>
						</xsl:if>
						<div class="center">
							<xsl:if test="$is-two-columns">
								<xsl:attribute name="style">margin-left: 15px;</xsl:attribute>
							</xsl:if>
							<xsl:apply-templates select="$errors" />
							<div class="content">
								<xsl:apply-templates select="document('udata://core/navibar/')/udata" mode="navibar_partial" />
								<xsl:apply-templates select="result" mode="header" />
								<xsl:apply-templates select="result" />
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div id="foot">
						<xsl:apply-templates select="document('usel://info-pages')/udata" mode="info-pages-foot" />
						<div class="copyright">
							Copyright <xsl:value-of select="document('udata://system/convertDate/now/(Y)')/udata" />. <xsl:text>&copyright;</xsl:text>
						</div>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>
