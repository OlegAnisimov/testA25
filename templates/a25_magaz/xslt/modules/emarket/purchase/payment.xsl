<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'bonus']">
            <xsl:apply-templates select="//steps" mode="checkout-steps" />
		<form id="bonus_payment" method="post" action="do/">
			<h4>
				<xsl:text>&pay-by-bonuses;</xsl:text>
			</h4>
			<div style="margin:0 15px;">
				<p>&can-use-bonuses;</p>
				<xsl:text>&order-summ; </xsl:text>
				<xsl:value-of select="$currency-prefix" />
				<xsl:text> </xsl:text>
				<xsl:value-of select="bonus/actual_total_price" />
				<xsl:text> </xsl:text>
				<xsl:value-of select="$currency-suffix" />
				<xsl:text>.</xsl:text><br />
				<xsl:text> &available-bonuses; </xsl:text>
				<xsl:value-of select="$currency-prefix" />
				<xsl:text> </xsl:text>
				<xsl:value-of select="bonus/available_bonus" />
				<xsl:text> </xsl:text>
				<xsl:value-of select="$currency-suffix" />
				<xsl:text>.</xsl:text>
			</div>
			<div><label>&spend-bonuses; <xsl:value-of select="$currency-prefix" /><input type="text" name="bonus" /><xsl:value-of select="$currency-suffix" /></label></div>
			<div><input type="submit" value="&continue;" class="button big" /></div>
		</form>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment']/bonus/@prefix">
		<xsl:value-of select="." />
		<xsl:text>&#160;</xsl:text>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment']/bonus/@suffix">
		<xsl:text>&#160;</xsl:text>
		<xsl:value-of select="." />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'choose']">
            <xsl:apply-templates select="//steps" mode="checkout-steps" />
            <form class="content-form" action="{$lang-prefix}/emarket/summary_do" method="POST">
                <div class="box-table">
                    <div class="box grid-100 tablet-grid-100 last">
                        <h2 class="subheader-font bigger-header margin-bottom">Способ оплаты</h2>
                        <xsl:apply-templates select="items/item" mode="purchase-payment-method" />
                    </div>
                </div>

                <hr />

                <div class="content-holder align-right">
                    <xsl:if test="$user-type = 'guest'">
                        <a href="{$lang-prefix}/emarket/purchase/delivery/choose" class="pull-left button-normal light-color middle-gradient dark-gradient-hover">
                            <b class="hide-on-desktop hide-on-tablet">Назад</b>
                            <b class="hide-on-mobile">К предыдущему шагу</b>
                        </a>
                    </xsl:if>

                    <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                        Продолжить
                        <span>
                            <i class="icon-angle-right"></i>
                        </span>
                    </button>
                </div>
            </form>
	</xsl:template>

	<xsl:template match="item" mode="purchase-payment-method">
            <xsl:param name="is_pers">0</xsl:param>
            <div class="form-input radio-input">
                <input type="radio" checked="checked" value="{@id}" id="pay-id-{@id}" name="payment-id" data-type="{document(concat('uobject://', document(concat('uobject://', @id,'.payment_type_id'))//value/item/@id, '.class_name'))//value}" class="paym-type-choose" />
                <label for="pay-id-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                </label>
                <xsl:if test="document(concat('uobject://', document(concat('uobject://', @id,'.payment_type_id'))//value/item/@id, '.class_name'))//value = 'invoice' and $is_pers = '1'">
                    <div class="for-invoice-form">
                        <xsl:call-template name="pers_leg_form" />
                    </div>
                </xsl:if>
            </div>
        </xsl:template>



	<xsl:template match="item" mode="payment">
		<div>
			<label>
				<xsl:if test="(position() = 1) and (@type-name = 'receipt')">
					<script>
						window.paymentId = <xsl:value-of select="@id" />;
					</script>
				</xsl:if>
				<input type="radio" name="payment-id" class="{@type-name}" value="{@id}">
					<xsl:attribute name="onclick">
						<xsl:text>this.form.action = </xsl:text>
						<xsl:choose>
							<xsl:when test="@type-name != 'receipt'">
								<xsl:text>'</xsl:text>
								<xsl:value-of select="//submit_url" />
								<xsl:text>';</xsl:text>
							</xsl:when>
							<xsl:otherwise><xsl:text>'/emarket/ordersList/'; window.paymentId = '</xsl:text><xsl:value-of select="@id" /><xsl:text>';</xsl:text></xsl:otherwise>
						</xsl:choose>
					</xsl:attribute>
					<xsl:if test="@active = 'active'">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:value-of select="@name" />
			</label>
		</div>
	</xsl:template>

	<xsl:template match="item" mode="payment_one_step">
		<div>
			<label>
				<input type="radio" name="payment-id" class="{@type-name}" value="{@id}">
					<xsl:if test="position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
					<xsl:if test="@active = 'active'">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:value-of select="@name" />
			</label>
		</div>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'yandex30']">
		<form id="form_yandex30" action="{formAction}" method="post">
			<input type="hidden" name="shopId" value="{shopId}" />
			<input type="hidden" name="Sum" value="{Sum}" />
			<input type="hidden" name="BankId" value="{BankId}" />
			<input type="hidden" name="scid" value="{scid}" />
			<input type="hidden" name="CustomerNumber" value="{CustomerNumber}" />
			<input type="hidden" name="order-id" value="{orderId}" />
			<input type="hidden" name="PaymentType" value="" />
			<input type="hidden" name="PaymentTypeProvider" value="" />
			<input type="hidden" name="cms_name" value="umistand"/>
			<div class="inline">
				<xsl:apply-templates select="items/item" mode="payment-modes-yandex30" />
			</div>
			<div>
				<xsl:text>&payment-redirect-text; Yandex.</xsl:text>
			</div>
			<div>
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
		<script>
			var elements = document.getElementById('form_yandex30').elements;
			elements.mode_type.value = '';
			function change(type, subtype) {
				elements.PaymentType.value = type;
				elements.PaymentTypeProvider.value = subtype;
			}
		</script>
	</xsl:template>

	<xsl:template match="item" mode="payment-modes-yandex30">
		<label><input type="radio" name="mode_type" value="{id}" onChange="javascript:change('{type}', '{subtype}');"/><xsl:value-of select="label"/></label>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'acquiropay']">
		<form method="post" action="{formAction}">
			<input type="hidden" name="product_id" value="{product_id}" />
			<input type="hidden" name="amount" value="{amount}" />
			<input type="hidden" name="language" value="{language}" />
			<input type="hidden" name="cf" value="{order_id}" />
			<input type="hidden" name="ok_url" value="{ok_url}" />
			<input type="hidden" name="cb_url" value="{cb_url}" />
			<input type="hidden" name="ko_url" value="{ko_url}" />
			<input type="hidden" name="token" value="{token}" />
			<div>
				<xsl:text>&payment-redirect-text; AcquiroPay.</xsl:text>
			</div>
			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'chronopay']">
		<form method="post" action="{formAction}">
			<input type="hidden" name="product_id" value="{product_id}" />
			<input type="hidden" name="product_name" value="{product_name}" />
			<input type="hidden" name="product_price" value="{product_price}" />
			<input type="hidden" name="language" value="{language}" />
			<input type="hidden" name="cs1" value="{cs1}" />
			<input type="hidden" name="cs2" value="{cs2}" />
			<input type="hidden" name="cs3" value="{cs3}" />
			<input type="hidden" name="cb_type" value="{cb_type}" />
			<input type="hidden" name="cb_url" value="{cb_url}" />
			<input type="hidden" name="decline_url" value="{decline_url}" />
			<input type="hidden" name="sign" value="{sign}" />

			<div>
				<xsl:text>&payment-redirect-text; Chronopay.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'yandex']">
		<form action="{formAction}" method="post">
			<input type="hidden" name="shopId"	value="{shopId}" />
			<input type="hidden" name="Sum"		value="{Sum}" />
			<input type="hidden" name="BankId"	value="{BankId}" />
			<input type="hidden" name="scid"	value="{scid}" />
			<input type="hidden" name="CustomerNumber" value="{CustomerNumber}" />
			<input type="hidden" name="order-id" value="{orderId}" />

			<div>
				<xsl:text>&payment-redirect-text; Yandex.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'payonline']">
		<form action="{formAction}" method="post">

			<input type="hidden" name="MerchantId" 	value="{MerchantId}" />
			<input type="hidden" name="OrderId" 	value="{OrderId}" />
			<input type="hidden" name="Currency" 	value="{Currency}" />
			<input type="hidden" name="SecurityKey" value="{SecurityKey}" />
			<input type="hidden" name="ReturnUrl" 	value="{ReturnUrl}" />
			<!-- NB! This field should exist for proper system working -->
			<input type="hidden" name="order-id"    value="{orderId}" />
			<input type="hidden" name="Amount" value="{Amount}" />

			<div>
				<xsl:text>&payment-redirect-text; PayOnline.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'robox']">
		<form action="{formAction}" method="post">
			<input type="hidden" name="MrchLogin" value="{MrchLogin}" />
			<input type="hidden" name="OutSum"	  value="{OutSum}" />
			<input type="hidden" name="InvId"	  value="{InvId}" />
			<input type="hidden" name="Desc"	  value="{Desc}" />
			<input type="hidden" name="SignatureValue" value="{SignatureValue}" />
			<input type="hidden" name="IncCurrLabel"   value="{IncCurrLabel}" />
			<input type="hidden" name="Culture"   value="{Culture}" />
			<input type="hidden" name="shp_orderId" value="{shp_orderId}" />

			<div>
				<xsl:text>&payment-redirect-text; Robox.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'rbk']">
		<form action="{formAction}" method="post">
			<input type="hidden" name="eshopId" value="{eshopId}" />
			<input type="hidden" name="orderId"	value="{orderId}" />
			<input type="hidden" name="recipientAmount"	value="{recipientAmount}" />
			<input type="hidden" name="recipientCurrency" value="{recipientCurrency}" />
			<input type="hidden" name="version" value="{version}" />

			<div>
				<xsl:text>&payment-redirect-text; RBK Money.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'kupivkredit'][@action = 'widget']">
		<xsl:choose>
			<xsl:when test="./@test-mode">
				<script src="https://kupivkredit-test-fe.tcsbank.ru/widget/vkredit.js"></script>
			</xsl:when>
			<xsl:otherwise>
				<script src="https://www.kupivkredit.ru/widget/vkredit.js"></script>
			</xsl:otherwise>
		</xsl:choose>
		<script>
			jQuery(document).ready(function() {
			<![CDATA[
			function widgetOpen(order, sig, price) {
				vKredit = new VkreditWidget(1, price, {
					order: order,
					sig: sig,
					onClose: function() {
						window.location.assign("/emarket/purchase/payment/choose/");
					},
					onAccept: function(accepted) {
						if(accepted == 1) {
							window.location.assign("/emarket/purchase/payment/kupivkredit/?accepted=accepted");
						} else {
							window.location.assign("/emarket/purchase/payment/choose/");
					}

					}
				});

				vKredit.openWidget();
			}
		]]>
			widgetOpen("<xsl:value-of select="order" />", "<xsl:value-of select="sig" />", <xsl:value-of select="totalPrice" />);
		});
		</script>
		<xsl:apply-templates select="//steps" />
		<h4>&in-progress;</h4>
		<p>&credit-request;</p>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'kupivkredit'][@action = 'declined']">
		<xsl:apply-templates select="//steps" />
		<h4>&error;</h4>
		<p>&credit-canceled;</p>
		<p><a href="/emarket/purchase/payment">&select-payment;</a></p>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'kupivkredit'][@action = 'error']">
		<xsl:apply-templates select="//steps" />
		<h4>&error;</h4>
		<p>&order-error;</p>
		<p><a href="/emarket/purchase/payment">&select-payment;</a></p>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'payanyway']">
		<form action="{formAction}" method="post">
            <input type="hidden" name="MNT_ID" value="{mntId}" />
            <input type="hidden" name="MNT_TRANSACTION_ID" value="{mnTransactionId}" />
            <input type="hidden" name="MNT_CURRENCY_CODE" value="{mntCurrencyCode}" />
            <input type="hidden" name="MNT_AMOUNT" value="{mntAmount}" />
            <input type="hidden" name="MNT_TEST_MODE" value="{mntTestMode}" />
            <input type="hidden" name="MNT_SIGNATURE" value="{mntSignature}" />
            <input type="hidden" name="MNT_SUCCESS_URL" value="{mntSuccessUrl}" />
            <input type="hidden" name="MNT_FAIL_URL" value="{mntFailUrl}" />

			<div>
				<xsl:text>&payment-redirect-text; PayAnyWay.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
		<xsl:call-template name="form-send" />
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'dengionline']">
		<xsl:apply-templates select="//steps" />
		<form class="width_100" action="{formAction}" method="post">
			<h4>
				<xsl:text>&payment-type;:</xsl:text>
			</h4>
			<input type="hidden" name="project" value="{project}" />
			<input type="hidden" name="amount" value="{amount}" />
			<input type="hidden" name="nickname" value="{order_id}" />
			<input type="hidden" name="source" value="{source}" />
			<input type="hidden" name="order_id" value="{order_id}" />
			<input type="hidden" name="comment" value="{comment}" />
			<input type="hidden" name="paymentCurrency" value="{paymentCurrency}" />
			<div class="inline">
				<xsl:apply-templates select="items/item[position() mod 3 = 1]" mode="payment-modes" />
			</div>
			<div class="inline">
				<xsl:apply-templates select="items/item[position() mod 3 = 2]" mode="payment-modes" />
			</div>
			<div class="inline">
				<xsl:apply-templates select="items/item[position() mod 3 = 0]" mode="payment-modes" />
			</div>
			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'paypal']">
		<xsl:apply-templates select="//steps" />
		<form class="width_100" action="{formAction}" method="post">
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="business" value="{paypalemail}" />
			<input type="hidden" name="item_name" value="Payment for order #{order_id}" />
			<input type="hidden" name="item_number" value="{order_id}" />
			<input type="hidden" name="amount" value="{total}" />
			<input type="hidden" name="no_shipping" value="1" />
			<input type="hidden" name="return" value="{return_success}" />
			<input type="hidden" name="rm" value="2" />
			<input type="hidden" name="cancel_return" value="{cancel_return}" />
			<input type="hidden" name="notify_url" value="{notify_url}" />
			<input type="hidden" name="currency_code" value="{currency}" />

			<div>
				<xsl:text>&payment-redirect-text; PayPal.</xsl:text>
			</div>

			<div>
				<input type="submit" value="&pay;" class="button big" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="item" mode="payment-modes">
		<label><input type="radio" name="mode_type" value="{id}"/><xsl:value-of select="label"/></label>
	</xsl:template>

        <xsl:template name="pers_leg_form">
            <p>
                <strong>Выберите юридическое лицо:</strong>
            </p>
            <input type="hidden" name="param2" value="do" />
            <xsl:choose>
                <xsl:when test="$user-info//property[@name='legal_persons']/value">
                    <xsl:apply-templates select="$user-info//property[@name='legal_persons']/value" mode="legal-person1">
                        <xsl:with-param name="customer_email" select="$user-info//property[@name = 'e-mail']/value" />
                    </xsl:apply-templates>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:call-template name="legal-person1">
                        <xsl:with-param name="customer_email" select="$user-info//property[@name = 'e-mail']/value" />
                    </xsl:call-template>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:template>

        <xsl:template name="legal-person1">
            <xsl:param name="customer_email"/>

            <input type="hidden" name="legal-person" value="new" />
            <xsl:variable name="lp_id" select="document('udata://emarket/get_lp_id')/udata" />
            <xsl:apply-templates select="document(concat('udata://data/getCreateForm/', $lp_id))/udata" mode="legal_form" >
                <xsl:with-param name="customer_email" select="$customer_email"/>
            </xsl:apply-templates>
        </xsl:template>

        <xsl:template match="value" mode="legal-person1">
            <xsl:param name="customer_email"/>
            <input type="hidden" name="legal-person" value="new"/>
                <xsl:apply-templates select="item" mode="legal-person1" />
                <div class="form-input radio-input">
                    <input id="lp-new" type="radio" name="legal-person" value="new" class="lp-choose"/>
                    <label for="lp-new" class="middle-color dark-hover">
                        <xsl:text>Новое юридическое лицо</xsl:text>
                    </label>
                </div>

            <div class="new-legal-person">
                <xsl:variable name="lp_id" select="document('udata://emarket/get_lp_id')/udata" />
                <xsl:apply-templates select="document(concat('udata://data/getCreateForm/', $lp_id))/udata" mode="legal_form" >
                    <xsl:with-param name="customer_email" select="$customer_email"/>
                </xsl:apply-templates>
            </div>
        </xsl:template>

        <xsl:template match="item" mode="legal-person1">
            <div class="form-input radio-input">
                <input id="lp-{@id}" type="radio" name="legal-person" value="{@id}" class="lp-choose">
                    <xsl:if test="position() = 1">
                        <xsl:attribute name="checked">
                            <xsl:text>checked</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </input>
                <label for="lp-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                </label>
            </div>
        </xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'invoice']" xmlns:xlink="http://www.w3.org/TR/xlink">
