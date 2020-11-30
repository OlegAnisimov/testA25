<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">
    <xsl:variable name="filters" select="document(concat('udata://catalog/getSmartFilters//', $document-page-id, '/0/1'))/udata" />

<!--    <xsl:template match="/result[@method = 'category']">
        <section class="page-content">
            <xsl:apply-templates select="document('udata://core/navibar')/udata" />
            <div class="page-block page-block-bottom cream-bg grid-container">
                <div class="sidebar-shadow push-25"></div>

                <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                    <div class="sidebar-box sidebar-top cream-gradient">
                        <xsl:apply-templates select="document(concat('udata://catalog/getCategoryList/0/', $cat-srch-id, '/100/1'))/udata" mode="catalog-sidebar-list"/>
                    </div>
                </div>

                <div class="content-with-sidebar grid-75 grid-parent">
                    <xsl:apply-templates select="document('udata://banners/fastInsert/(cat_top)')/udata" mode="catalog-banner" />

                    <xsl:apply-templates select="document(concat('udata://catalog/getSmartFilters/0/', $document-page-id, '/0/10'))/udata" mode="catalog-smart-f" />

                    <div class="grid-100 margin-bottom">
                        <div class="well-shadow well-box last light-bg">
                            <div class="product-sort hide-on-mobile">
                                <a href="#" class="middle-color active-hover catalog-sort-head selected">Название</a>
                                <span class="sort-arrows">
                                    <a href="#" data-name="h1" data-asc="1" class="middle-color active-hover catalog-sort selected">
                                        <i class="icon-caret-up"></i>
                                    </a>
                                    <a href="#" data-name="h1" data-asc="0" class="middle-color active-hover catalog-sort">
                                        <i class="icon-caret-down"></i>
                                    </a>
                                </span>
                            </div>
                            <div class="product-sort hide-on-mobile">
                                <a href="#" class="middle-color active-hover catalog-sort-head">Цена</a>
                                <span class="sort-arrows">
                                    <a href="#" data-name="price" data-asc="1" class="middle-color active-hover catalog-sort">
                                        <i class="icon-caret-up"></i>
                                    </a>
                                    <a href="#" data-name="price" data-asc="0" class="middle-color active-hover catalog-sort">
                                        <i class="icon-caret-down"></i>
                                    </a>
                                </span>
                            </div>

                            <div class="product-per-page">
                                <div class="middle-color">
                                    <label for="products-per-page">Показать</label>
                                    <div class="custom-selectbox light-gradient active-hover">
                                        <select name="products-per-page" id="products-per-page">
                                            <option value="12">12 На страницу</option>
                                            <option value="24">24 На страницу</option>
                                            <option value="36">36 На страницу</option>
                                            <option value="48">48 На страницу</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="product-display">
                                <a href="#grid" class="middle-color active-hover selected catalog-style">
                                    <i class="icon-th"></i>
                                </a>
                                <a href="#list" class="middle-color active-hover catalog-style">
                                    <i class="icon-th-list"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <xsl:apply-templates select="document(concat('udata://catalog/getSmartCatalog/0/', $document-page-id, '/12/1/10/(h1)/1'))/udata" mode="catalog-smart-c" />

                    <div class="catalog-loader" data-id="{$document-page-id}">
                        <img src="/templates/a25_magaz/mokup/images/ajax-loader.gif" alt="" />
                    </div>
                </div>
            </div>
        </section>
    </xsl:template>-->

    <xsl:template match="/result[@method = 'category']">
        <xsl:value-of select="document('udata://content/redirect/(/shop/)/')" />
    </xsl:template>

    <xsl:template match="/result[@method = 'category'][page/@id]">
        <xsl:variable name="catalog_top_id">
            <xsl:choose>
                <xsl:when test="parents/page/@id">
                    <xsl:value-of select="parents/page[position() = 1]/@id" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="page/@id" />
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="form-id">
            <xsl:choose>
                <xsl:when test=".//property[@name='id_formy']/value">
                    <xsl:value-of select=".//property[@name='id_formy']/value" />
                </xsl:when>
                <xsl:otherwise>152</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="button-txt">
            <xsl:choose>
                <xsl:when test=".//property[@name='tekst_knopki']/value">
                    <xsl:value-of select=".//property[@name='tekst_knopki']/value" />
                </xsl:when>
                <xsl:otherwise>Узнать цену</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="underhead-txt">
            <xsl:choose>
                <xsl:when test=".//property[@name='tekst_pod_zagolovokom']/value">
                    <xsl:value-of select=".//property[@name='tekst_pod_zagolovokom']/value" />
                </xsl:when>
                <xsl:otherwise>Для того, чтобы узнать цену на данный товар - заполните форму ниже и наши специалисты свяжутся с вами в ближайшее время.</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <section class="page-content">
            <xsl:apply-templates select="document('udata://core/navibar')/udata" />
            <div class="page-block page-block-bottom cream-bg grid-container">
                <div class="sidebar-shadow push-25"></div>
                <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                    <div class="sidebar-box sidebar-top cream-gradient">
                        <!--<xsl:apply-templates select="document(concat('udata://catalog/getCategoryList/0/', $cat-srch-id, '/100/1'))/udata" mode="catalog-sidebar-list"/>-->
                        <xsl:apply-templates select="document(concat('udata://catalog/getCategoryList/0/', $catalog_top_id, '/100/1'))/udata" mode="catalog-sidebar-list"/>
                    </div>
                </div>

                <div class="content-with-sidebar grid-75 grid-parent">
                    <div class="grid-100 margin-bottom">
                        <xsl:if test=".//property[@name='vklyuchit_formu']/value = '1'">
                            <a href="#modal-form-{$form-id}" class="modal-form-button fancy button-normal light-color middle-gradient dark-gradient-hover">
                                <xsl:value-of select="$button-txt" />
                            </a>
                            <div style="display:none">
                                <div id="modal-form-{$form-id}">
                                    <xsl:apply-templates select="document(concat('udata://webforms/add/', $form-id))/udata">
                                        <xsl:with-param name="form-header" select="$button-txt" />
                                        <xsl:with-param name="underhead-txt" select="$underhead-txt" />
                                    </xsl:apply-templates>
                                </div>
                            </div>
                        </xsl:if>
                        <h1 umi:element-id="{page/@id}" umi:field-name="h1" umi:empty="Заголовок">
                            <xsl:value-of select=".//property[@name = 'h1']/value" disable-output-escaping="yes" />
                        </h1>
                        <!--Вывод подразделов для страницы "Нитки по направлениям"-->
                        <xsl:choose>
                            <xsl:when test="page/@id = 440 or page/@id = 240 or page/@id = 260 or page/@id = 479 or page/@id = 494">
                                <div umi:element-id="{page/@id}" umi:field-name="descr" umi:empty="Описание" class="descr">
                                    <xsl:value-of select=".//property[@name = 'descr']/value" disable-output-escaping="yes" />
                                </div>
                                <div class="hits-categories hits-categories--thread">
                                    <xsl:apply-templates select="document(concat('usel://get-child/', page/@id))//page" mode="mp-category-grid" />
                                    <div class="clearfix" />
                                </div>
                            </xsl:when>
                            <xsl:otherwise>
                                <div umi:element-id="{page/@id}" umi:field-name="descr" umi:empty="Описание" class="descr">
                                    <xsl:value-of select=".//property[@name = 'descr']/value" disable-output-escaping="yes" />
                                </div>
                            </xsl:otherwise>
                        </xsl:choose>
                        <xsl:apply-templates select=".//property[@name='karta_cvetov']" mode="colors-map" />
                        <xsl:if test=".//property[@name='vklyuchit_formu']/value = '1'">
                            <a href="#modal-form-{$form-id}" class="modal-form-button fancy button-normal light-color middle-gradient dark-gradient-hover">
                                <xsl:value-of select="$button-txt" />
                            </a>
                        </xsl:if>
                    </div>
                </div>
            </div>
        </section>
    </xsl:template>

    <xsl:template match="property" mode="colors-map" />
    <xsl:template match="property[value]" mode="colors-map">
        <div class="colors-map">
            <xsl:apply-templates select="value" mode="colors-map" />
        </div>
    </xsl:template>

    <xsl:template match="value" mode="colors-map">
        <a href="{.}" class="fancybox hidden" target="_blank" data-rel="colors-map">
            <xsl:if test="position() = '1'">
                <xsl:attribute name="class">
                    <xsl:text>fancybox button-normal colors-map-button light-color middle-gradient dark-gradient-hover</xsl:text>
                </xsl:attribute>
                <xsl:text>Карта цветов</xsl:text>
            </xsl:if>
        </a>
    </xsl:template>

    <xsl:template match="udata" mode="catalog-smart-c" />
    <xsl:template match="udata[lines/item]" mode="catalog-smart-c">
        <div class="catalog-items">
            <xsl:apply-templates select="lines/item" mode="catalog-smart-c" />
        </div>

        <div class="grid-100 clear-before">
            <a class="button-block middle-color dark-hover light-bg middle-border catalog-load-more" href="#" data-page="1">
                <xsl:if test="total &lt;= per_page">
                    <xsl:attribute name="style">display:none;</xsl:attribute>
                </xsl:if>
                <strong>Загрузить больше</strong> &#160;
                <i class="icon-repeat"></i>
            </a>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="catalog-smart-c">
        <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
        <xsl:variable name="price" select="document(concat('udata://emarket/price/', @id))/udata" />
        <xsl:variable name="parent-p" select="document(concat('upage://', $page-info//page/@parentId))/udata" />
        <div class="grid-33 tablet-grid-50" umi:element-id="{@id}">
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
                        <xsl:apply-templates select="$page-info//property[@name = 'osobennost_tovara']" mode="merch-sticker" />
                    </xsl:otherwise>
                </xsl:choose>

                <a class="product-img" href="{@link}" umi:field-name="photo" umi:empty="Фотография">
                    <span>
                        <xsl:choose>
                            <xsl:when test="$page-info//property[@name = 'photo']/value">
                                <img src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'photo']/value/@path, ')/150/170'))//src}" alt="" />
                            </xsl:when>
                            <xsl:otherwise>
                                <img src="/templates/a25_magaz/mokup/images/photos/img-product1.jpg" alt="" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </span>
                </a>

                <div class="product-info light-bg middle-border">
                    <h3 class="product-title subheader-font" umi:field-name="h1" umi:empty="Заголовок">
                        <a href="{@link}" class="dark-color active-hover">
                            <strong>
                                <xsl:value-of select="$page-info//property[@name = 'h1']/value" />
                            </strong>
                        </a>
                    </h3>
                    <a href="{$parent-p//page/@link}" class="product-category middle-color dark-hover">
                        <xsl:value-of select="$parent-p//page/name" />
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
        </div><!--  END Product   -->
    </xsl:template>

    <xsl:template match="udata" mode="catalog-sidebar-list" />
    <xsl:template match="udata[items/item]" mode="catalog-sidebar-list">
        <nav class="submenu">
            <ul class="expandable-menu">
                <li class="align-right back">
                    <a href="#sidebar-mobile" class="dark-color active-hover click-slide custom-slide">
                        <i class="icon-chevron-right" />
                    </a>
                </li>
                <xsl:apply-templates select="items/item" mode="catalog-sidebar-list" />
            </ul>
        </nav>
    </xsl:template>

    <xsl:template match="item" mode="catalog-sidebar-list">
        <xsl:variable name="sub-items" select="document(concat('udata://catalog/getCategoryList/0/', @id, '/100/1'))/udata" />
        <li>
            <!--<xsl:if test="$document-page-id = @id or $document-page-id = $sub-items//item/@id or $document-result/parents/page/@id = @id">-->
            <xsl:if test="$document-page-id = @id or $document-page-id = $sub-items//item/@id or $document-result/parents/page/@id = @id">
                <xsl:attribute name="class">expanded</xsl:attribute>
            </xsl:if>

            <xsl:if test="$sub-items//items/item">
                <span class="more_category">
                    <i class="icon-chevron-down"></i>
                </span>
            </xsl:if>

            <a href="{@link}" class="dark-color ">
                <xsl:attribute name="class">
                    <xsl:text>dark-color active-hover</xsl:text>
                    <xsl:if test="$document-page-id = @id or $document-page-id = $sub-items//item/@id or $document-result/parents/page/@id = @id">
                        <xsl:text> selected</xsl:text>
                    </xsl:if>
                </xsl:attribute>

                <xsl:value-of select="node()" />
            </a>
            <xsl:apply-templates select="$sub-items" mode="catalog-sidebar-list-sub"/>
        </li>
        <xsl:if test="position() != last()">
            <li class="sidebar-divider"></li>
        </xsl:if>
    </xsl:template>

    <xsl:template match="udata" mode="catalog-sidebar-list-sub" />
    <xsl:template match="udata[items/item]" mode="catalog-sidebar-list-sub">
        <ul>
            <xsl:apply-templates select="items/item" mode="catalog-sidebar-list-sub" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="catalog-sidebar-list-sub">
        <li>
            <a href="{@link}">
                <xsl:attribute name="class">
                    <xsl:text>dark-color active-hover</xsl:text>
                    <xsl:if test="(@id = $document-result/page/@id) or (@id = $document-result/parents/page/@id)">
                        <xsl:text> selected</xsl:text>
                    </xsl:if>
                </xsl:attribute>
                <b class="middle-color">&#8250;</b>&#160;
                <xsl:value-of select="node()" />
                <xsl:apply-templates select="document(concat('udata://catalog/getObjectsList/0/', @id, '/0/1'))/udata" mode="count-objs" />
            </a>
        </li>
    </xsl:template>

    <xsl:template match="udata" mode="count-objs">
        <xsl:if test="total &gt; 0">
            &#160;
            <small class="middle-color">(<xsl:value-of select="total" />)</small>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>