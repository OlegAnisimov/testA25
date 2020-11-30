<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/">
            <xsl:apply-templates select="udata" mode="catalog-smart-c" />
	</xsl:template>

        <xsl:template match="udata" mode="catalog-smart-c" />
        <xsl:template match="udata[lines/item]" mode="catalog-smart-c">
            <xsl:apply-templates select="lines/item" mode="catalog-smart-l" />
        </xsl:template>

        <xsl:template match="item" mode="catalog-smart-l">
            <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
            <xsl:variable name="price" select="document(concat('udata://emarket/price/', @id))/udata" />
            <xsl:variable name="parent-p" select="document(concat('upage://', $page-info//page/@parentId))/udata" />
            <div class="grid-100" umi:element-id="{@id}">
                <div class="product-wide light-bg clearfix item-for-reload" id="catalog-rel-{@id}">
                    <div class="sticker-cont">
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
                    </div>

                    <div class="grid-15 tablet-grid-20 mobile-grid-35 tablet-grid-20 product-img-holder" umi:field-name="photo" umi:empty="Фотография">
                        <a class="product-img" href="{@link}">
                            <xsl:choose>
                                <xsl:when test="$page-info//property[@name = 'photo']/value">
                                    <img src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'photo']/value/@path, ')/150/170'))//src}" alt="" class="img-cont" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <img src="/templates/a25_magaz/mokup/images/photos/img-product1.jpg" alt="" class="img-cont" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </a>
                    </div>
                    <div class="grid-50 tablet-grid-45 mobile-grid-65 product-description">
                        <h3 class="product-title subheader-font" umi:field-name="h1" umi:empty="Заголовок">
                            <a href="{@link}" class="dark-color active-hover">
                                <strong>
                                    <xsl:value-of select="$page-info//property[@name = 'h1']/value" />
                                </strong>
                            </a>
                        </h3>

                        <a href="{$parent-p//page/@link}" class="product-category middle-color dark-hover parent-cont">
                            <xsl:value-of select="$parent-p//page/name" />
                        </a>

                        <xsl:if test="$page-info//property[@name = 'description_short']/value">
                            <div class="dark-color hide-on-mobile">
                                <xsl:value-of select="$page-info//property[@name = 'description_short']/value" />
                            </div>
                        </xsl:if>
                        <div  umi:field-name="description_short" umi:empty="Краткое описание" />
                    </div>

                    <div class="grid-35 tablet-grid-35 hide-on-mobile product-actions">
                        <div class="product-price active-color price-cont">
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
                            <a href="/emarket/basket/put/element/{@id}" class="button-dual-left middle-gradient dark-gradient-hover">Добавить <i class="icon-shopping-cart"></i></a>
                            <a class="button-dual-right middle-gradient dark-gradient-hover">
                                <i class="icon-angle-down"></i>
                            </a>
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
</xsl:stylesheet>