<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:include href="purchase/required.xsl" />
	<xsl:include href="purchase/delivery.xsl" />
	<xsl:include href="purchase/payment.xsl" />

        <xsl:template match="/result[@module = 'emarket'][@method = 'summary']">
            <xsl:variable name="ord-obj" select="document(concat('uobject://', udata/@id))/udata" />
            <xsl:choose>
                <xsl:when test="not(udata/items/item)">
                    <xsl:apply-templates select="document('udata://content/redirect/(/emarket/cart/)/')" />
                </xsl:when>
                <xsl:when test="not(udata/delivery/method)">
                    <xsl:apply-templates select="document('udata://content/redirect/(/emarket/purchase/delivery/address/)/')" />
                </xsl:when>
                <xsl:when test="not($ord-obj//property[@name='payment_id']/value/item)">
                    <xsl:apply-templates select="document('udata://content/redirect/(/emarket/purchase/payment/choose/)/')" />
                </xsl:when>
                <xsl:otherwise>
<!--                    <xsl:apply-templates select="udata" mode="summary-info">
                        <xsl:with-param name="ord" select="$ord-obj" />
                    </xsl:apply-templates>-->
                </xsl:otherwise>
            </xsl:choose>
        </xsl:template>

        <xsl:template match="udata" mode="summary-info">
            <xsl:variable name="ord" select="document(concat('uobject://', @id))/udata" />
<!--            <div class="box-table">
                <div class="box grid-33 tablet-grid-33">
                    <xsl:if test="not(.//steps/item[@name = 'Оплата']) and not(.//steps/item[@name = 'Доставка'])">
                        <xsl:attribute name="class">box grid-33 tablet-grid-33 last</xsl:attribute>
                    </xsl:if>
                    <h2 class="subheader-font bigger-header margin-bottom">Информация о покупателе</h2>
                    <ul>
                        <xsl:apply-templates select="customer//group[@name = 'short_info']/property[@name != 'login']" mode="summary-customer" />
                        <xsl:apply-templates select="customer//property[@name = 'e-mail']" mode="summary-customer" />
                    </ul>
                </div>

                <xsl:if test=".//steps/item[@name = 'Доставка']">
                    <div class="box grid-33 tablet-grid-33">
                        <xsl:if test="not(.//steps/item[@name = 'Оплата'])">
                            <xsl:attribute name="class">box grid-33 tablet-grid-33 last</xsl:attribute>
                        </xsl:if>
                        <h2 class="subheader-font bigger-header margin-bottom">Адрес доставки</h2>

                        <ul class="value-delivery_adr">
                            <li></li>
                        </ul>
                    </div>
                </xsl:if>

                <xsl:if test=".//steps/item[@name = 'Доставка'] or .//steps/item[@name = 'Оплата']">
                    <div class="box grid-33 tablet-grid-33 last">
                        <h2 class="subheader-font bigger-header margin-bottom">Оплата и доставка</h2>

                        <ul>
                            <xsl:if test=".//steps/item[@name = 'Оплата']">
                                <li>
                                    <xsl:text>Оплата: </xsl:text>
                                    <strong class="active-color value-payment_id"></strong>
                                </li>
                            </xsl:if>
                            <xsl:if test=".//steps/item[@name = 'Доставка']">
                                <li>
                                    <xsl:text>Доставка: </xsl:text>
                                    <strong class="active-color value-delivery_method"></strong>
                                </li>
                            </xsl:if>
                        </ul>
                    </div>
                </xsl:if>
            </div>-->

            <!--<hr class="hide-on-mobile" />-->

