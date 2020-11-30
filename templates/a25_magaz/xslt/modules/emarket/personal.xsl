<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/result[@module='emarket'][@method = 'personal']">
            <xsl:choose>
                <xsl:when test="$user-type = 'guest'">
                    <xsl:apply-templates select="document('udata://content/redirect/(/)/')" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:apply-templates select="document('udata://emarket/ordersList')/udata" mode="usr-ords-sets" />
                </xsl:otherwise>
            </xsl:choose>
	</xsl:template>

        <xsl:template match="udata" mode="usr-ords-sets">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="sidebar-shadow push-25"></div>

                    <!-- Sidebar  -->
                    <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                        <!-- Sidebar submenu box -->
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <nav class="submenu">
                                <ul class="expandable-menu">
                                    <li class="align-right back">
                                        <a href="#sidebar-mobile" class="dark-color active-hover click-slide">
                                            <i class="icon-chevron-right"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{$lang-prefix}/users/settings" class="dark-color active-hover">Профиль</a>
                                    </li>
                                    <li class="sidebar-divider"></li>
                                    <li>
                                        <a href="{$lang-prefix}/emarket/personal" class="dark-color active-hover selected">История заказов</a>
                                    </li>
                                    <li class="sidebar-divider"></li>
                                    <li>
                                        <a href="{$lang-prefix}/users/logout/" class="dark-color active-hover">Выход</a>
                                    </li>
                                </ul>
                            </nav>
                        </div><!-- END Sidebar submenu box -->
                    </div><!-- END Sidebar  -->

                    <!-- Content  -->
                    <div class="content-with-sidebar grid-75">
                        <div class="content-page grid-100">
                            <div class="my-profile-header middle-color clearfix">
                                <div class="my-profile-info">
                                    <xsl:variable name="user-info" select="document(concat('uobject://', $user-id))/udata" />
                                    <h1 class="active-color header-font">
                                        <span class="dark-color">Здравствуйте, </span>
                                        <xsl:value-of select="concat($user-info//property[@name='fname']/value, ' ',$user-info//property[@name='lname']/value)" />
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="with-shadow grid-100 light-bg">
                            <div class="content-page grid-100">
                                <h2 class="bigger-header with-border subheader-font">История заказов</h2>
                                <xsl:apply-templates select="items" mode="usr-ords-sets" />
                            </div>
                        </div>
                    </div><!-- END Content  -->
                </div> <!-- END Page block  -->
            </section>
        </xsl:template>

        <xsl:template match="items[item]" mode="usr-ords-sets">
            <xsl:apply-templates select="item" mode="usr-ords-sets">
                <xsl:sort select="@id" order="descending" />
            </xsl:apply-templates>
        </xsl:template>

        <xsl:template match="items" mode="usr-ords-sets">
            <p class="usr-ord-list-no">Пока нет ни одного заказа</p>
        </xsl:template>

        <xsl:template match="item" mode="usr-ords-sets">
            <xsl:variable name="order-info" select="document(concat('uobject://', @id))/udata" />
            <xsl:variable name="status_code" select="document(concat('uobject://', $order-info//property[@name='status_id']/value/item/@id))//property[@name='codename']/value" />
            <xsl:if test="$order-info//group[@name = 'order_props']/property[@name='order_items']/value/item">
                <h2 class="dark-color subheader-font">
                    <xsl:text>Заказ № (</xsl:text>
                    <xsl:value-of select="$order-info//property[@name='number']/value" />
                    <xsl:text>) от </xsl:text>
                    <xsl:value-of select="document(concat('udata://system/convertDate/', $order-info//property[@name='order_date']/value/@unix-timestamp, '/(d.m.Y)'))" />
                    <xsl:text> в </xsl:text>
                    <xsl:value-of select="document(concat('udata://system/convertDate/', $order-info//property[@name='order_date']/value/@unix-timestamp, '/(H:i)'))" />
                </h2>
                <p>
                    <strong>Статус заказа: </strong>
                    <xsl:value-of select="$order-info//property[@name='status_id']/value/item/@name" />
                </p>
                <p>
                    <strong>Доставка: </strong>
                    <xsl:value-of select="$order-info//property[@name='delivery_id']/value/item/@name" />
                </p>
                <xsl:if test="$order-info//property[@name='delivery_address']/value/item">
                    <p>
                        <strong>Адрес доставки: </strong>
                        <xsl:apply-templates select="document(concat('uobject://', $order-info//property[@name='delivery_address']/value/item/@id))//property" mode="delivery-address" />
                    </p>
                </xsl:if>
                <div class="checkout-summary">
                    <table class="usr-ord-list-table">
                        <tr class="middle-color">
                            <th colspan="2" class="summary-name">Товар</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th class="active-color">Стоимость</th>
                        </tr>
                        <xsl:apply-templates select="$order-info//group[@name = 'order_props']/property[@name='order_items']/value/item" mode="summary-items-per" />
                    </table>
                </div>
                <div class="checkout-summary summary-mobile">
                    <table>
                        <xsl:apply-templates select="items/item" mode="summary-items-mobile-per" />
                    </table>
                </div>
                <p>
                    <strong>Стоимость доставки: </strong>
                    <xsl:value-of select="$order-info//property[@name='delivery_price']/value" />
                    <xsl:text> руб.</xsl:text>
                </p>
                <p>
                    <strong>Сумма заказа: </strong>
                    <xsl:value-of select="$order-info//property[@name='total_price']/value" />
                    <xsl:text> руб.</xsl:text>
                </p>
                <xsl:if test="$status_code = 'payment'">
                    <p>
                        <strong>Оплатить заказ:</strong>
                    </p>
                    <div>
                        <form action="/emarket/pay_from_lk/" method="post" id="lk-payment-form">
                            <input type="hidden" name="order_id" value="{@id}" />
                            <div style="display:none"><xsl:copy-of select="document(concat('uobject://',@id))/udata" /></div>
                            <xsl:apply-templates select="document('udata://emarket/get_payments')//item" mode="purchase-payment-method">
                                <xsl:with-param name="is_pers" select="'1'" />
                            </xsl:apply-templates>
                            <br/>
                            <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover no-conf-pay">
                                Оплатить
                                <span>
                                    <i class="icon-angle-right"></i>
                                </span>
                            </button>
                            <br/>
                            <br/>
                        </form>
                    </div>
                </xsl:if>
                <xsl:if test="position() != last()">
                    <hr />
                </xsl:if>
            </xsl:if>
        </xsl:template>

        <xsl:template match="item" mode="summary-items-mobile">
            <xsl:variable name="item-obj" select="document(concat('uobject://', @id))/udata" />
            <xsl:variable name="item-info" select="document(concat('upage://', $item-obj//property[@name='item_link']/value/page/@id))/udata" />
            <tr>
                <td>
                    <xsl:choose>
                        <xsl:when test="$item-info//property[@name='photo']/value">
                            <img src="{document(concat('udata://system/makeThumbnail/(', $item-info//property[@name='photo']/value/@path, ')/40/40///1'))//src}" alt="" />
                        </xsl:when>
                        <xsl:otherwise>
                            <img src="/templates/a25_magaz/mokup/images/photos/visited-item-2.jpg" alt="" />
                        </xsl:otherwise>
                    </xsl:choose>
                </td>
                <td class="summary-name">
                    <a href="{$item-obj//property[@name='item_link']/value/page/@link}">
                        <xsl:value-of select="@name" />
                    </a>
                </td>
            </tr>
            <tr>
                <td class="summary-price">
                    <strong class="active-color">
                        <xsl:value-of select="$item-obj//property[@name='item_total_price']/value" />
                        <xsl:text> руб.</xsl:text>
                    </strong>
                </td>
                <td class="middle-color">
                    <xsl:value-of select="$item-obj//property[@name='item_amount']/value" />
                    <xsl:text> x </xsl:text>
                    <xsl:value-of select="$item-obj//property[@name='item_price']/value" />
                    <xsl:text> руб.</xsl:text>
                </td>
            </tr>
        </xsl:template>

        <xsl:template match="item" mode="summary-items-per">
            <xsl:variable name="item-obj" select="document(concat('uobject://', @id))/udata" />
            <xsl:variable name="item-info" select="document(concat('upage://', $item-obj//property[@name='item_link']/value/page/@id))/udata" />
            <tr>
                <td>
                    <xsl:choose>
                        <xsl:when test="$item-info//property[@name='photo']/value">
                            <img src="{document(concat('udata://system/makeThumbnail/(', $item-info//property[@name='photo']/value/@path, ')/40/40///1'))//src}" alt="" />
                        </xsl:when>
                        <xsl:otherwise>
                            <img src="/templates/a25_magaz/mokup/images/photos/visited-item-2.jpg" alt="" />
                        </xsl:otherwise>
                    </xsl:choose>
                </td>
                <td class="summary-name">
                    <a href="{$item-obj//property[@name='item_link']/value/page/@link}">
                        <xsl:value-of select="@name" />
                    </a>
                </td>
                <td>
                    <xsl:value-of select="$item-obj//property[@name='item_amount']/value" />
                </td>
                <td class="middle-color">
                    <xsl:value-of select="$item-obj//property[@name='item_price']/value" />
                    <xsl:text> руб.</xsl:text>
                </td>
                <td class="active-color">
                    <strong>
                        <xsl:value-of select="$item-obj//property[@name='item_total_price']/value" />
                        <xsl:text> руб.</xsl:text>
                    </strong>
                </td>
            </tr>
        </xsl:template>
</xsl:stylesheet>