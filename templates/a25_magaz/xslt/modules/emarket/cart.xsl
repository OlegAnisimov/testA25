<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="result[@method = 'cart']">
<!--		<xsl:if test="not(contains($purchase-method, 'purchasing_one_step'))">
			<xsl:apply-templates select="//steps" />
		</xsl:if>-->
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                
                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container cartp-content">
                    <xsl:apply-templates select="document('udata://system/listErrorMessages')//item" mode="form-errs-cart" />
                    <xsl:apply-templates select="document('udata://emarket/cart')/udata" mode="ajax_reload_cart" />
                </div>
            </section>
	</xsl:template>

        <xsl:template match="item" mode="form-errs-cart">
            <p style="padding-left: 12px;padding-top: 10px;color: red;margin: 0;">
                <xsl:value-of select="node()" />
            </p>
        </xsl:template>
        
	<xsl:template match="udata" mode="ajax_reload_cart">
            <!-- Content  -->
            <div class="content-holder grid-100">
                <div class="cart-product-list well-shadow">
                    <p class="cp-empty-cart">Корзина пуста</p>
                </div>
            </div>
	</xsl:template>

	<xsl:template match="udata[count(items/item) &gt; 0]" mode="ajax_reload_cart">
            <!-- Content  -->
            <div class="content-holder grid-100">
                <div class="cart-header well-shadow well-table light-bg margin-bottom hide-on-mobile">
                    <div class="well-box-middle grid-60 tablet-grid-60">Наименование</div>
                    <div class="well-box-middle align-center grid-10 tablet-grid-10">Кол-во</div>
                    <div class="well-box-middle align-center grid-15 tablet-grid-15">Цена</div>
                    <div class="well-box-middle align-center last grid-15 tablet-grid-15 active-color">Стоимость</div>
                </div>

                <div class="cart-product-list well-shadow">
                    <xsl:apply-templates select="items/item" mode="cartp-items" />
                </div>
            </div><!-- END Content  -->
            
            <xsl:apply-templates select="document('udata://emarket/purchasing_one_step/')/udata" mode="pos-cont"/>
            
