<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:output encoding="utf-8" method="html" indent="yes"/>
        <xsl:param name="typ_id" />
        <xsl:variable name="user-type" select="/result/user/@type" />
        <xsl:template match="/">
            <!--<h2 class="subheader-font bigger-header margin-bottom">Новый адрес доставки</h2>-->

            <xsl:apply-templates select="document(concat('udata://data/getCreateForm/', $typ_id))//field" mode="new-adrs-fields" />
        </xsl:template>

        <xsl:template match="field" mode="new-adrs-fields">
            <div class="form-input">
                <label for="new-adr-{@name}" class="middle-color">
                    <xsl:value-of select="@title" />
                    <xsl:if test="@required  = 'required'">
                        <span class="active-color">*</span>
                    </xsl:if>
                </label>
                <input type="text" class="text-input dark-color light-bg" name="{@input_name}" id="new-adr-{@name}" value="">
                    <xsl:if test="@required  = 'required'">
                        <xsl:attribute name="required">required</xsl:attribute>
                    </xsl:if>
                </input>
            </div>
        </xsl:template>

        <xsl:template match="field[@type='text']" mode="new-adrs-fields">
            <div class="form-input" style="margin-left: -11px;margin-right: 7px;">
<!--                <label for="new-adr-{@name}" class="middle-color">
                    <xsl:value-of select="@title" />
                    <xsl:if test="@required  = 'required'">
                        <span class="active-color">*</span>
                    </xsl:if>
                </label>-->
                <textarea class="textarea-input dark-color light-bg" name="{@input_name}" id="new-adr-{@name}" value="{$user-type}">
                    <xsl:if test="@required  = 'required'">
                        <xsl:attribute name="required">required</xsl:attribute>
                    </xsl:if>
                </textarea>
            </div>
        </xsl:template>

        <xsl:template match="field[@type='relation']" mode="new-adrs-fields">
            <div class="form-input">
                <label for="new-adr-{@name}" class="middle-color">
                    <xsl:value-of select="@title" />
                </label>
                <div class="custom-selectbox dark-color light-gradient active-hover">
                    <select name="{@input_name}" id="new-adr-{@name}">
                        <xsl:apply-templates select="values/item" mode="new-adrs-fields" />
                    </select>
                </div>
            </div>
        </xsl:template>

        <xsl:template match="item" mode="new-adrs-fields">
            <option value="{@id}">
                <xsl:value-of select="node()" />
            </option>
        </xsl:template>

</xsl:stylesheet>