<!--            <xsl:call-template name="pos-steps">
                <xsl:with-param name="active-step" select="'5'" />
            </xsl:call-template>-->
            <div class="box grid-50 tablet-grid-50 last" style="margin-top:15px;">
		<form id="invoice" method="post" action="do" class="content-form">
                    <xsl:apply-templates select="items" mode="legal-person">
                        <xsl:with-param name="customer_email" select="customer/@e-mail" />
                    </xsl:apply-templates>
                    <hr />
                    <br />
                    <div class="form-submit">
                        <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover chk-lp-subm">
                            &make-bill;
                            <span>
                                <i class="icon-angle-right"></i>
                            </span>
                        </button>
                    </div>
                    <br />
		</form>
            </div>
	</xsl:template>


	<xsl:template match="items" mode="legal-person" xmlns:xlink="http://www.w3.org/TR/xlink">
            <xsl:param name="customer_email"/>
            <input type="hidden" name="legal-person" value="new" />
            <xsl:apply-templates select="document(../@xlink:href)/udata" mode="legal_form" >
                <xsl:with-param name="customer_email" select="$customer_email"/>
            </xsl:apply-templates>
	</xsl:template>

	<xsl:template match="items[count(item) &gt; 0]" mode="legal-person" xmlns:xlink="http://www.w3.org/TR/xlink">
            <xsl:param name="customer_email"/>
            <h2 class="subheader-font bigger-header margin-bottom">
                <xsl:text>&choose-legal_person;:</xsl:text>
            </h2>
            <xsl:apply-templates select="item" mode="legal-person" />
            <div class="form-input radio-input">
                <input type="radio" value="new" id="legper-new" name="legal-person" class="ch-lp"/>
                <label for="legper-new" class="middle-color dark-hover">
                    <xsl:text>&new-legal-person;</xsl:text>
                </label>
            </div>

            <div id="new-legal-person">
                <xsl:apply-templates select="document(../@xlink:href)/udata" mode="legal_form" >
                    <xsl:with-param name="customer_email" select="$customer_email"/>
                </xsl:apply-templates>
            </div>
	</xsl:template>

	<xsl:template match="item" mode="legal-person"  xmlns:xlink="http://www.w3.org/TR/xlink">
            <div class="form-input radio-input">
                <input type="radio" value="{@id}" id="legper-{@id}" name="legal-person" class="ch-lp">
                    <xsl:if test="position() = 1">
                        <xsl:attribute name="checked">
                            <xsl:text>checked</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </input>
                <label for="legper-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                </label>
            </div>
	</xsl:template>

	<xsl:template match="udata" mode="legal_form">
		<xsl:param name="customer_email"/>

		<xsl:apply-templates select="//field[@name='name']"  mode="legal_form" >
			<xsl:with-param name="title" select="'Наименование организации'"/>
		</xsl:apply-templates>
		<xsl:apply-templates select="//field[@name='inn']"   mode="legal_form" >
			<xsl:with-param name="title" select="'ИНН'"/>
		</xsl:apply-templates>
		<xsl:apply-templates select="//field[@name='kpp']"   mode="legal_form" >
			<xsl:with-param name="title" select="'КПП'"/>
		</xsl:apply-templates>
		<xsl:apply-templates select="//field[@name='email']" mode="legal_form" >
			<xsl:with-param name="title" select="'E-mail для доставки счета'"/>
			<xsl:with-param name="customer_email" select="$customer_email"/>
		</xsl:apply-templates>
	</xsl:template>

	<xsl:template name="form-send">
		<script>
			jQuery('body').hide(0, function() {
				jQuery(document).ready(function(){
					jQuery('.center form').get(0).submit();
				});
			});
		</script>
	</xsl:template>

</xsl:stylesheet>