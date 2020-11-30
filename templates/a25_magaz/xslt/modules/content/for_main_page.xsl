<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:umi="http://www.umi-cms.ru/TR/umi">

    <xsl:template match="group" mode="mp-tabs-header">
        <div class="page-tabs grid-100 a25_magaz-tabs">
            <xsl:apply-templates select="property" mode="mp-tabs-header" />
        </div>
    </xsl:template>

    <xsl:template match="property" mode="mp-tabs-header">
        <h2 class="header-font">
            <a class="light-color active-hover dark-gradient cream-gradient-hover transition-color" href="#mp-tab-{@name}">
                <span class="hide-on-mobile">
                    <xsl:value-of select="title" />
                </span>
                <xsl:variable name="icon">
                    <xsl:choose>
                        <xsl:when test="@name ='rekomendovannye'">icon-thumbs-up</xsl:when>
                        <xsl:when test="@name ='rasprodazha'">icon-shopping-cart</xsl:when>
                        <xsl:when test="@name ='populyarnye'">icon-star</xsl:when>
                        <xsl:otherwise>icon-thumbs-up</xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>
                <i class="{$icon} hide-on-desktop hide-on-tablet"></i>
            </a>
        </h2>
    </xsl:template>

    <xsl:template match="group" mode="mp-tabs-content">
        <div class="page-tabs-holder">
            <xsl:apply-templates select="property" mode="mp-tabs-content" />
        </div>
    </xsl:template>

    <xsl:template match="property" mode="mp-tabs-content">
        <div class="page-tab" id="mp-tab-{@name}">
            <xsl:apply-templates select="value/page[position() &lt; 5]" mode="mp-tabs-content" />

            <xsl:if test="count(value/page) &gt; 4">
                <div class="grid-100 clear-before">
                    <a class="button-block middle-color dark-hover light-bg middle-border mp-tab-loadmore" href="#mp-tab-counter-{@name}" data-params="/{$document-page-id}/({@name})/" data-offset="4">
                        <strong>
                            <xsl:text>Загрузить больше  </xsl:text>
                        </strong>
                        <i class="icon-repeat"></i>
                    </a>
                </div>
                <div class="mp-tab-pages-counter" >
                    <strong>Показано <span id="mp-tab-counter-{@name}"><xsl:value-of select="count(value/page[position() &lt; 5])" /></span> из <xsl:value-of select="count(value/page)" /></strong>
                </div>
            </xsl:if>
        </div>
    </xsl:template>

    <xsl:template match="page" mode="mp-tabs-content">
        <xsl:variable name="price" select="document(concat('udata://emarket/price/', @id))/udata" />
        <div class="grid-25 tablet-grid-50" umi:element-id="{@id}">
            <div class="product-box light-bg">
                <xsl:choose>
                    <xsl:when test="$price/discount">
                        <xsl:variable name="discount-id" select="document(concat('uobject://', $price/discount/@id))//property[@name = 'discount_modificator_id']/value/item/@id" />
                        <div class="ribbon-small ribbon-red">
                            <div class="ribbon-inner">
                                <span class="ribbon-text">
                                    <xsl:text>Скидка </xsl:text>
                                    <xsl:value-of select="document(concat('uobject://', $discount-id))//property[@name = 'proc']/value" />
                                    <xsl:text>%</xsl:text>
                                </span>
                                <span class="ribbon-aligner"></span>
                            </div>
                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="document(concat('upage://', @id,'.osobennost_tovara'))" mode="merch-sticker" />
                    </xsl:otherwise>
                </xsl:choose>

                <a class="product-img" href="{@link}" umi:field-name="photo" umi:empty="Фотография">
                    <span>
                        <xsl:choose>
                            <xsl:when test="document(concat('upage://', @id,'.photo'))//value">
                                <img src="{document(concat('udata://system/makeThumbnailFull/(', document(concat('upage://', @id,'.photo'))//value/@path, ')/150/170'))//src}" alt="" />
                            </xsl:when>
                            <xsl:otherwise>
                                <img src="/templates/a25_magaz/mokup/images/photos/img-product1.jpg" alt="" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </span>
                </a>

                <div class="product-info light-bg middle-border">
                    <h3 class="product-title subheader-font"  umi:field-name="h1" umi:empty="Заголовок">
                        <a href="{@link}" class="dark-color active-hover">
                            <strong>
                                <xsl:value-of select="document(concat('upage://', @id,'.h1'))//value" />
                            </strong>
                        </a>
                    </h3>
                    <a href="{document(concat('upage://', @parentId))/udata/page/@link}" class="product-category middle-color dark-hover">
                        <xsl:value-of select="document(concat('upage://', @parentId))/udata/page/name" />
                    </a>

                    <div class="product-bottom">
                        <div class="product-price active-color">
                            <xsl:if test="$price/price/original">
                                <del class="light-gradient middle-border dark-color">
                                    <xsl:value-of select="concat($price/price/original, ' ', $price/price/@suffix)" />
                                </del>
                            </xsl:if>
                            <strong umi:field-name="price" umi:empty="Цена">
                                <xsl:value-of select="concat($price/price/actual, ' ', $price/price/@suffix)" />
                            </strong>
                        </div>
                        <div class="clear"></div>

                        <div class="button-dual light-color transition-all">
                            <a href="/emarket/basket/put/element/{@id}" class="button-dual-left middle-gradient dark-gradient-hover mp-cart-action">
                                <xsl:text>Добавить </xsl:text>
                                <i class="icon-shopping-cart"></i>
                            </a>
                            <a class="button-dual-right middle-gradient dark-gradient-hover">
                                <i class="icon-angle-down"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="property" mode="merch-sticker"/>
    <xsl:template match="property[value]" mode="merch-sticker">
        <div class="ribbon-small ribbon-{document(concat('uobject://', value/item/@id))//property[@name = 'cvet_stikera']/value/item/@name}">
            <div class="ribbon-inner">
                <span class="ribbon-text">
                    <xsl:value-of select="value/item/@name" />
                </span>
                <span class="ribbon-aligner"></span>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="page" mode="mp-news-block">
        <!-- Page block header  -->
        <div class="grid-100">
            <h2 class="active-color header-font" umi:element-id="{@id}" umi:field-name="h1" umi:empty="Заголовок">
                <xsl:value-of select="document(concat('upage://', @id))//property[@name = 'h1']/value" />
            </h2>
        </div>

        <xsl:apply-templates select="document(concat('udata://news/lastlist/', @id, '/0/3/?extProps=h1,anons,anons_pic'))" mode="mp-news"/>
    </xsl:template>

    <xsl:template match="page" mode="mp-slider-block">
        <xsl:apply-templates select="document(concat('usel://sub_pages/', @id))/udata" mode="mp-slider"/>
    </xsl:template>

    <xsl:template match="udata" mode="mp-slider" />
    <xsl:template match="udata[page]" mode="mp-slider">
        <div class="homepage-slider grid-container juicy-wrapper hide-on-mobile">
            <ul id="juicy-slider" class="juicy-slider">
                <xsl:apply-templates select="page" mode="mp-slider" />
            </ul>

            <div class="juicy-slider-nav juicy-bullets cream-border active-bg hide-on-tablet" data-type="bullets-thumbs" />
        </div>
    </xsl:template>

    <xsl:template match="page" mode="mp-slider">
        <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
        <li>
            <xsl:attribute name="data-change">
                <xsl:choose>
                    <xsl:when test="$page-info//property[@name = 'slide_effects']/value/item">
                        <xsl:apply-templates select="document(concat('uobject://', $page-info//property[@name = 'slide_effects']/value/item/@id))//group[@name = 'slide_options']/property" mode="mp-slide-effect" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text>slices:8 speed:1700</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            
            <!--<img class="juicy-bg" data-src-xl="{$page-info//property[@name = 'slide_pic']/value}" src="/point.png" data-thumb="{$page-info//property[@name = 'slide_thumb']/value}" alt=""/>-->
            <img
                class="juicy-bg"
                data-src-xl="{$page-info//property[@name = 'slide_pic']/value}"
                src="/point.png"
                data-thumb="{document(concat('udata://system/makeThumbnail/(', $page-info//property[@name = 'slide_thumb']/value/@path, ')/auto/64'))//src}"
                alt=""
            />

            <xsl:apply-templates select="$page-info//group[@name = 'slide_show_params']/property" mode="mp-slide-check-show">
                <xsl:with-param name="page" select="$page-info" />
            </xsl:apply-templates>
        </li>
    </xsl:template>

    <xsl:template match="property" mode="mp-slide-check-show">
        <xsl:param name="page" />
        <xsl:variable name="group-name" select="concat(@name,'_params')" />
        <xsl:apply-templates select="$page//group[@name = $group-name]" mode="mp-slide-block-add" />
    </xsl:template>

    <xsl:template match="group[@name = 'show_slide_header_params']" mode="mp-slide-block-add">
        <div style="top: {.//property[@name='slide_header_y']/value}px; left: {.//property[@name='slide_header_x']/value}px"
             data-show="at:700 effect:slide-fade direction:top speed:2000 easing:easeOutQuint"
             data-hide="effect:slide-fade direction:top speed:800"
             class="juicy-layer header-font dark-color slider-header dark-border">

            <xsl:value-of select=".//property[@name='slide_header']/value" />
        </div>
    </xsl:template>

    <xsl:template match="group[@name = 'show_slide_text_params']" mode="mp-slide-block-add">
        <div
            style="top: {.//property[@name='slide_text_y']/value}px; left: {.//property[@name='slide_text_x']/value}px"
            data-show="at:1000 effect:shift-fade direction:left speed:2000 easing:easeOutQuint"
            data-hide="effect:slide-fade direction:left speed:800"
            class="juicy-layer subheader-font dark-color slider-subheader">

            <xsl:value-of select=".//property[@name='slide_text']/value" disable-output-escaping="yes" />
        </div>
    </xsl:template>

    <xsl:template match="group[@name = 'show_slide_but_params']" mode="mp-slide-block-add">
        <a
            style="top: {.//property[@name='slide_but_y']/value}px; left: {.//property[@name='slide_but_x']/value}px"
            data-show="at:1300 effect:slide-fade direction:bottom speed:2000 easing:easeOutQuint"
            data-hide="effect:slide-fade direction:bottom speed:800"
            href="{.//property[@name='slide_but_link']/value}"
            class="juicy-layer button-normal button-with-icon light-color middle-gradient dark-gradient-hover">

            <xsl:value-of select=".//property[@name='slide_but_text']/value" />
            <span>
                <i class="icon-angle-right"></i>
            </span>
        </a>
    </xsl:template>

    <xsl:template match="group[@name = 'show_slide_prod_params']" mode="mp-slide-block-add">
        <div
            style="top: {.//property[@name='slide_prod_y']/value}px; left: {.//property[@name='slide_prod_x']/value}px"
            data-show="at:2500 effect:scale-fade direction:left speed:800 easing:easeInOutBack"
            data-hide="effect:scale-fade direction:left speed:500"
            class="juicy-layer popup-outside-trigger popup-circle dark-bg active-bg-hover transition-color">

            <img class="popup-circle-icon" src="/templates/a25_magaz/mokup/images/icons/img-slider-plus.png" alt="" />

            <xsl:apply-templates select="document(concat('upage://', .//property[@name = 'slide_prod_link']/value/page/@id))/udata" mode="mp-slide-product" />
        </div>
    </xsl:template>

    <xsl:template match="udata" mode="mp-slide-product">
        <xsl:variable name="price" select="document(concat('udata://emarket/price/', .//page/@id))/udata" />
        <ul class="product-slider-popup popup-bottom popup-box cream-bg">
            <li class="arrow">
                <span class="shadow cream-bg"></span>
            </li>
            <li class="focusor"></li>

            <li class="clearfix">
                <div class="product-popup-left">
                    <a href="{.//page/@link}">
                        <xsl:choose>
                            <xsl:when test=".//property[@name = 'photo']/value">
                                <img src="{document(concat('udata://system/makeThumbnailFull/(', .//property[@name = 'photo']/value/@path, ')/110/140'))//src}" alt="" />
                            </xsl:when>
                            <xsl:otherwise>
                                <img src="/templates/a25_magaz/mokup/images/photos/img-jacket.png" alt="" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </a>
                </div>
                <div class="product-popup-divider"></div>
                <div class="product-popup-right">
                    <ul>
                        <li class="product-popup-title dark-color active-hover">
                            <a href="{.//page/@link}">
                                <strong>
                                    <xsl:value-of select=".//property[@name = 'h1']/value" />
                                </strong>
                            </a>
                        </li>
                        <li class="product-popup-subtitle middle-color">
                            <xsl:value-of select=".//property[@name = 'description_short']/value" />
                        </li>
                        <xsl:if test="$price/price/original">
                            <li class="product-popup-subtitle middle-color">
                                <del>
                                    <xsl:value-of select="concat($price/price/original, ' ', $price/price/@suffix)" />
                                </del>
                            </li>
                        </xsl:if>

                        <li class="product-popup-price active-color">
                            <strong>
                                <xsl:value-of select="concat($price/price/actual, ' ', $price/price/@suffix)" />
                            </strong>
                        </li>
                    </ul>
                    <a href="/emarket/basket/put/element/{.//page/@id}" class="product-popup-button button-small button-with-icon light-color middle-gradient dark-gradient-hover mp-cart-action">
                        <xsl:text>Добавить</xsl:text>
                        <span>
                            <i class="icon-shopping-cart"></i>
                        </span>
                    </a>
                </div>
            </li>
        </ul>
    </xsl:template>

    <xsl:template match="group[@name = 'show_slide_stic_params']" mode="mp-slide-block-add">
        <div
            style="top: -67px; left: 764px"
            data-show="at:4000 effect:shift-fade direction:right speed:800 easing:easeInOutQuint"
            data-hide="effect:fade direction:left speed:500">
            <xsl:attribute name="class">
                <xsl:text>juicy-layer ribbon-big ribbon-</xsl:text>
                <xsl:choose>
                    <xsl:when test=".//property[@name = 'slide_stic_color']/value/item">
                        <xsl:value-of select=".//property[@name = 'slide_stic_color']/value/item/@name" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text>red</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>

            <span class="ribbon-small-text">
                <xsl:value-of select="//property[@name = 'slide_stic_txt_s']/value" />
            </span>
            <span class="ribbon-big-text">
                <xsl:value-of select="//property[@name = 'slide_stic_txt_l']/value" />
            </span>
            <span class="ribbon-aligner"></span>
        </div>
    </xsl:template>

    <xsl:template match="property" mode="mp-slide-effect">
        <xsl:if test="position() &gt; 1">
            <xsl:text> </xsl:text>
        </xsl:if>
        <xsl:value-of select="concat(@name, ':', value)" />
    </xsl:template>

</xsl:stylesheet>