<!--            <div class="checkout-summary">
                <table>
                    <tr class="middle-color">
                        <th colspan="2" class="summary-name">Товар</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                        <th class="active-color">Стоимость</th>
                    </tr>
                    <xsl:apply-templates select="items/item" mode="summary-items" />
                </table>
            </div>

            <div class="checkout-summary summary-mobile">
                <table>
                    <xsl:apply-templates select="items/item" mode="summary-items-mobile" />
                </table>
            </div>
            <hr />-->

            <div class="grid-container grid-parent">

                <div class="checkout-message grid-100 tablet-grid-100">
                    <div class="form-input">
                        <label for="message" class="middle-color">Комментарий к заказу:</label>
                        <textarea class="textarea-input dark-color light-bg" name="kommentarij_k_zakazu" id="message"></textarea>
                    </div>
                    <hr/>
                </div>
                <div class="checkout-message grid-65 tablet-grid-65" style="color: #999999;padding-right: 10px;" umi:element-id="{$site-info-id}" umi:field-name="tekst_na_stranice_zakaza" umi:empty="Текст заказа">
                    <xsl:value-of select="$site-info//property[@name='tekst_na_stranice_zakaza']/value" disable-output-escaping="yes"/>
                </div>
                <div class="checkout-total-holder grid-35 tablet-grid-35 align-right">
                    <dl class="checkout-sub-total middle-color clearfix">
                        <dt class="uppercase">Стоимость товаров:</dt>
                        <dd>
                            <xsl:choose>
                                <xsl:when test="summary/price/original">
                                    <xsl:value-of select="summary/price/original" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="summary/price/actual" />
                                </xsl:otherwise>
                            </xsl:choose>
                            <xsl:text> руб.</xsl:text>
                        </dd>
                        <dt class="uppercase">Скидка на заказ:</dt>
                        <dd>
                            <xsl:value-of select="discount_value" />
                            <xsl:text> руб.</xsl:text>
                        </dd>
                        <xsl:if test=".//steps/item[@name = 'Доставка']">
                            <dt class="uppercase">Стоимость доставки:</dt>
                            <dd class="value-delivery_price" ></dd>
                        </xsl:if>
                    </dl>

                    <dl class="checkout-total clearfix">
                        <dt class="uppercase dark-color">Итого:</dt>
                        <dd class="active-color value-price_total" data-actual="{summary/price/actual}"></dd>
                    </dl>
                </div>
            </div>

            <hr />

            <div class="content-holder align-right">
