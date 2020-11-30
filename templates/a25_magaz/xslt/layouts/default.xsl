<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:umi="http://www.umi-cms.ru/TR/umi"
                xmlns:xlink="http://www.w3.org/TR/xlink"
                exclude-result-prefixes="umi xlink">

    <xsl:template match="/" mode="layout">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
        <html lang="ru">
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1" />
<!--                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
                <meta name="keywords" content="{//meta/keywords}" />
                <meta name="description" content="{//meta/description}" />
                <title>
                    <xsl:value-of select="$document-title" />
                </title>
                <meta property="og:type" content="website" />
                <meta property="og:url" content="https://{$domain}{$request-uri}" />
                <meta property="og:title" content="{$header}" />
                <meta property="og:description" content="{//meta/description}" />
                <xsl:choose>
                    <xsl:when test=".//property[@name='photo']/value">
                        <meta property="og:image" content="https://{$domain}{.//property[@name='photo']/value}" />
                        <meta property="og:image:width" content="{.//property[@name='photo']/value/@width}" />
                        <meta property="og:image:height" content="{.//property[@name='photo']/value/@height}" />
                    </xsl:when>
                    <xsl:when test=".//property[@name='publish_pic']/value">
                        <meta property="og:image" content="https://{$domain}{.//property[@name='publish_pic']/value}" />
                        <meta property="og:image:width" content="{.//property[@name='publish_pic']/value/@width}" />
                        <meta property="og:image:height" content="{.//property[@name='publish_pic']/value/@height}" />
                    </xsl:when>
                    <xsl:when test=".//property[@name='anons_pic']/value">
                        <meta property="og:image" content="https://{$domain}{.//property[@name='anons_pic']/value}" />
                        <meta property="og:image:width" content="{.//property[@name='anons_pic']/value/@width}" />
                        <meta property="og:image:height" content="{.//property[@name='anons_pic']/value/@height}" />
                    </xsl:when>
                    <xsl:when test=".//property[@name='header_pic']/value">
                        <meta property="og:image" content="https://{$domain}{.//property[@name='header_pic']/value}" />
                        <meta property="og:image:width" content="{.//property[@name='header_pic']/value/@width}" />
                        <meta property="og:image:height" content="{.//property[@name='header_pic']/value/@height}" />
                    </xsl:when>
                    <xsl:otherwise>
                        <meta property="og:image" content="https://{$domain}{$site-info//property[@name='og_logo']/value}" />
                        <meta property="og:image:width" content="{$site-info//property[@name='og_logo']/value/@width}" />
                        <meta property="og:image:height" content="{$site-info//property[@name='og_logo']/value/@height}" />
                    </xsl:otherwise>
                </xsl:choose>

