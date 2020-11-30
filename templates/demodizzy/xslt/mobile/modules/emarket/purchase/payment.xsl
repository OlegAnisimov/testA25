<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="purchasing[@stage = 'payment'][@step = 'bonus']">
		<form id="bonus_payment" method="post" action="do/">
			<h4>
				<xsl:text>Оплата бонусами:</xsl:text>
			</h4>
			<div style="margin:0 15px;">
				<xsl:text>Вы собираетесь оплатить заказ на сумму </xsl:text>
				<xsl:apply-templates select="bonus/@prefix" />
				<xsl:value-of select="bonus/actual_total_price" />
				<xsl:apply-templates select="bonus/@suffix" />
				<xsl:text>.</xsl:text>
			</div>
			<div style="margin:0 15px;">
				<xsl:text>Вы можете оплатить Ваш заказ накопленными бонусами. Доступно бонусов на </xsl:text>
				<xsl:apply-templates select="bonus/@prefix" />
				<xsl:value-of select="bonus/available_bonus" />
				<xsl:apply-templates select="bonus/@suffix" />
				<xsl:text>.</xsl:text>
			</div>
			<div><label><input type="text" name="bonus" />Количество бонусов</label></div>
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
		<h4>
			<xsl:text>&payment-type;:</xsl:text>
		</h4>

		<form id="payment_choose" method="post" action="do/">
			<xsl:apply-templates select="items/item" mode="payment" />

			<div>
				<input type="submit" value="&continue;" class="button big" />
			</div>
		</form>
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
							<xsl:when test="@type-name != 'receipt'"><xsl:text>'do/';</xsl:text></xsl:when>
							<xsl:otherwise><xsl:text>'/emarket/ordersList/'; window.paymentId = '</xsl:text><xsl:value-of select="@id" /><xsl:text>';</xsl:text></xsl:otherwise>
						</xsl:choose>
					</xsl:attribute>
					<xsl:if test="position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:value-of select="@name" />
			</label>
		</div>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
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
				<input type="submit" value="Оплатить" class="button big" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="purchasing[@stage = 'payment'][@step = 'invoice']" xmlns:xlink="http://www.w3.org/TR/xlink">
		<form id="invoice" method="post" action="do">
			<xsl:apply-templates select="items" mode="legal-person" />
			<div>
				<input type="submit" value="Выписать счет" class="button big" />
			</div>
		</form>
		<script>
			jQuery('#invoice').submit(function(){
				var input = jQuery('input:radio:checked', this);
				if (typeof input.val() == 'undefined' || input.val() == 'new') {
					if (typeof input.val() == 'undefined') {
						jQuery('input:radio[value=new]', this).attr('checked','checked');
					}
					return site.forms.data.check(this);
				}
			});
		</script>
	</xsl:template>

	<xsl:template match="items" mode="legal-person" xmlns:xlink="http://www.w3.org/TR/xlink">
		<input type="hidden" name="legal-person" value="new" />
		<xsl:apply-templates select="document(../@xlink:href)//field" mode="form" />
	</xsl:template>

	<xsl:template match="items[count(item) &gt; 0]" mode="legal-person" xmlns:xlink="http://www.w3.org/TR/xlink">
		<h4>
			<xsl:text>&choose-legal_person;:</xsl:text>
		</h4>
		<xsl:apply-templates select="item" mode="legal-person" />

		<div>
			<label>
				<input type="radio" name="legal-person" value="new" />
				<xsl:text>&new-legal-person;</xsl:text>
			</label>
		</div>

		<div id="new-legal-person">
			<xsl:apply-templates select="document(../@xlink:href)//field" mode="form" />
		</div>

	</xsl:template>

	<xsl:template match="item" mode="legal-person"  xmlns:xlink="http://www.w3.org/TR/xlink">
		<div class="form_element">
			<label>
				<input type="radio" name="legal-person" value="{@id}">
					<xsl:if test="position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:value-of select="@name" />
			</label>
		</div>
	</xsl:template>


</xsl:stylesheet>