<!--                <xsl:if test=".//steps/item[@name = 'Доставка'] or .//steps/item[@name = 'Оплата']">
                    <a href="#" class="pull-left button-normal light-color middle-gradient dark-gradient-hover goto-step" data-step="3">
                        <xsl:if test="not(.//steps/item[@name = 'Доставка']) or not(.//steps/item[@name = 'Оплата'])">
                            <xsl:attribute name="data-step">2</xsl:attribute>
                        </xsl:if>
                        <b class="hide-on-desktop hide-on-tablet">Назад</b>
                        <b class="hide-on-mobile">К предыдущему шагу</b>
                    </a>
                </xsl:if>-->

                <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover trig-subm">
                    Завершить заказ
                    <span>
                        <i class="icon-angle-right"></i>
                    </span>
                </button>
            </div>
        </xsl:template>

        <xsl:template match="item" mode="summary-items-mobile">
            <xsl:variable name="item-info" select="document(concat('upage://', page/@id))/udata" />
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
                    <strong>
                        <xsl:value-of select="$item-info//property[@name='h1']/value" />
                    </strong>
                    <small class="middle-color">
                        <xsl:value-of select="$item-info//property[@name='description_short']/value" />
                    </small>
                </td>
            </tr>
            <tr>
                <td class="summary-price">
                    <strong class="active-color">
                        <xsl:value-of select="total-price/actual" />
                        <xsl:text> руб.</xsl:text>
                    </strong>
                </td>
                <td class="middle-color">
                    <xsl:value-of select="amount" />
                    <xsl:text> x </xsl:text>
                    <xsl:value-of select="price/actual" />
                    <xsl:text> руб.</xsl:text>
                </td>
            </tr>
        </xsl:template>

        <xsl:template match="item" mode="summary-items">
            <xsl:variable name="item-info" select="document(concat('upage://', page/@id))/udata" />
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
                    <strong>
                        <xsl:value-of select="$item-info//property[@name='h1']/value" />
                    </strong>
                    <small class="middle-color">
                        <xsl:value-of select="$item-info//property[@name='description_short']/value" />
                    </small>
                </td>
                <td>
                    <xsl:value-of select="amount" />
                </td>
                <td class="middle-color">
                    <xsl:value-of select="price/actual" />
                    <xsl:text> руб.</xsl:text>
                </td>
                <td class="active-color">
                    <strong>
                        <xsl:value-of select="total-price/actual" />
                        <xsl:text> руб.</xsl:text>
                    </strong>
                </td>
            </tr>
        </xsl:template>

        <xsl:template match="property" mode="summary-customer"/>
        <xsl:template match="property[@type='string']" mode="summary-customer">
            <li>
                <xsl:value-of select="title" />
                <xsl:text>: </xsl:text>
                <xsl:value-of select="value" />
            </li>
        </xsl:template>

        <xsl:template match="property" mode="summary-adr">
            <li>
                <xsl:value-of select="title" />
                <xsl:text>: </xsl:text>
                <xsl:value-of select="value" />
            </li>
        </xsl:template>

        <xsl:template match="property[@type='relation']" mode="summary-adr">
            <li>
                <xsl:value-of select="title" />
                <xsl:text>: </xsl:text>
                <xsl:value-of select="value/item/@name" />
            </li>
        </xsl:template>

	<xsl:template match="/result[@method = 'purchase']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <!-- Content  -->
                    <div class="content-page checkout-page grid-100">
                        <xsl:apply-templates select="document('udata://emarket/purchase')" />
                    </div><!-- END Content  -->
                </div><!-- END Page block  -->
            </section>
	</xsl:template>

        <xsl:template match="steps" mode="checkout-steps">
            <div class="checkout-progress progress">
                <xsl:apply-templates select="item" mode="checkout-steps" />
            </div>
            <hr />
        </xsl:template>

        <xsl:template match="item" mode="checkout-steps">
            <xsl:if test="position() = last()">
                <div>
                    <xsl:attribute name="class">
                        <xsl:text>progress-step</xsl:text>
                        <xsl:choose>
                            <xsl:when test="@status = 'active'">
                                <xsl:text> completed-step</xsl:text>
                            </xsl:when>
                            <xsl:when test="$document-result/@method = 'summary'">
                                <xsl:text> active-color current-step</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text> middle-color</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:choose>
                        <xsl:when test="@status = 'active'">
                            <a href="/emarket/summary" class="step-outer dark-color active-hover">
                                <span class="step-inner">
                                    <span class="step-line active-bg"></span>
                                    <span class="light-color active-bg">
                                        <i class="icon-ok"></i>
                                    </span>
                                </span>
                                <xsl:text>Сводка</xsl:text>
                            </a>
                        </xsl:when>
                        <xsl:when test="$document-result/@method = 'summary'">
                            <a class="step-outer">
                                <span class="step-inner">
                                    <span class="light-color active-bg">
                                        <xsl:value-of select="position()" />
                                    </span>
                                </span>
                                <xsl:text>Сводка</xsl:text>
                            </a>
                        </xsl:when>
                        <xsl:otherwise>
                            <a class="step-outer">
                                <span class="step-inner">
                                    <span>
                                        <xsl:value-of select="position()" />
                                    </span>
                                </span>
                                <xsl:text>Сводка</xsl:text>
                            </a>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
            </xsl:if>
            <div>
                <xsl:attribute name="class">
                    <xsl:text>progress-step</xsl:text>
                    <xsl:choose>
                        <xsl:when test="@status = 'active'">
                            <xsl:text> active-color current-step</xsl:text>
                        </xsl:when>
                        <xsl:when test="@status = 'complete'">
                            <xsl:text> completed-step</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text> middle-color</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:if test="position() = last()">
                        <xsl:text> last</xsl:text>
                    </xsl:if>
                </xsl:attribute>

                <xsl:variable name="number">
                    <xsl:choose>
                        <xsl:when test="position() = last()">
                            <xsl:value-of select="position()+1" />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="position()" />
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>

                <xsl:choose>
                    <xsl:when test="@status = 'active'">
                        <a class="step-outer">
                            <span class="step-inner">
                                <span class="light-color active-bg">
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="@name" />
                        </a>
                    </xsl:when>
                    <xsl:when test="@status = 'complete'">
                        <a href="{@link}" class="step-outer dark-color active-hover">
                            <span class="step-inner">
                                <span class="step-line active-bg"></span>
                                <span class="light-color active-bg">
                                    <i class="icon-ok"></i>
                                </span>
                            </span>
                            <xsl:value-of select="@name" />
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <a class="step-outer">
                            <span class="step-inner">
                                <span>
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="@name" />
                        </a>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </xsl:template>


        <xsl:template match="steps" mode="checkout-steps-summary">
            <div class="checkout-progress progress">
                <xsl:apply-templates select="item" mode="checkout-steps-summary" />
            </div>
            <hr class="hide-on-mobile" />
        </xsl:template>

        <xsl:template match="item" mode="checkout-steps-summary">
            <div class="progress-step completed-step">
                <a href="{@link}" class="step-outer dark-color active-hover">
                    <span class="step-inner">
                        <span class="step-line active-bg"></span>
                        <span class="light-color active-bg">
                            <i class="icon-ok"></i>
                        </span>
                    </span>
                    <xsl:value-of select="@name" />
                </a>
            </div>
        </xsl:template>

        <xsl:template match="item[position() = last()]" mode="checkout-steps-summary">
            <div class="progress-step active-color current-step">
                <a class="step-outer">
                    <span class="step-inner">
                        <span class="light-color active-bg">
                            <xsl:value-of select="position()" />
                        </span>
                    </span>
                    <xsl:text>Сводка</xsl:text>
                </a>
            </div>
            <div class="progress-step middle-color last">
                <a class="step-outer">
                    <span class="step-inner">
                        <span>
                            <xsl:value-of select="position()+1" />
                        </span>
                    </span>
                    <xsl:value-of select="@name" />
                </a>
            </div>
        </xsl:template>

	<xsl:template match="purchasing">
		<h4>
			<xsl:text>Purchase is in progress: </xsl:text>
			<xsl:value-of select="concat(@stage, '::', @step, '()')" />
		</h4>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'result']">
            <div class="align-center">
                <div class="alert alert-error margin-bottom margin-top">
                    <xsl:text>&emarket-order-failed;</xsl:text>
                </div>
            </div>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'result' and @step = 'successful']">

            <xsl:variable name="pay_lk" select="document('udata://emarket/check_lk_pay')/udata" />
            <xsl:variable name="ord-id">
                <xsl:choose>
                    <xsl:when test="$pay_lk//result = 'true'">
                        <xsl:value-of select="$pay_lk//order_id" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select=".//order/@id" />
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            <xsl:variable name="order" select="document(concat('uobject://', $ord-id))/udata" />
            <xsl:variable name="payment_id" select="document(concat('uobject://', $order//property[@name='payment_id']/value/item/@id))/udata"/>
