<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="result[@method = 'cart']">
		<xsl:if test="not(contains($purchase-method, 'purchasing_one_step'))">
			<xsl:apply-templates select="//steps" />
		</xsl:if>
		<xsl:apply-templates select="document('udata://emarket/cart')/udata" />
	</xsl:template>

	<xsl:template match="udata[@method = 'cart']">
		<div class="basket">
			<h4 class="empty-content">&basket-empty;</h4>
			<p>&return-to-catalog;</p>
		</div>
	</xsl:template>


	<xsl:template match="udata[@method = 'cart'][count(items/item) &gt; 0]">
		<div class="basket">
			<table>
				<thead>
					<tr>
						<td>
							<xsl:text>&basket-item;</xsl:text>
						</td>
						<td>
							<xsl:text>&price;, </xsl:text><xsl:value-of select="$currency-prefix" /><xsl:value-of select="$currency-suffix" /> x <xsl:text>&amount;</xsl:text>
						</td>
						<td>
							<xsl:text>&item-discount;, </xsl:text><xsl:value-of select="$currency-prefix" /><xsl:value-of select="$currency-suffix" />
						</td>
						<td>
							<xsl:text>&sum;, </xsl:text><xsl:value-of select="$currency-prefix" /><xsl:value-of select="$currency-suffix" />
						</td>
						<td>
							<xsl:text></xsl:text>
						</td>
					</tr>
				</thead>
				<tbody>
					<xsl:apply-templates select="items/item" />
				</tbody>
			</table>
			<div class="summary">
				<xsl:apply-templates select="summary" />
			</div>
			<div class="cart-buttons">
				<!--a href="{$lang-prefix}/emarket/basket/remove_all/">
					<xsl:text>&clear-basket;</xsl:text>
				</a-->
				<a href="{$lang-prefix}/" class="toCatalog">&continue-shopping;</a>		
				<a href="{$lang-prefix}{$purchase-method}" class="basket_purchase size2">
					<xsl:text>&begin-purchase;</xsl:text>
				</a>
			</div>
			<div class="clear"></div>
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
					<img class="top" src="/templates/demodizzy/images/amount-top.png"/>
					<img class="bottom" src="/templates/demodizzy/images/amount-bottom.png"/>
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

</xsl:stylesheet>