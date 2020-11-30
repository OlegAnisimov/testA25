<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:output encoding="utf-8" method="html" indent="yes"/>

        <xsl:template match="/">
            <h2 class="subheader-font bigger-header margin-bottom">Способ доставки</h2>
            <xsl:apply-templates select="udata/onestep/delivery_choose/items/item" mode="purchase-delivery-method" />
            <xsl:apply-templates select="udata/onestep/delivery/delivery/items/item" mode="purchase-delivery-method" />
        </xsl:template>

        <xsl:template match="item" mode="purchase-delivery-method">
            <div class="form-input radio-input">
                <input type="radio" value="{@id}" id="meth-id-{@id}" name="delivery-id" data-title="{@name}" data-price="{@price}" required="required">
<!--                    <xsl:attribute name="checked">
                        <xsl:if test="@id=349">checked</xsl:if>
                    </xsl:attribute>-->
                </input>
                <label for="meth-id-{@id}" class="middle-color dark-hover">
                    <xsl:value-of select="@name" />
                    <xsl:text> - </xsl:text>
                    <xsl:value-of select="@price" />
                    <xsl:text> руб.</xsl:text>
                </label>
            </div>
        </xsl:template>

</xsl:stylesheet>