<!--                <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/multy.css" type="text/css" />
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/font-awesome/css/font-awesome.min.css" type="text/css" />
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/js/juicy/css/juicy.css" type="text/css" />
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" />-->

                <link rel="stylesheet" href="/min/f=/templates/a25_magaz/mokup/css/multy.css,/templates/a25_magaz/mokup/css/font-awesome/css/font-awesome.min.css,/templates/a25_magaz/mokup/js/juicy/css/juicy.css,/templates/a25_magaz/mokup/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" />

                <xsl:choose>
                    <xsl:when test="result[@module = 'catalog' and @method = 'object']">
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/js/juicy/css/themes/a25_magaz-product/style.css" type="text/css" />
                    </xsl:when>
                    <xsl:when test="result[@module = 'news'][@method = 'rubric']">
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/blog.css" type="text/css" />
                    </xsl:when>
                    <xsl:when test="result[@module = 'news'][@method = 'item']">
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/blog.css" type="text/css" />
                    </xsl:when>
                    <xsl:otherwise>
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/js/juicy/css/themes/a25_magaz/style.css" type="text/css" />
                    </xsl:otherwise>
                </xsl:choose>

                <!--[if IE 7]>
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/font-awesome/css/font-awesome-ie7.min.css" />
                <![endif]-->

                <xsl:choose>
                    <xsl:when test="$site-info//property[@name='site_theme']/value">
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/colors/{$site-info//property[@name='site_theme']/value/item/@name}.css" type="text/css" />
                    </xsl:when>
                    <xsl:otherwise>
                        <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/colors/red.css" type="text/css" />
                    </xsl:otherwise>
                </xsl:choose>
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/base.css" type="text/css" />
                <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/layout.css" type="text/css" />
                <xsl:if test="result[@module = 'content'][page/@is-default = '1']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/homepage.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@method = 'category']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/products-listing.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'catalog' and @method = 'object']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/product-detail.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'emarket'][@method = 'cart']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/cart.css" type="text/css"/>
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/checkout.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'emarket'][@method = 'purchase']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/checkout.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'emarket'][@method = 'purchasing_one_step']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/checkout.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'emarket'][@method = 'summary']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/checkout.css" type="text/css" />
                </xsl:if>
                <xsl:if test="result[@module = 'users'][@method = 'settings']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/my-profile.css" type="text/css"/>
                </xsl:if>
                <xsl:if test="result[@module = 'emarket'][@method = 'personal']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/checkout.css" type="text/css" />
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/my-profile.css" type="text/css"/>
                </xsl:if>
                <xsl:if test="result[@module = 'users'][@method = 'registrate' or @method = 'forget']">
                    <link rel="stylesheet" href="/templates/a25_magaz/mokup/css/pages/contact.css" type="text/css"/>
                </xsl:if>

                <xsl:if test="$site-info//property[@name='favicon']/value">
                    <link rel="shortcut icon" href="{$site-info//property[@name='favicon']/value}" />
                </xsl:if>

                <!-- HTML5 Shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="/templates/a25_magaz/mokup/js/html5shim.js" />
                <![endif]-->
            </head>
            <body class="content-font dark-color {$site-bg}">
                <div id="fb-root"></div>
                <!-- div class="top_warning">В период до 17 августа наш офис работает удаленно. Все заказы будут обработаны и отгружены.</div -->
                <header>
                    <nav class="top-menu grid-container hide-on-tablet hide-on-mobile">
                        <div class="grid-100">
                            <div class="top-menu-left">
                                <ul>
                                    <li>
                                        <span class="dark-color">Продаем нитки по всей России</span>
                                    </li>
                                </ul>
                            </div>
                            <!--<xsl:apply-templates select="document('udata://menu/draw/mini_menu')/udata" mode="mini-menu" />-->

                            <div class="top-menu-right">
                                <ul>
                                    <li>
                                        <div class="remove-whitespaces">
                                            <div class="header-middle-box last-box">
                                                <form class="input-with-submit header-search" action="/search/search_do/" method="GET">
                                                    <input type="text" class="text-input input-round dark-color light-bg" value="" name="search_string" placeholder="Поиск нитей" />
                                                    <button type="submit" class="input-round-submit middle-color dark-hover srch-toggle">
                                                        <i class="icon-search" />
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <div class="header-middle grid-container light-gradient">
                        <div class="grid-100">
                            <a href="/" class="header-logo grid-20" title="" umi:element-id="{$site-info-id}" umi:field-name="site_logo" umi:empty="Логотип сайта">
                                <img
                                    class="lazyload"
                                    src="/point.png"
                                    data-src="{document(concat('udata://system/makeThumbnail/(', $site-info//property[@name = 'site_logo']/value/@path, ')/auto/64'))//src}" alt="Логотип компании Ozen Iplik" />
                            </a>

                            <div class="grid-80 remove-whitespaces">
                                <div class="header-middle-box last-box">
                                    <a class="top-phone dark-color" href="tel:{$site-info//property[@name='telefon_ssylka']/value}" umi:element-id="{$site-info-id}" umi:field-name="telefon" umi:empty="Телефон">
                                        <xsl:value-of select="$site-info//property[@name='telefon']/value" />
                                    </a>

                                    <div umi:element-id="{$site-info-id}" umi:field-name="telefon_ssylka" umi:empty="Телефон ссылка" />

                                    <a href="#modal-form-top" class="modal-form-button fancy button-normal light-color middle-gradient dark-gradient-hover">
                                        <xsl:value-of select="'Получить прайс лист'" />
                                    </a>
                                    <div style="display:none">
                                        <div id="modal-form-top">
                                            <div class="modal-form-top-area">
                                                <xsl:apply-templates select="document('udata://webforms/add/151')/udata">
                                                    <xsl:with-param name="form-header" select="'Получить прайс лист'" />
                                                    <xsl:with-param name="underhead-txt" select="'Для того, чтобы узнать цену на данный товар - заполните форму ниже и наши специалисты свяжутся с вами в ближайшее время.'" />
                                                </xsl:apply-templates>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <nav class="main-menu grid-container" id="main-menu">
                        <div class="mobile-overlay" />

                        <ul class="main-menu-mobile">
                            <li class="middle-color light-hover">
                                <a href="#menu-mobile" class="main-menu-item click-slide">
                                    <i class="icon-reorder"></i>
                                </a>
                            </li>

                            <li class="middle-color light-hover">
                                <a href="/" class="main-menu-item">
                                    <i class="icon-home"></i>
                                </a>
                            </li>

                            <!--<xsl:apply-templates select="document('udata://emarket/cart')/udata" mode="header-cart-mobile" />-->
                        </ul>

                        <!--   Tablet and desktop main menu    -->
                        <xsl:apply-templates select="document('udata://menu/draw/main_menu')/udata" mode="main-menu" />
                    </nav><!--    END Main Menu    -->
                </header>

                <xsl:apply-templates select="/result" />

                <footer>
                    <!-- Footer blocks  -->
                    <div class="footer-top grid-container clearfix">
                        <xsl:apply-templates select="document('udata://menu/draw/footer_menu')/udata" mode="footer-menu" />

                        <div class="grid-100 grid-parent" umi:element-id="{$site-info-id}" >
                            <div class="grid-75 tablet-grid-50">
                                <h3 class="light-color subheader-font" umi:field-name="footer_header_1" umi:empty="Заголовок контента футера 1">
                                    <strong>
                                        <xsl:value-of select="$site-info//property[@name = 'footer_header_1']/value" />
                                    </strong>
                                </h3>

                                <div umi:field-name="footer_content_1" umi:empty="Текст контента футера 1" class="middle-color">
                                    <xsl:value-of select="$site-info//property[@name = 'footer_content_1']/value" disable-output-escaping="yes" />
                                </div>
                                <xsl:value-of select="$site-info//property[@name = 'blok_podelitsya_v_footer']/value" disable-output-escaping="yes" />
								<p class="bottom_links"><a href="/content/sitemap/">Карта сайта</a></p>
                            </div>
                            <div class="grid-25 tablet-grid-50">
                                <xsl:if test="$site-info//property[@name = 'zagolovok_kontenta_futera_2']/value">
                                    <h3 class="light-color subheader-font" umi:field-name="zagolovok_kontenta_futera_2" umi:empty="Заголовок контента футера 2">
                                        <strong>
                                            <xsl:value-of select="$site-info//property[@name = 'zagolovok_kontenta_futera_2']/value" />
                                        </strong>
                                    </h3>
                                </xsl:if>

                                <div umi:field-name="footer_content_2" umi:empty="Текст контента футера 2" class="middle-color">
                                    <xsl:value-of select="$site-info//property[@name = 'footer_content_2']/value" disable-output-escaping="yes" />
                                </div>

