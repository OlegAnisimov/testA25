<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/result[@module = 'catalog' and @method = 'object']">
            <xsl:variable name="price" select="document(concat('udata://emarket/price/', $document-page-id))/udata" />
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">

                    <div class="sidebar-shadow push-25"></div>

                    <!-- Sidebar  -->
                    <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                        <!-- Sidebar submenu box -->
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <xsl:apply-templates select="document(concat('udata://catalog/getCategoryList/0/', $cat-srch-id, '/100/1'))/udata" mode="catalog-sidebar-list"/>
                        </div><!-- END Sidebar submenu box -->

                    </div><!-- END Sidebar  -->

                    <!-- Content  -->
                    <div class="content-with-sidebar grid-75">
                        <div class="product-detail-shadow">

                            <!-- Product detail informations  -->
                            <div class="product-detail cream-gradient grid-container">
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
                                        <xsl:apply-templates select=".//property[@name = 'osobennost_tovara']" mode="merch-sticker" />
                                    </xsl:otherwise>
                                </xsl:choose>

                                <!-- Product gallery  -->
                                <xsl:if test=".//property[@name = 'photo']/value">
                                    <div class="product-images grid-40 tablet-grid-40 juicy-wrapper">
                                        <ul id="product-gallery" class="juicy-slider middle-border">
                                            <li>
                                                <a href="{.//property[@name = 'photo']/value}" class="fancybox" target="_blank" rel="product-images">
                                                    <img class="juicy-bg" src="{document(concat('udata://system/makeThumbnailFull/(', .//property[@name = 'photo']/value/@path, ')/250/250'))//src}" data-thumb="{document(concat('udata://system/makeThumbnailFull/(', .//property[@name = 'photo']/value/@path, ')/50/50'))//src}" alt="" />
                                                </a>
                                            </li>
                                            <xsl:apply-templates select=".//property[@name = 'photos']/value" mode="product-img-slider" />
                                        </ul>
                                        <div class="juicy-slider-nav juicy-thumbs middle-border dark-border-hover active-border-selected" data-type="thumbs"></div>
                                    </div><!-- END Product gallery  -->
                                </xsl:if>


                                <!-- Product description  -->
                                <div class="product-info grid-55 tablet-grid-55">
                                    <h1 class="header-font dark-color" umi:element-id="{$document-page-id}" umi:field-name="h1" umi:empty="Заголовок">
                                        <xsl:value-of select=".//property[@name = 'h1']/value" />
                                    </h1>

                                    <div class="clearfix">
                                        <div class="product-social grid-100 tablet-grid-100 hide-on-mobile">
                                            <xsl:value-of select="$site-info//property[@name = 'product_share']/value" disable-output-escaping="yes" />
                                        </div>
                                    </div>

                                    <div class="product-perex" umi:element-id="{$document-page-id}" umi:field-name="description" umi:empty="Описание">
                                        <xsl:value-of select=".//property[@name = 'description']/value" disable-output-escaping="yes" />
                                    </div>

                                    <div class="product-meta-price clearfix">
                                        <xsl:apply-templates select=".//group[@name = 'svojstva_tovara']" mode="product-props-table">
                                            <xsl:with-param name="store" select=".//property[@name = 'common_quantity']/value" />
                                        </xsl:apply-templates>
                                        <div class="product-price active-color grid-45">
                                            <xsl:if test="$price/price/original">
                                                <del class="middle-color">
                                                    <xsl:value-of select="concat($price/price/original, ' ', $price/price/@suffix)" />
                                                </del>
                                            </xsl:if>
                                            <strong>
                                                <xsl:value-of select="concat($price/price/actual, ' ', $price/price/@suffix)" />
                                            </strong>
                                        </div>
                                    </div>
                               
                                    <!-- Product options  -->
                                    <form action="/emarket/basket/put/element/{$document-page-id}" method="POST" id="catalog-obj-form">
                                        <xsl:apply-templates select="//group[@name='catalog_option_props']" mode="table_options" />
                                        <div class="product-options clearfix">
                                            <label for="product-quantity" class="hide-on-mobile">Количество</label>
                                            <input type="text" id="product-quantity" name="amount" class="product-quantity text-input dark-color light-bg" data-val="1" value="1" />
                                            <div class="button-dual light-color transition-all">
                                                <button type="submit" class="button-dual-left middle-gradient dark-gradient-hover sbmt-but">
                                                    <xsl:text>Добавить </xsl:text>
                                                    <i class="icon-shopping-cart"></i>
                                                </button>
                                                <a class="button-dual-right middle-gradient dark-gradient-hover">
                                                    <i class="icon-angle-down"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form><!-- END Product actions  -->

                                </div><!-- END Product description  -->

                            </div><!-- END Product detail informations  -->

                            <!-- END Product detail tabs  -->
                            <div class="product-detail-tabs grid-100 light-bg">
                                <!--  Page tabs   -->
                                <div class="page-tabs a25_magaz-tabs">
                                    <xsl:apply-templates select=".//property[@name = 'description_large']" mode="product-tabs-header" />
                                    <xsl:apply-templates select=".//property[@name = 'recommended_items']" mode="product-tabs-header" />
                                    <xsl:apply-templates select=".//property[@name = 'svyazannye_tovary']" mode="product-tabs-header">
                                        <xsl:with-param name="head" select="'zagolovok_svyazannyh_tovarov'" />
                                    </xsl:apply-templates>
                                    <xsl:apply-templates select=".//property[@name = 'pohozhie_tovary']" mode="product-tabs-header">
                                        <xsl:with-param name="head" select="'zagolovok_pohozhih_tovarov'" />
                                    </xsl:apply-templates>
                                </div> <!--  END Page tabs   -->

                                <div class="page-tabs-holder">
                                    <xsl:apply-templates select=".//property[@name = 'description_large']" mode="product-tabs-content" />
                                    <xsl:apply-templates select=".//property[@name = 'recommended_items']" mode="product-tabs-content" />
                                    <xsl:apply-templates select=".//property[@name = 'svyazannye_tovary']" mode="product-tabs-content" />
                                    <xsl:apply-templates select=".//property[@name = 'pohozhie_tovary']" mode="product-tabs-content" />
                                </div>
                            </div><!-- END Product detail tabs  -->
                            <div>
                                <xsl:if test="not(.//property[@name = 'description_large']/value)">
                                    <div umi:field-name="description_large" umi:empty="Подробное описание" />
                                </xsl:if>
                                <div umi:element-id="{$site-info-id}" umi:field-name="zagolovok_svyazannyh_tovarov" umi:empty="Заголовок связанных товаров" />
                                <div umi:element-id="{$document-page-id}" umi:field-name="svyazannye_tovary" umi:empty="Связанные товары" />
                                <div umi:element-id="{$site-info-id}" umi:field-name="zagolovok_pohozhih_tovarov" umi:empty="Заголовок похожих товаров" />
                                <div umi:element-id="{$document-page-id}" umi:field-name="pohozhie_tovary" umi:empty="Похожие товары" />
                            </div>
                        </div>
                    </div><!-- END Content  -->
                </div> <!-- END Page block  -->
            </section>
	</xsl:template>

        <xsl:template match="property" mode="product-tabs-header" />
        <xsl:template match="property[value]" mode="product-tabs-header">
            <xsl:param name="head" />
            <h2 class="header-font">
                <a class="middle-color active-hover light-bg transition-color" href="#tab-{@name}">
                    <span class="hide-on-mobile">
                        <xsl:choose>
                            <xsl:when test="$head and $site-info//property[@name = $head]/value">
                                <xsl:value-of select="$site-info//property[@name = $head]/value" />
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="title" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </span>
                    <i class="icon-align-left hide-on-desktop hide-on-tablet"></i>
                </a>
            </h2>
        </xsl:template>

        <xsl:template match="property" mode="product-tabs-content" />
        <xsl:template match="property[value]" mode="product-tabs-content">
            <div id="tab-{@name}" umi:field-name="{@name}" umi:empty="{title}" umi:element-id="{$document-page-id}">
                <xsl:attribute name="class">
                    <xsl:text>page-tab grid-100</xsl:text>
                    <xsl:if test="@name != 'description_large'">
                        <xsl:text> clearfix</xsl:text>
                    </xsl:if>
                </xsl:attribute>
                <xsl:apply-templates select="value" mode="product-tabs-content"/>
            </div>
        </xsl:template>

        <xsl:template match="value" mode="product-tabs-content">
            <xsl:value-of select="node()" disable-output-escaping="yes" />
        </xsl:template>

        <xsl:template match="value[page]" mode="product-tabs-content">
            <xsl:apply-templates select="page" mode="product-tabs-content" />
        </xsl:template>

        <xsl:template match="page" mode="product-tabs-content">
            <xsl:variable name="page-info" select="document(concat('upage://', @id))/udata" />
            <xsl:variable name="price" select="document(concat('udata://emarket/price/', @id))/udata" />
            <xsl:variable name="parent-p" select="document(concat('upage://', $page-info//page/@parentId))/udata" />
            <!--  Product  -->
            <div class="grid-33 tablet-grid-33">
                <div class="product-box light-bg transition-all">
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

                    <a class="product-img" href="{@link}">
                        <span>
                            <xsl:choose>
                                <xsl:when test="$page-info//property[@name = 'photo']/value">
                                    <img class="juicy-bg" src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'photo']/value/@path, ')/150/170'))//src}" alt="" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <img src="/templates/a25_magaz/mokup/images/photos/img-product1.jpg" alt="" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </span>
                    </a>

                    <div class="product-info light-bg middle-border">
                        <h3 class="product-title subheader-font">
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
                                <strong class="middle-border">
                                    <xsl:value-of select="concat($price/price/actual, ' ', $price/price/@suffix)" />
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--  END Product   -->
        </xsl:template>

        <xsl:template match="value" mode="product-img-slider">
            <li>
                <a href="{node()}" class="fancybox" target="_blank" rel="product-images">
                    <img class="juicy-bg" src="{document(concat('udata://system/makeThumbnailFull/(', @path, ')/250/250'))//src}" data-thumb="{document(concat('udata://system/makeThumbnailFull/(', @path, ')/50/50'))//src}" alt="" />
                </a>
            </li>
        </xsl:template>

        <xsl:template match="group" mode="product-props-table">
            <xsl:param name="store" />
            <div class="product-meta middle-color grid-55">
                <table>
                    <xsl:apply-templates select="property" mode="product-props-table" />
                    <tr>
                        <td>Наличие:</td>
                        <td class="active-color">
                            <xsl:choose>
                                <xsl:when test="$store &gt; 0">
                                    <xsl:text>В наличии</xsl:text>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:text>Нет в наличии</xsl:text>
                                </xsl:otherwise>
                            </xsl:choose>
                        </td>
                    </tr>
                </table>
            </div>
        </xsl:template>

        <xsl:template match="property" mode="product-props-table">
            <tr>
                <td><xsl:value-of select="title" />:</td>
                <td><xsl:value-of select="value/item/@name" /></td>
            </tr>
        </xsl:template>











	<xsl:template match="property[@name = '&property-description;']">
		<div class="descr" itemprop="description">
			<h4>
				<xsl:text>&item-description;:</xsl:text>
			</h4>

			<div umi:element-id="{../../../@id}" umi:field-name="{@name}" umi:empty="&item-description;">
				<xsl:value-of select="value" disable-output-escaping="yes" />
			</div>
		</div>
	</xsl:template>

	<xsl:template match="property[@name = '&property-description;' and value = '']">
		<div class="descr" itemprop="description">
			<div umi:element-id="{../../../@id}" umi:field-name="{@name}" umi:empty="&item-description;">

			</div>
		</div>
	</xsl:template>

	<xsl:template match="group" mode="table">
		<table class="object">
			<thead>
				<tr>
					<th colspan="2">
						<xsl:value-of select="concat(title, ':')" />
					</th>
				</tr>
			</thead>
			<tbody umi:element-id="{../../@id}">
				<xsl:apply-templates select="property" mode="table" />
			</tbody>
		</table>
	</xsl:template>

	<xsl:template match="property" mode="table">
		<tr>
			<xsl:apply-templates select="title" mode="table"/>
			<xsl:apply-templates select="value" mode="table"/>
		</tr>
	</xsl:template>
	<xsl:template match="property/title" mode="table">
		<td>
			<span>
				<xsl:apply-templates select="document(concat('utype://', ../../../../@type-id, '.', ../../@name))/udata/group/field[@name = ../@name]/tip" mode="tip" />
				<xsl:value-of select="." />
			</span>
		</td>
	</xsl:template>
	<xsl:template match="property/value" mode="table">
		<td umi:field-name="{../@name}">
			<xsl:apply-templates select=".." />
		</td>
	</xsl:template>
	<xsl:template match="property[@type='symlink']/value" mode="table">
		<td umi:field-name="{../@name}" umi:type="catalog::object">
			<xsl:apply-templates select=".." />
		</td>
	</xsl:template>
	<xsl:template match="property[@type='wysiwyg']/value" mode="table">
		<td umi:field-name="{../@name}">
			<xsl:value-of select="." disable-output-escaping="yes" />
		</td>
	</xsl:template>
	<xsl:template match="group" mode="div">
		<div class="item_properties">
			<div>
				<xsl:value-of select="concat(title, ':')" />
			</div>
			<xsl:apply-templates select="property" mode="div" />
		</div>
	</xsl:template>

	<xsl:template match="property" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:field-name="{@name}">
			<xsl:apply-templates select="." />
		</span>
		<xsl:text>; </xsl:text>
	</xsl:template>

	<xsl:template match="property[last()]" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:field-name="{@name}">
			<xsl:apply-templates select="." />
		</span>
		<xsl:text>. </xsl:text>
	</xsl:template>

	<xsl:template match="property[@name = 'udachno_sochetaetsya_s']" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:type="catalog::object" umi:field-name="{@name}">
			<xsl:apply-templates select="value/page" mode="div" />
		</span>
		<xsl:text>; </xsl:text>
	</xsl:template>

	<xsl:template match="property[@name = 'udachno_sochetaetsya_s' and last()]" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:type="catalog::object" umi:field-name="{@name}">
			<xsl:apply-templates select="value/page" mode="div" />
		</span>
		<xsl:text>. </xsl:text>
	</xsl:template>

	<xsl:template match="page" mode="div">
		<a href="{@link}">
			<xsl:value-of select="name" />
		</a>
		<xsl:text>, </xsl:text>
	</xsl:template>

	<xsl:template match="page[last()]" mode="div">
		<a href="{@link}">
			<xsl:value-of select="name" />
		</a>
	</xsl:template>

	<xsl:template match="group" mode="table_options">
		<xsl:if test="count(//option) &gt; 0">
			<!--<h4 style="margin-top:15px;"><xsl:value-of select="concat(title, ':')" /></h4>-->
                        <h4 style="margin-top:15px;"></h4>
			<xsl:apply-templates select="property" mode="table_options" />
		</xsl:if>
	</xsl:template>

	<xsl:template match="property" mode="table_options">
		<table class="object" id="option_table_options">