<!--            <div class="grid-100 grid-parent margin-bottom clearfix">
                <div class="grid-45 tablet-grid-45">
                    <div class="well-shadow well-box last light-bg align-right">
                        <dl class="cart-sub-total middle-color clearfix">
                            <dt class="uppercase">Стоимость товаров:</dt>
                            <dd>
                                <span class="cartp-total-pr">
                                    <xsl:choose>
                                        <xsl:when test="summary/price/original">
                                            <xsl:value-of select="summary/price/original" />
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:value-of select="summary/price/actual" />
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </span>
                                <xsl:text> руб.</xsl:text>
                            </dd>
                            <dt class="uppercase">Скидка на заказ:</dt>
                            <dd>
                                <span class="cartp-total-disc">
                                    <xsl:value-of select="discount_value" />
                                </span>
                                <xsl:text> руб.</xsl:text>
                            </dd>
                        </dl>

                        <dl class="cart-total clearfix">
                            <dt class="uppercase dark-color">Итого:</dt>
                            <dd class="active-color">
                                <span class="cartp-total-pr-d">
                                    <xsl:value-of select="summary/price/actual" />
                                </span>
                                <xsl:text> руб.</xsl:text>
                            </dd>
                        </dl>

                        <a href="/emarket/purchasing_one_step/" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                            Перейти к оплате <span>
                                <i class="icon-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>-->
	</xsl:template>

        <xsl:template match="item" mode="cartp-items">
            <xsl:variable name="page-info" select="document(concat('upage://', page/@id))/udata" />
            <div class="cart-prod-custom">
                <!-- Cart product  -->
                <div class="cart-product well-table light-bg" id="item-{@id}">
                    <div class="well-box-middle cart-product-image align-center grid-10 tablet-grid-10">
                        <a href="{page/@link}" class="cartp-img-wrap" title="{@name}">
                            <xsl:choose>
                                <xsl:when test="$page-info//property[@name='photo']/value">
                                    <xsl:variable name="photo" select="document(concat('udata://system/makeThumbnail/(', $page-info//property[@name='photo']/value/@path, ')/51/68///1'))/udata" />
                                    <img src="{$photo//src}" alt="" style="width: {$photo//width}px; height: {$photo//height}px;" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <img src="/templates/a25_magaz/mokup/images/photos/img-cart-dummy.jpg" alt="" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </a>
                    </div>
                    <div class="well-box-middle well-border-gradient grid-50 tablet-grid-50">
                        <div class="cart-product-info">
                            <div class="cart-product-title">
                                <a href="{@link}" title="{@name}" class="header-font dark-color active-hover">
                                    <strong>
                                        <xsl:value-of select="$page-info//property[@name='h1']/value" />
                                    </strong>
                                </a>
                                <xsl:if test="options/option">
                                    <div class="option_cart_block"><xsl:value-of select="options/option/@name" /></div>
                                </xsl:if>
                                <xsl:if test="$page-info//property[@name='description_short']/value">
                                    <a href="{@link}" title="{@name}" class="cart-product-category middle-color dark-hover">
                                        <xsl:value-of select="$page-info//property[@name='description_short']/value" />
                                    </a>
                                </xsl:if>
                            </div>
                        </div>
                    </div>
                    <div class="well-box-middle well-border-gradient align-center grid-10 tablet-grid-10">
                        <input type="number" name="amount" class="text-input product-quantity dark-color light-bg cartp-ch-amount" value="{amount}" data-val="{amount}" min="1" data-id="{page/@id}" data-item="{@id}"/>
                    </div>
                    <div class="well-box-middle well-border-gradient align-center grid-15 tablet-grid-15 middle-color">
                        <strong>
                            <span class="cartp-item-pr">
                                <xsl:value-of select="price/actual" />
                            </span>
                            <xsl:text> руб.</xsl:text>
                        </strong>
                    </div>
                    <div class="well-box-middle align-center last grid-15 tablet-grid-15 active-color">
                        <strong>
                            <span class="cartp-item-pr-t">
                                <xsl:value-of select="total-price/actual" />
                            </span>
                            <xsl:text> руб.</xsl:text>
                        </strong>
                    </div>


                </div><!-- END Cart product  -->
                <a class="cart-product-remove circle-button dark-bg active-bg-hover hide-on-desktop cartp-basket-remove" href="/emarket/basket/remove/item/{@id}" data-id="{@id}">
                    <span class="cancel"></span>
                </a>
            </div>


        </xsl:template>

	<xsl:template match="udata[@method = 'cart']//item">
		<tr class="cart_item_{@id}">
			<td class="name">
				<xsl:call-template name="catalog-thumbnail">
					<xsl:with-param name="element-id" select="page/@id" />
					<xsl:with-param name="field-name">photo</xsl:with-param>
					<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
					<xsl:with-param name="width">77</xsl:with-param>
					<xsl:with-param name="height">55</xsl:with-param>
					<xsl:with-param name="align">middle</xsl:with-param>
				</xsl:call-template>
				<a href="{$lang-prefix}{page/@link}">	<xsl:value-of select="@name" />	</a>
			</td>
			<td>
				<span><xsl:value-of select="price/actual | price/original" /></span>
				<span class="x"> x </span>
				<input type="text" value="{amount}" class="amount" />
				<input type="hidden" value="{amount}" />
				<span class="change-amount">
					<img class="top" src="/templates/a25_magaz/images/amount-top.png"/>
					<img class="bottom" src="/templates/a25_magaz/images/amount-bottom.png"/>
				</span>
			</td>
			<td>
				<span class="cart_item_discount_{@id}">
					<xsl:choose>
						<xsl:when test="discount">
							<xsl:value-of select="discount/amount" />
						</xsl:when>
						<xsl:otherwise>
							0
						</xsl:otherwise>
					</xsl:choose>
				</span>
			</td>
			<td>
				<span class="cart_item_price_{@id} size2">
					<xsl:value-of select="total-price/actual" />
				</span>
			</td>
			<td>
				<a href="{$lang-prefix}/emarket/basket/remove/item/{@id}/" id="del_basket_{@id}" class="del" />
			</td>
		</tr>
		<xsl:apply-templates select="document(concat('upage://', page/@id, '.udachno_sochetaetsya_s'))/udata" mode="related-goods" />
	</xsl:template>

	<xsl:template match="udata" mode="related-goods" />
	<xsl:template match="udata[property]" mode="related-goods">
		<tr class="related-goods">
			<td colspan="5">
				<div class="title">
					<xsl:value-of select="//title" /><br />
				</div>
				<xsl:apply-templates select="//value/page" mode="related-goods"/>
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="page" mode="related-goods" />
	<xsl:template match="page[position() &lt; 3]" mode="related-goods">
		<xsl:variable name="is_options">
			<xsl:apply-templates select="document(concat('upage://', @id))/udata/page/properties" mode="is_options" />
		</xsl:variable>

		<div class="item">
			<a href="{$lang-prefix}{@link}"> <xsl:value-of select="name" /> </a>
		</div>
		<div class="buy">
			<span>
			<xsl:value-of select="$currency-prefix" />
			<xsl:text> </xsl:text>
			<xsl:value-of select="document(concat('upage://', @id, '.price'))//value" />
			<xsl:text> </xsl:text>
			<xsl:value-of select="$currency-suffix" />
			&#160;&#160;&#160;
			<a href="{$lang-prefix}/emarket/basket/put/element/{@id}/" class="basket_list options_{$is_options}" id="add_basket_{@id}">&basket-add-short;</a>
			</span>
		</div>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']/summary">
		<xsl:if test="price/bonus!=''">
			<div class="info">
				<xsl:text>&order-bonus;: </xsl:text>
				<span class="cart_discount">
					<xsl:value-of select="$currency-prefix" />
					<xsl:text> </xsl:text>
					<xsl:value-of select="price/bonus" />
					<xsl:text> </xsl:text>
					<xsl:value-of select="$currency-suffix" />
				</span>
			</div>
		</xsl:if>
		<div class="info">
			<xsl:text>&order-discount;: </xsl:text>
			<span class="cart_discount">
				<xsl:value-of select="$currency-prefix" />
				<xsl:text> </xsl:text>
				<xsl:choose>
					<xsl:when test="price/discount!=''">
						<xsl:value-of select="price/discount" />
					</xsl:when>
					<xsl:otherwise>
						0
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text> </xsl:text>
				<xsl:value-of select="$currency-suffix" />
			</span>
		</div>
		<xsl:apply-templates select="price/delivery[.!='']" mode="cart" />
		<div class="size2 tfoot">
			<xsl:text>&summary-price;: </xsl:text>
			<xsl:value-of select="$currency-prefix" />
			<xsl:text> </xsl:text>
			<span class="cart_summary size3">
				<xsl:apply-templates select="price/actual" />
			</span>
			<xsl:text> </xsl:text>
			<xsl:value-of select="$currency-suffix" />
		</div>
	</xsl:template>

	<xsl:template match="delivery[.!='']" mode="cart">
		<div class="info">
			<xsl:text>&delivery;: </xsl:text>
			<xsl:value-of select="$currency-prefix" />
			<xsl:text> </xsl:text>
			<xsl:value-of select="." />
			<xsl:text> </xsl:text>
			<xsl:value-of select="$currency-suffix" />
		</div>
	</xsl:template>

        <xsl:template match="udata" mode="header-cart-mobile">
            <li class="main-menu-cart active-color light-hover">
                <a href="/emarket/cart/" class="main-menu-item header-cart-preview-mobile">
                    <xsl:choose>
                        <xsl:when test="summary/amount &gt; 0">
                            <i class="icon-shopping-cart">&#160;</i>
                            <xsl:value-of select="summary/amount" />
                            &#160;|&#160;
                            <xsl:value-of select="concat(summary/price/actual, ' ', summary/price/@suffix)" />
                        </xsl:when>
                        <xsl:otherwise>
                            <i class="icon-shopping-cart">&#160;</i>
                            <xsl:text>Корзина пуста</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </a>
            </li>
        </xsl:template>

        <xsl:template match="udata" mode="header-cart">
            <div class="header-cart" id="header-cart">
                <a href="/emarket/cart/" class="text-input input-round dark-color light-bg header-cart-preview">
                    <xsl:choose>
                        <xsl:when test="summary/amount &gt; 0">
                            <strong class="active-color">
                                <i class="icon-shopping-cart">&#160;</i>
                                <xsl:value-of select="summary/amount" />
                            </strong>
                            <xsl:text> Товар(ов)</xsl:text>&#160;|&#160;
                            <strong class="active-color">
                                <xsl:value-of select="concat(summary/price/actual, ' ', summary/price/@suffix)" />
                            </strong>
                        </xsl:when>
                        <xsl:otherwise>
                            <strong class="active-color">
                                <i class="icon-shopping-cart">&#160;</i>
                            </strong>
                            <xsl:text>Корзина пуста</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </a>

                <!--    Quick cart    -->
                <ul style="display:none;">
                    <xsl:attribute name="class">
                        <xsl:text>popup-box header-quick-cart cream-bg mp-cart-container</xsl:text>
                        <xsl:if test="summary/amount = 0">
                            <xsl:text> custom-hid</xsl:text>
                        </xsl:if>
                    </xsl:attribute>
                    <xsl:if test="summary/amount &gt; 0">
                        <li class="arrow-top">
                            <span class="shadow cream-bg"></span>
                        </li>
                        <li class="focusor-top"></li>

                        <xsl:apply-templates select="items/item" mode="header-cart" />

                        <li class="quick-cart-total">
                            <span class="quick-cart-left dark-color">Итого</span>
                            <span class="quick-cart-right active-color">
                                <xsl:value-of select="concat(summary/price/actual, ' ', summary/price/@suffix)" />
                            </span>
                        </li>
                        <li class="list-divider"></li>

                        <li class="quick-cart-buttons">
                            <a href="/emarket/cart/" class="button-small light-color middle-gradient dark-gradient-hover">
                                <xsl:text>В корзину</xsl:text>
                            </a>
                            <a href="/emarket/purchasing_one_step/" class="button-small light-color active-gradient dark-gradient-hover">
                                <xsl:text>Купить</xsl:text>
                            </a>
                        </li>
                    </xsl:if>
                </ul><!--    END Quick cart    -->
            </div>
        </xsl:template>

        <xsl:template match="item" mode="header-cart">
            <xsl:variable name="page-info" select="document(concat('upage://', page/@id))/udata" />
            <li class="quick-cart-item light-bg-hover transition-all">
                <xsl:choose>
                    <xsl:when test="amount = 1">
                        <a href="/emarket/basket/remove/item/{@id}" class="quick-cart-remove circle-button middle-bg active-bg-hover mp-cart-action">
                            <span class="minus"></span>
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <a href="/emarket/basket/put/element/{page/@id}/?amount={amount - 1}" class="quick-cart-remove circle-button middle-bg active-bg-hover mp-cart-add">
                            <span class="minus"></span>
                        </a>
                    </xsl:otherwise>
                </xsl:choose>
                <a href="/emarket/basket/put/element/{page/@id}/?amount={amount + 1}" class="quick-cart-add circle-button middle-bg active-bg-hover mp-cart-add">
                    <span class="plus"></span>
                </a>
                <a href="{page/@link}" class="quick-cart-left dark-color">
                    <span class="quick-cart-image">
                        <xsl:choose>
                            <xsl:when test="$page-info//property[@name = 'photo']/value">
                                <img src="{document(concat('udata://system/makeThumbnailFull/(', $page-info//property[@name = 'photo']/value/@path, ')/40/40'))//src}" alt="" />
                            </xsl:when>
                            <xsl:otherwise>
                                <img src="/templates/a25_magaz/mokup/images/photos/quick-cart-item.jpg" alt="" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </span>
                    <span class="quick-cart-name">
                        <xsl:value-of select="$page-info//property[@name = 'h1']/value" />
                    </span>
                </a>
                <a href="{page/@link}" class="quick-cart-right dark-color">
                    <span class="hc-item-amount">
                        <xsl:value-of select="amount" />
                    </span>
                    <xsl:text> x </xsl:text>
                    <strong class="active-color">
                        <span class="hc-item-price">
                            <xsl:value-of select="price/actual" />
                        </span>
                        <xsl:value-of select="concat(' ', price/@suffix)" />
                    </strong>
                </a>
            </li>
            <li class="list-divider"></li>
        </xsl:template>

</xsl:stylesheet>