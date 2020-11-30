<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/result[@method = 'ordersList']">
		<xsl:apply-templates select="document('udata://emarket/ordersList')" />
	</xsl:template>
	
	
	<xsl:template match="udata[@method = 'ordersList']">
		<div id="con_tab_orders">
			<xsl:if test="$method = 'personal'">
				<xsl:attribute name="style">display: none;</xsl:attribute>
			</xsl:if>
			
			<table class="blue">
				<thead>
					<tr>
						<th class="name">
							<xsl:text>&order-number;</xsl:text>
						</th>
						
						<th class="name">
							<xsl:text>&order-status;</xsl:text>
						</th>
						<th class="name">
							<xsl:text>&order-method;</xsl:text>
						</th>
						<th>
							<xsl:text>&order-sum;</xsl:text>
						</th>
					</tr>
				</thead>
				<tbody>
					<xsl:apply-templates select="items/item" mode="order" />
				</tbody>
			</table>
		</div>
	</xsl:template>
	
	<xsl:template match="item" mode="order">
		<xsl:apply-templates select="document(concat('udata://emarket/order/', @id))/udata" />
		<tr>
			<td colspan="4" class="separate"></td>
		</tr>
	</xsl:template>

	<xsl:template match="item[position() = last()]" mode="order">
		<xsl:apply-templates select="document(concat('udata://emarket/order/', @id))/udata" />
	</xsl:template>


	<xsl:template match="udata[@module = 'emarket'][@method = 'order']">
		<tr>
			<td class="name">
				<strong>
					<xsl:text>&number; </xsl:text>
					<xsl:value-of select="number" />
				</strong>
				<div>
					<xsl:text>&date-from; </xsl:text>
					<xsl:apply-templates select="document(concat('uobject://', @id, '.order_date'))//property" />
				</div>
			</td>
			<td class="name">
				<xsl:value-of select="status/@name" />
				<div>
					<xsl:text>&date-from-1; </xsl:text>
					<xsl:apply-templates select="document(concat('uobject://', @id, '.status_change_date'))//property" />
				</div>
			</td>
			<td>
				<xsl:apply-templates select="document(concat('uobject://', @id, '.payment_id'))//item/@name" />
			</td>
			<td>
				<xsl:apply-templates select="summary/price" mode="discounted-price"/>
			</td>
		</tr>
		
		<xsl:apply-templates select="items/item" />
		<xsl:apply-templates select="summary/price/delivery" />
		<xsl:apply-templates select="summary/price/discount" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'order']/items/item">
		<tr>
			<td colspan="3" class="name">
				<a href="{page/@link}">
					<xsl:value-of select="@name" />
				</a>
			</td>

			<td>
				<xsl:apply-templates select="price" mode="discounted-price"/>
				<xsl:text> x </xsl:text>
				<xsl:apply-templates select="amount" />
				<xsl:text> = </xsl:text>
				<xsl:apply-templates select="total-price" />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="delivery[.!='']">
		<tr>
			<td colspan="3" class="name">
				<strong>Доставка</strong>
			</td>
			<td>
				<xsl:value-of select="." />
					<xsl:text> </xsl:text>
				<xsl:value-of select="../@suffix" />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="discount[.!='']">
		<tr>
			<td colspan="3" class="name">
				<strong>Скидка</strong>
			</td>
			<td>
				<xsl:value-of select="." />
					<xsl:text> </xsl:text>
				<xsl:value-of select="../@suffix" />
			</td>
		</tr>
	</xsl:template>

</xsl:stylesheet>