<!--            <xsl:call-template name="pos-steps">
                <xsl:with-param name="active-step" select="count(.//steps/item) + 1" />
            </xsl:call-template>-->
            <div class="align-center">
                <div class="alert alert-success margin-bottom margin-top">
                    <xsl:text>&emarket-order-successful;</xsl:text>
                </div>

                <p class="align-center">
                    На Ваш адрес электронной почты было отправлено уведомление.<br />
                    Номер вашего заказа <strong><xsl:value-of select="$order//group[@name = 'order_props']/property[@name='number']/value" /></strong>
                </p>
                <xsl:choose>
                    <!-- Юрики -->
                    <xsl:when test="$pay_lk//payment_id = 'invoice'">
                        <a href="/emarket/print_invoice/{$pay_lk//order_id}" target="_blank" class="button-normal light-color middle-gradient dark-gradient-hover margin-bottom">Скачать счет</a>
                    </xsl:when>
                    <xsl:when test="$pay_lk//payment_id = 'receipt'">
                        <xsl:value-of select="document(concat('udata://emarket/receipt_link/', .//order/@id))" disable-output-escaping="yes" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:if test="document(concat('uobject://', $payment_id//property[@name='payment_type_id']/value/item/@id))//property[@name='class_name']/value = 'receipt'">
                            <xsl:value-of select="document(concat('udata://emarket/receipt_link/', .//order/@id))" disable-output-escaping="yes" />
                        </xsl:if>
                        <xsl:if test="invoice_link">
                            <a href="{invoice_link}" target="_blank" class="button-normal light-color middle-gradient dark-gradient-hover margin-bottom">Скачать счет</a>
                        </xsl:if>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <hr />

            <div class="content-holder align-center">
                <a href="/" class="button-normal light-color middle-gradient dark-gradient-hover">
                    Продолжить покупки
                </a>
                &#160;
                &#160;
                &#160;
                <a href="/" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                    На главную
                    <span>
                        <i class="icon-angle-right"></i>
                    </span>
                </a>
            </div>
	</xsl:template>
</xsl:stylesheet>