<!--                                <form method="POST" action="{$lang-prefix}/dispatches/subscribe_do/" name="sbs_frm">
                                    <div class="input-with-button">
                                        <input type="text" placeholder="Введите свой E-Mail" name="sbs_mail" id="subscribe" class="text-input dark-color light-bg" />
                                        <button type="submit" class="middle-color dark-hover">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </form>-->
                            </div>
                        </div>
                    </div><!-- END Footer blocks  -->


                    <!-- Footer copyright and social buttons -->
                    <div class="footer-bottom grid-container clearfix" umi:element-id="{$site-info-id}">
                        <div class="footer-copyright middle-color grid-70" umi:field-name="footer_copyrights" umi:empty="Копирайты футера">
                            <xsl:value-of select="$site-info//property[@name = 'footer_copyrights']/value" disable-output-escaping="yes" />
                        </div>

                        <div class="footer-social grid-30" umi:element-id="{$site-info-id}">
                            <xsl:apply-templates select="$site-info//group[@name = 'social_media']/property" mode="social-media" />
                            <xsl:apply-templates select="$site-info//group[@name = 'social_media']/property" mode="social-media-eip" />
                        </div>
                    </div><!-- END Footer copyright and social buttons -->
                    <div umi:element-id="{$site-info-id}" umi:field-name="footer_counters" umi:empty="Поле для счетчиков">
                        <xsl:value-of select="$site-info//property[@name = 'footer_counters']/value" disable-output-escaping="yes" />
                    </div>
                </footer>

                <xsl:call-template name="organization-microdata" />

                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700" type="text/css" />

                <!-- Scripts -->
                <script src="/templates/a25_magaz/mokup/js/jquery-1.11.0.min.js" />
                <script>
                    var lastJQ = $.noConflict();
                </script>
