<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<!-- Выбор адреса доставки -->
	<xsl:template match="purchasing[@stage = 'delivery'][@step = 'address']">
            <xsl:apply-templates select="//steps" mode="checkout-steps" />
            <form class="content-form" action="{$lang-prefix}/emarket/purchase/delivery/address/do/" method="POST">

                <div class="box-table">
                    <div class="box grid-50 tablet-grid-50">
                        <h2 class="subheader-font bigger-header margin-bottom">Адрес доставки</h2>

                        <xsl:apply-templates select="delivery/items/item" mode="purchase-delivery-choose" />
                        <xsl:apply-templates select="items/item" mode="purchase-delivery-adrs" />
                        <div class="form-input radio-input">
                            <input type="radio" name="delivery-address" id="adr-new" value="new" data-id="{@type-id}" />
                            <label for="adr-new" class="middle-color dark-hover">Новый адрес доставки</label>
                        </div>
                    </div>

                    <div class="box grid-50 tablet-grid-50 last">
                        <div class="new-adr-cont">

                        </div>
                    </div>
                </div>

                <hr />

                <div class="content-holder align-right">
                    <xsl:if test="$user-type = 'guest'">
                        <a href="/emarket/purchase/required" class="pull-left button-normal light-color middle-gradient dark-gradient-hover">
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

        <xsl:template match="item" mode="purchase-delivery-choose">
            <div class="form-input radio-input">
                <input type="radio" checked="checked" value="delivery_{@id}" id="adr-id-{@id}" name="delivery-address" class="del-self" data-price="{@price}" data-title="{@name}"/>
                <label for="adr-id-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                    <xsl:text> - </xsl:text>
                    <xsl:value-of select="@price" />
                    <xsl:text> руб.</xsl:text>
                </label>
            </div>
        </xsl:template>

        <xsl:template match="item" mode="purchase-delivery-adrs">
            <div class="form-input radio-input">
                <input type="radio" checked="checked" value="{@id}" id="adr-d-id-{@id}" name="delivery-address" class="del-adr" required="required"/>
                <label for="adr-d-id-{@id}" class="middle-color dark-hover">
                    <xsl:apply-templates select="document(concat('uobject://', @id))//property" mode="delivery-address" />
                </label>
            </div>
        </xsl:template>

	<xsl:template name="delivery_address">
		<xsl:param name="context" />
		<xsl:param name="block" />

		<h4>
			<xsl:text>&choose-delivery-address;:</xsl:text>
		</h4>
		<input type="hidden" name="delivery-address" value="new" />
		<xsl:apply-templates select="$context/delivery/items/item" mode="delivery-self" />
		<xsl:apply-templates select="$context/items" mode="delivery-address" />

		<xsl:if test="(count($context/items/item) &gt; 0 and $context/only_self_delivery != 1) or ($context/self_delivery_exist = 1 and $context/only_self_delivery != 1)">
			<div>
				<label>
					<input type="radio" name="delivery-address" value="new" />
					<xsl:text>&new-delivery-address;</xsl:text>
				</label>
			</div>
		</xsl:if>

		<xsl:if test="$context/only_self_delivery != 1 and $context/@xlink:href">
			<div id="new-address">
				<xsl:apply-templates select="document($context/@xlink:href)//field" mode="form" />
			</div>
		</xsl:if>

		<script>
			jQuery(document).ready(function() {
				var block = "<xsl:value-of select="$block"/>";
				jQuery(block).submit(function() {
					return site.forms.emarket.checkFields(this);
				});

				site.forms.emarket.toggleNewObjectForm(block, '#new-address');
			});
		</script>
	</xsl:template>

	<xsl:template match="items" mode="delivery-address" />

	<xsl:template match="items[count(item) &gt; 0]" mode="delivery-address">
		<xsl:apply-templates select="item" mode="delivery-address" />
	</xsl:template>

	<xsl:template match="item" mode="delivery-address">
		<div class="form_element">
			<label>
				<input type="radio" name="delivery-address" value="{@id}">
					<xsl:if test="@active = 'active' or position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:apply-templates select="document(concat('uobject://', @id))//property" mode="delivery-address" />
			</label>
		</div>
	</xsl:template>

	<xsl:template match="item[@id='self']" mode="delivery-address">
		<div class="form_element">
			<label>
				<input type="radio" name="delivery-address" value="{@id}" />
				<xsl:text></xsl:text>
			</label>
		</div>
	</xsl:template>

	<xsl:template match="property" mode="delivery-address">
		<xsl:value-of select="value" />
		<xsl:text>, </xsl:text>
	</xsl:template>

	<xsl:template match="property[@type = 'relation']" mode="delivery-address">
		<xsl:value-of select="value/item/@name" />
		<xsl:text>, </xsl:text>
	</xsl:template>

	<xsl:template match="property[position() = last()]" mode="delivery-address">
		<xsl:value-of select="value" />
	</xsl:template>


	<!-- Выбор способа доставки -->
	<xsl:template match="purchasing[@stage = 'delivery'][@step = 'choose']">
            <xsl:apply-templates select="//steps" mode="checkout-steps" />
            <form class="content-form" action="{$lang-prefix}/emarket/purchase/delivery/choose/do/" method="POST">
                <div class="box-table">
                    <div class="box grid-100 tablet-grid-100 last">
                        <h2 class="subheader-font bigger-header margin-bottom">Способ доставки</h2>
                        <xsl:apply-templates select="items/item" mode="purchase-delivery-method" />
                    </div>
                </div>

                <hr />

                <div class="content-holder align-right">
                    <xsl:if test="$user-type = 'guest'">
                        <a href="/emarket/purchase/delivery/address" class="pull-left button-normal light-color middle-gradient dark-gradient-hover">
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

        <xsl:template match="item" mode="purchase-delivery-method">
            <div class="form-input radio-input">
                <input type="radio" checked="checked" value="{@id}" id="meth-id-{@id}" name="delivery-id" data-title="{@name}"  />
                <label for="meth-id-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                    <xsl:text> - </xsl:text>
                    <xsl:value-of select="@price" />
                    <xsl:text> руб.</xsl:text>
                </label>
            </div>
        </xsl:template>

	<xsl:template match="item" mode="delivery-choose">
		<xsl:variable name="delivery-price" select="@price"/>
		<div>
			<label>
				<input type="radio" name="delivery-id" value="{@id}"
					   data-type="{@type-class-name}">
					<xsl:apply-templates select="." mode="delivery-choose-first" />
				</input>
				<xsl:value-of select="@name" />

				<xsl:call-template  name="delivery-price" >
					<xsl:with-param name="price" select="$delivery-price"/>
				</xsl:call-template >
			</label>
		</div>
	</xsl:template>

	<xsl:template match="item" mode="delivery-self">
		<xsl:variable name="delivery-price" select="@price"/>
		<div>
			<label>
				<input type="radio" name="delivery-address" value="delivery_{@id}"
					   data-type="{@type-class-name}">
					<xsl:if test="@active = 'active' or position() = 1">
						<xsl:attribute name="checked" select="'checked'" />
					</xsl:if>
				</input>
				<xsl:value-of select="@name" />

				<xsl:call-template  name="delivery-price" >
					<xsl:with-param name="price" select="$delivery-price"/>
				</xsl:call-template >
			</label>
		</div>
	</xsl:template>

	<xsl:template match="item" mode="delivery-choose-first">
			<xsl:if test="@active = 'active'">
				<xsl:attribute name="checked" select="'checked'" />
			</xsl:if>
	</xsl:template>

	<xsl:template match="item[1]" mode="delivery-choose-first">
		<xsl:attribute name="checked" select="'checked'" />
	</xsl:template>

	<xsl:template name="delivery-price">
		<xsl:param name="price" select="0"/>

		<xsl:variable name="formatted-price" select="document(concat('udata://emarket/applyPriceCurrency/', $price))/udata" />

		<xsl:text> - </xsl:text>
		<xsl:choose>
			<xsl:when test="$formatted-price/price">
				<xsl:apply-templates select="$formatted-price" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$price" />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>