<!--			<thead>
				<tr>
					<th colspan="3">
						<xsl:value-of select="concat(title, ':')" />
					</th>
				</tr>
			</thead>-->
			<tbody>
				<xsl:apply-templates select="value/option" mode="table_options" />
			</tbody>
		</table>
	</xsl:template>

	<xsl:template match="option" mode="table_options">
		<tr>
			<td style="width:20px;">
				<input type="radio" name="options[{../../@name}]" value="{object/@id}" data-float="{@float}">
					<xsl:if test="position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
			</td>
			<td>
				<xsl:value-of select="object/@name" />
			</td>
<!--			<td align="right">
				<xsl:value-of select="@float" />
			</td>-->
		</tr>
	</xsl:template>

	<xsl:template match="tip" mode="tip">
		<xsl:attribute name="title">
			<xsl:apply-templates />
		</xsl:attribute>
		<xsl:attribute name="style">
			<xsl:text>border-bottom:1px dashed; cursor:help;</xsl:text>
		</xsl:attribute>
	</xsl:template>

	<xsl:template match="value" mode="photos">
		<a class="fancybox-group" href="{text()}">
			<xsl:call-template name="catalog-thumbnail">
				<xsl:with-param name="element-id" select="$document-page-id" />
				<xsl:with-param name="source" select="text()" />
				<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
				<xsl:with-param name="field-name" select="../@name" />
				<xsl:with-param name="width" select="90" />
			</xsl:call-template>
		</a>
	</xsl:template>

</xsl:stylesheet>