<!--                <script src="/templates/a25_magaz/mokup/js/fancybox/jquery.fancybox-1.3.4.js" />
                <script src="/templates/a25_magaz/mokup/js/juicy/js/jquery.juicy.js" />
                <script src="/templates/a25_magaz/mokup/js/a25_magaz.scripts.js" />
                <script src="/templates/a25_magaz/mokup/js/noUiSlider/jquery.nouislider.min.js" />-->

                <script src="/min/f=/templates/a25_magaz/mokup/js/fancybox/jquery.fancybox-1.3.4.js,/templates/a25_magaz/mokup/js/juicy/js/jquery.juicy.js,/templates/a25_magaz/mokup/js/a25_magaz.scripts.js,/templates/a25_magaz/mokup/js/noUiSlider/jquery.nouislider.min.js" />

                <script>
                    Global.documentReady();
                </script>

                <xsl:if test="result[@module = 'content'][page/@is-default = '1']">
                    <script>
                        Homepage.documentReady();
                    </script>
                </xsl:if>
                <xsl:if test="result[@module = 'catalog' and @method = 'object']">
                    <script>
                        Product.documentReady();
                    </script>
                </xsl:if>

                <script src="/min/f=/templates/a25_magaz/mokup/js/custom.js" />


                <!-- css3-mediaqueries.js for IE less than 9 -->
                <!--[if lt IE 9]>
                <script src="/templates/a25_magaz/mokup/js/css3-mediaqueries.js" />
                <![endif]-->

                <xsl:if test="$is-admin">
                    <xsl:value-of select="document('udata://system/includeQuickEditJs')/udata" disable-output-escaping="yes" />
                </xsl:if>
                <script src="/templates/a25_magaz/js/i18n.{result/@lang}.js" />
                <script src="/templates/a25_magaz/js/__common.js" />
            </body>
        </html>
    </xsl:template>

    <xsl:template match="property" mode="social-media">
        <a href="{value}" class="middle-color light-hover transition-color" target="_blank">
            <i class="icon-{title}"></i>
        </a>
    </xsl:template>

    <xsl:template match="property" mode="social-media-eip">
        <div umi:field-name="{@name}" umi:empty="{title}" />
    </xsl:template>

<!--    <xsl:template match="udata" mode="mp-dispatch">
        <form method="POST" action="{$lang-prefix}/dispatches/subscribe_do/" name="sbs_frm">
            <div class="input-with-button">
                <input type="text" placeholder="Введите свой E-Mail" name="sbs_mail" id="subscribe" class="text-input dark-color light-bg" />
                <button type="submit" class="middle-color dark-hover">
                    <i class="icon-plus"></i>
                </button>
            </div>
        </form>
    </xsl:template>-->

    <xsl:template name="organization-microdata">
        <xsl:param name="itemprop" />
        <div itemscope="" itemtype="http://schema.org/Organization">
            <xsl:if test="$itemprop">
                <xsl:attribute name="itemprop">
                    <xsl:value-of select="$itemprop" />
                </xsl:attribute>
            </xsl:if>
            <meta itemprop="name" content="{$site-info//property[@name='nazvanie_organizacii']/value}" />
            <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                <link itemprop="image url" href="{$site-info//property[@name='site_logo']/value}"/>
            </div>
            <link itemprop="url" href="https://{$domain}" />
            <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                <meta itemprop="postalCode" content="{$site-info//property[@name='indeks']/value}" />
                <meta itemprop="addressLocality" content="{$site-info//property[@name='strana_gorod']/value}" />
                <meta itemprop="streetAddress" content="{$site-info//property[@name='ulica_dom_i_td']/value}" />
            </div>
            <meta itemprop="email" content="{$site-info//property[@name='email']/value}" />
            <meta itemprop="telephone" content="{$site-info//property[@name='telefon']/value}" />
        </div>
    </xsl:template>

</xsl:stylesheet>
