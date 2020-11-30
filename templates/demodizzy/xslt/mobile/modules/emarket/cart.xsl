<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="result[@method = 'cart']">
		<xsl:apply-templates select="document('udata://emarket/cart')/udata" />
		<xsl:if test="$user-type != 'guest'">
			<p style="margin-top:10px;">
				<a href="{$lang-prefix}/emarket/ordersList/" >
					<xsl:text>&view-orders-list;</xsl:text>
				</a>
			</p>
		</xsl:if>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']">
		<div class="basket">
			<xsl:text>&basket-empty;</xsl:text>
		</div>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart'][items/item]">
		<div class="basket">
			<table class="blue">
				<thead>
					<tr>
						<th class="name">
							<xsl:text>&basket-item;</xsl:text>
						</th>
						<th>
							<xsl:text>&price;</xsl:text>
						</th>
						<th>
							<xsl:text>&amount;</xsl:text>
						</th>
						<th>
							<xsl:text>&sum-actual;</xsl:text>
						</th>
						<th>
							<xsl:text></xsl:text>
						</th>
					</tr>
				</thead>
				<tbody>
					<xsl:apply-templates select="items/item" />
					<xsl:apply-templates select="summary/price/delivery[.!='']" mode="cart" />
				</tbody>
				<xsl:apply-templates select="summary" />
			</table>
			<div>
                <a href="{$lang-prefix}/emarket/basket/remove_all/">
                    <xsl:text>&clear-basket;</xsl:text>
                </a>
                <a href="{$lang-prefix}/emarket/purchase/" class="button big basket_purchase">
                    <xsl:text>&begin-purchase;</xsl:text>
                </a>
            </div>
			<div class="clear"></div>
		</div>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart'][items/item/discount]">
		<div class="basket">
			<table class="blue">
				<thead>
					<tr>
						<th class="name">
							<xsl:text>&basket-item;</xsl:text>
						</th>
						<th>
							<xsl:text>&price;</xsl:text>
						</th>
						<th>
							<xsl:text>&price-actual;</xsl:text>
						</th>
						<th>
							<xsl:text>&amount;</xsl:text>
						</th>
						<th>
							<xsl:text>&sum-actual;</xsl:text>
						</th>
						<th>
							<xsl:text>&delete;</xsl:text>
						</th>
					</tr>
				</thead>
				<tbody>
					<xsl:apply-templates select="items/item" />
				</tbody>
				<xsl:apply-templates select="summary" />
			</table>
			<div>
                <a href="{$lang-prefix}/emarket/basket/remove_all/">
                    <xsl:text>&clear-basket;</xsl:text>
                </a>
                <a href="{$lang-prefix}/emarket/purchase/" class="button big basket_purchase">
                    <xsl:text>&begin-purchase;</xsl:text>
                </a>
            </div>
			<div class="clear"></div>
		</div>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']//item">
		<tr class="cart_item_{@id}">
			<td class="name">
				<a href="{page/@link}">
					<xsl:value-of select="@name" />
				</a>
			</td>
			<td>
				<xsl:apply-templates select="price" />
			</td>
			<td>
				<!--<input type="text" value="{amount}" onkeyup="
					var e = jQuery(this).next('input'), old = e.val();
					e.val(this.value);
					site.basket.modify({@id}, this.value, old);
				" />
				-->
				<xsl:value-of select="amount" />
				<input type="hidden" value="{amount}" />
			</td>
			<td class="last_td">
				<span class="cart_item_price_{@id}">
					<xsl:apply-templates select="total-price" />
				</span>
			</td>
			<td>
				<a href="{$lang-prefix}/emarket/basket/remove/item/{@id}/" id="{@id}" class="del" />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']//item[discount]">
		<tr class="cart_item_{@id}">
			<td class="name">
				<a href="{page/@link}">
					<xsl:value-of select="@name" />
				</a>
			</td>
			<td><xsl:apply-templates select="price/original" /></td>
			<td>
				<xsl:apply-templates select="price" />
			</td>
			<td>
				<input type="text" value="{amount}" onkeyup="
					var e = jQuery(this).next('input'), old = e.val();
					e.val(this.value);
					site.basket.modify({@id}, this.value, old);
				" />
				<input type="hidden" value="{amount}" />
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
					<xsl:text> </xsl:text>
					<xsl:value-of select="price/@suffix" />
				</span>
			</td>
			<td style="text-align:right;">
				<span class="cart_item_price_{@id}">
					<xsl:apply-templates select="total-price" />
				</span>
			</td>
			<td>
				<a href="{$lang-prefix}/emarket/basket/remove/item/{@id}/" id="{@id}" class="del" />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']/summary">
		<tr style="text-align:left;">
				<td colspan="3" style="text-align:left;">
					<xsl:text>&discounted-sum;: </xsl:text>
				</td>
				<td colspan="2" style="text-align:right;">
					<span class="cart_goods_discount">
						<xsl:apply-templates select="price"/>
					</span>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:left;">
					<xsl:text>&order-discount;: </xsl:text>
				</td>
				<td colspan="2" style="text-align:right;">
					<span class="cart_discount">
						<xsl:choose>
							<xsl:when test="price/discount!=''">
								<xsl:value-of select="price/discount" />
							</xsl:when>
							<xsl:otherwise>
								0
							</xsl:otherwise>
						</xsl:choose>
						<xsl:text> </xsl:text>
						<xsl:value-of select="price/@suffix" />
					</span>
				</td>
				<td></td>
			</tr>
		<tfoot>
			<tr>
				<td colspan="6" align="right">
					<p class="itog"><xsl:text>&summary-price;:  </xsl:text>

					<span class="cart_summary">
						<xsl:apply-templates select="price" mode="discounted-price"/>
					</span></p>
				</td>
			</tr>
		</tfoot>
	</xsl:template>

	<xsl:template match="delivery[.!='']" mode="cart">
		<tr>
			<td class="name">
				<strong>&delivery;</strong>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td colspan="2" style="text-align:right;">
				<xsl:value-of select="." />
					<xsl:text> </xsl:text>
				<xsl:value-of select="../@suffix" />
			</td>
			<td>
			</td>
		</tr>
	</xsl:template>

</xsl:stylesheet>