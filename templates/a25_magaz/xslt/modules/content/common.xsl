<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="result[@module = 'content']">
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
                            <div class="content-page grid-100" umi:element-id="{$document-page-id}">
                                <h1 class="active-color header-font with-border full" umi:field-name="h1" umi:empty="Заголовок">
                                    <xsl:value-of select=".//property[@name='h1']/value" />
                                </h1>
                                <div umi:field-name="content" umi:empty="Контент">
                                    <xsl:value-of select=".//property[@name='content']/value" disable-output-escaping="yes" />
                                </div>
                                <xsl:if test="page/@id = 8">
                                    <xsl:apply-templates select="document('udata://webforms/add/140/')/udata">
                                        <xsl:with-param name="class" select="' contact-target'" />
                                    </xsl:apply-templates>
                                </xsl:if>

                                <xsl:apply-templates select=".//property[@name='ssylka_na_razdely']/value" mode="ssylka_na_razdely" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
	</xsl:template>

        <xsl:template match="value" mode="ssylka_na_razdely" />
        <xsl:template match="value[page]" mode="ssylka_na_razdely">
            <div class="hits-categories catalog-hits">
                <xsl:apply-templates select="page" mode="ssylka_na_razdely" />
                <div class="clearfix" />
            </div>
        </xsl:template>

        <xsl:template match="page" mode="ssylka_na_razdely">
            <div xmlns:umi="http://www.umi-cms.ru/TR/umi" class="grid-33 tablet-grid-50" umi:element-id="{@id}">
                <div class="category-wrapper">
                    <a href="{@link}">
                        <xsl:variable name="img">
                            <xsl:choose>
                                <xsl:when test="document(concat('upage://', @id, '.header_pic'))//value/@path">
                                    <xsl:value-of select="document(concat('upage://', @id, '.header_pic'))//value/@path" />
                                </xsl:when>
                                <xsl:otherwise>./no_image_available.jpeg</xsl:otherwise>
                            </xsl:choose>
                        </xsl:variable>
                        <img class="" src="{document(concat('udata://system/makeThumbnailFull/(', $img, ')/285/151/'))//src}" alt="" umi:field-name="header_pic" umi:empty="Изображение заголовка" />
                        <span class="header-span" umi:field-name="name" umi:empty="Название страницы">
                            <xsl:value-of select="name" />
                        </span>
                    </a>
                </div>
            </div>
        </xsl:template>

        <xsl:template match="result[@module = 'content'][page/@is-default = '1']">
            <xsl:apply-templates select=".//property[@name = 'ssylka_na_slajder']/value/page" mode="mp-slider-block" />

            <section class="page-content">
                <div class="page-block cream-bg grid-container no-padding">
                    <div class="margin-bottom">
                        <div class="hits-header">
                            <h1 umi:element-id="{$document-page-id}" umi:field-name="zagolovok_razdelov" umi:empty="Заголовок разделов">
                                <xsl:value-of select=".//property[@name='zagolovok_razdelov']/value" />
                            </h1>
                        </div>
                        <div class="hits-content" umi:element-id="{$document-page-id}" umi:field-name="tekst_razdelov" umi:empty="Текст разделов">
                            <xsl:value-of select=".//property[@name='tekst_razdelov']/value" disable-output-escaping="yes" />
                        </div>
                        <div umi:element-id="{$document-page-id}" umi:field-name="ssylki_na_razdely" umi:empty="Ссылки на разделы" />
                        <div class="hits-categories">
                            <xsl:apply-templates select=".//property[@name='ssylki_na_razdely']/value/page" mode="mp-category-grid" />
                            <div class="clearfix" />
                        </div>
                    </div>
                </div>

                <div class="page-block cream-bg grid-container no-padding" umi:element-id="{$document-page-id}">
                    <div class="">
                        <div class="hits-header no-margin">
                            <h2 umi:field-name="zagolovok_kontrolya" umi:empty="Заголовок контроля">
                                <xsl:value-of select=".//property[@name='zagolovok_kontrolya']/value" />
                            </h2>
                        </div>
                        <div class="ctrl-wrapper">
                            <div class="ctrl-big grid-50 lazyload-bg" data-src="{.//property[@name='izobrazhenie_kontrolya']/value}">
                                <h5 umi:field-name="zagolovok_kartinki_kontrolya" umi:empty="Заголовок">
                                    <a href="{.//property[@name='ssylka_kontrolya']/value}">
                                        <xsl:value-of select=".//property[@name='zagolovok_kartinki_kontrolya']/value" />
                                    </a>
                                </h5>
                                <h2 umi:field-name="podzagolovok_kontrolya" umi:empty="Подзаголовок">
                                    <a href="{.//property[@name='ssylka_kontrolya']/value}">
                                        <xsl:value-of select=".//property[@name='podzagolovok_kontrolya']/value" />
                                    </a>
                                </h2>
                                <div class="span-divider"><span /><span /><span /></div>
                                <div umi:field-name="izobrazhenie_kontrolya" umi:empty="Изображение контроля" />
                                <div umi:field-name="ssylka_kontrolya" umi:empty="Ссылка контроля" />
                            </div>
                            <xsl:apply-templates select="." mode="ctrl-small">
                                <xsl:with-param name="number" select="'1'" />
                            </xsl:apply-templates>
                            <xsl:apply-templates select="." mode="ctrl-small">
                                <xsl:with-param name="number" select="'2'" />
                            </xsl:apply-templates>
                            <div class="clearfix" />
                        </div>
                    </div>
                </div>

                <div class="page-block cream-bg grid-container no-padding">
                    <div class="margin-bottom">
                        <div class="hits-header">
                            <h2 umi:element-id="{$document-page-id}" umi:field-name="zagolovok_sertifikatov" umi:empty="Заголовок сертификатов">
                                <xsl:value-of select=".//property[@name='zagolovok_sertifikatov']/value" />
                            </h2>
                        </div>
                        <div class="grid-100">
                            <xsl:apply-templates select=".//property[@name='izobrazheniya_sertifikatov']/value" mode="serts-img" />
                        </div>
                    </div>
                </div>

                <xsl:if test=".//group[@name = 'vkladki_tovarov']/property/value">
                    <div class="page-block page-tabs-block cream-bg grid-container">
                        <xsl:apply-templates select=".//group[@name = 'vkladki_tovarov']" mode="mp-tabs-header" />
                        <xsl:apply-templates select=".//group[@name = 'vkladki_tovarov']" mode="mp-tabs-content" />
                    </div>
                </xsl:if>

                <div class="page-block cream-bg grid-container">
                    <xsl:apply-templates select="document(concat('udata://banners/insert/(mp_middle)//1/', $document-page-id))/udata" mode="mp-banners" />

                    <div class="grid-100 margin-bottom">
                        <div class="tip dark-color light-bg">
                            <span class="tip-ribbon"></span>
                            <div umi:element-id="{$document-page-id}" umi:field-name="dop_kontent" umi:empty="Доп контент">
                                <xsl:value-of select=".//property[@name = 'dop_kontent']/value" disable-output-escaping="yes" />
                            </div>
                        </div>
                    </div>

                    <xsl:apply-templates select=".//property[@name = 'ssylka_na_novost']/value/page" mode="mp-news-block" />

                    <div class="grid-100 margin-bottom">
                        <div class="tip dark-color light-bg" umi:element-id="{$document-page-id}" umi:field-name="content" umi:empty="Контент">
                            <xsl:value-of select=".//property[@name = 'content']/value" disable-output-escaping="yes" />
                        </div>
                    </div>
                </div>
            </section>
        </xsl:template>

        <xsl:template match="result" mode="ctrl-small">
            <xsl:param name="number" />
            <div class="ctrl-small grid-50">
                <xsl:if test="$number = '2'">
                    <xsl:attribute name="class">ctrl-small grid-50 no-margin</xsl:attribute>
                </xsl:if>
                <div class="ctrl-header">
                    <i class="{.//property[@name=concat('ctrl_icon_', $number)]/value}" />
                    <h4 umi:field-name="ctrl_header_{$number}" umi:empty="Заголовок">
                        <xsl:value-of select=".//property[@name=concat('ctrl_header_', $number)]/value" />
                    </h4>
                    <div umi:field-name="ctrl_icon_{$number}" umi:empty="Класс иконки" />
                </div>
                <div class="ctrl-text" umi:field-name="ctrl_text_{$number}" umi:empty="Текст">
                    <xsl:value-of select=".//property[@name=concat('ctrl_text_', $number)]/value" disable-output-escaping="yes" />
                </div>
            </div>
        </xsl:template>

        <xsl:template match="value" mode="serts-img">
            <div class="grid-33 tablet-grid-33">
                <a href="{.}" class="thumbnail light-bg fancybox serts-img">
                    <img class="lazyload" src="/point.png" data-src="{document(concat('udata://system/makeThumbnail/(', @path, ')/285/360///1'))//src}" alt="" />
                    <span class="thumbnail-arrow light-color active-border">
                        <i class="icon-zoom-in" />
                    </span>
                </a>
            </div>
        </xsl:template>

        <xsl:template match="page" mode="mp-category-grid">
            <xsl:if test="position() &gt; 1 and position() mod 3 = 1">
                <div class="clearfix"></div>
            </xsl:if>

            <xsl:variable name="img">
                <xsl:choose>
                    <xsl:when test="document(concat('upage://', @id, '.header_pic'))//value">
                        <xsl:value-of select="document(concat('upage://', @id, '.header_pic'))//value" />
                    </xsl:when>
                    <xsl:otherwise>&empty-photo;</xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            <div class="grid-33 tablet-grid-33" umi:element-id="{@id}">
                <div class="category-wrapper">
                    <a href="{@link}">
                        <img class="lazyload" data-src="{document(concat('udata://system/makeThumbnailFull/(.', $img, ')/285/151'))//src}" src="/point.png" alt="" umi:field-name="header_pic" umi:empty="Изображение заголовка" />
                        <span class="header-span" umi:field-name="h1" umi:empty="Заголовок">
                            <xsl:value-of select="document(concat('upage://', @id, '.h1'))//value" />
                        </span>
                    </a>
                </div>
            </div>
        </xsl:template>

	<!--<xsl:include href="menu.xsl" />-->
	<xsl:include href="404.xsl" />
	<xsl:include href="sitemap.xsl" />
	<!--<xsl:include href="recentPages.xsl" />-->
	<xsl:include href="for_main_page.xsl" />

</xsl:stylesheet>