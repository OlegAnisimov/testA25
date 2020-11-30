<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:output encoding="utf-8" method="html" indent="yes"/>
        <xsl:param name="active-step" />

        <xsl:template match="/">
            <xsl:variable name="steps" select="document('udata://emarket/cart/')/udata/steps" />
            <xsl:call-template name="pos-steps-step">
                <xsl:with-param name="active" select="$active-step" />
                <xsl:with-param name="number" select="'1'" />
                <xsl:with-param name="header" select="'Авторизация'" />
                <xsl:with-param name="class" select="''" />
            </xsl:call-template>

            <xsl:apply-templates select="$steps/item[position() != '1' and position() != last()]" mode="pos-steps-step-new">
                <xsl:with-param name="active" select="$active-step" />
            </xsl:apply-templates>

            <xsl:call-template name="pos-steps-step">
                <xsl:with-param name="active" select="$active-step" />
                <xsl:with-param name="number" select="count($steps/item)" />
                <xsl:with-param name="header" select="'Сводка'" />
                <xsl:with-param name="class" select="''" />
            </xsl:call-template>

            <xsl:call-template name="pos-steps-step">
                <xsl:with-param name="active" select="$active-step" />
                <xsl:with-param name="number" select="count($steps/item) + 1" />
                <xsl:with-param name="header" select="'Завершение'" />
                <xsl:with-param name="class" select="' last'" />
            </xsl:call-template>
        </xsl:template>

        <xsl:template match="item" mode="pos-steps-step-new">
            <xsl:param name="active" />
            <xsl:variable name="number" select="position()+1" />
            <xsl:variable name="header">
                <xsl:choose>
                    <xsl:when test="@name = 'header-emarket-payment'">Оплата</xsl:when>
                    <xsl:when test="@name = 'header-emarket-delivery'">Доставка</xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="@name" />
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            <div>
                <xsl:attribute name="class">
                    <xsl:text>progress-step</xsl:text>
                    <xsl:choose>
                        <xsl:when test="$active = $number">
                            <xsl:text> active-color current-step</xsl:text>
                        </xsl:when>
                        <xsl:when test="$active &gt; $number">
                            <xsl:text> completed-step</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text> middle-color</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:if test="$number = '5'">
                        <xsl:text> last</xsl:text>
                    </xsl:if>
                </xsl:attribute>

                <xsl:choose>
                    <xsl:when test="$active = $number">
                        <a class="step-outer">
                            <span class="step-inner">
                                <span class="light-color active-bg">
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:when>
                    <xsl:when test="$active &gt; $number">
                        <a href="#" class="step-outer dark-color active-hover goto-step" data-step="{$number}">
                            <span class="step-inner">
                                <span class="step-line active-bg"></span>
                                <span class="light-color active-bg">
                                    <i class="icon-ok"></i>
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <a class="step-outer">
                            <span class="step-inner">
                                <span>
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </xsl:template>

        <xsl:template name="pos-steps-step">
            <xsl:param name="active" />
            <xsl:param name="number" />
            <xsl:param name="header" />
            <xsl:param name="class" />

            <div>
                <xsl:attribute name="class">
                    <xsl:text>progress-step</xsl:text>
                    <xsl:choose>
                        <xsl:when test="$active = $number">
                            <xsl:text> active-color current-step</xsl:text>
                        </xsl:when>
                        <xsl:when test="$active &gt; $number">
                            <xsl:text> completed-step</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text> middle-color</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:value-of select="$class" />
                </xsl:attribute>

                <xsl:choose>
                    <xsl:when test="$active = $number">
                        <a class="step-outer">
                            <span class="step-inner">
                                <span class="light-color active-bg">
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:when>
                    <xsl:when test="$active &gt; $number">
                        <a href="#" class="step-outer dark-color active-hover goto-step" data-step="{$number}">
                            <span class="step-inner">
                                <span class="step-line active-bg"></span>
                                <span class="light-color active-bg">
                                    <i class="icon-ok"></i>
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <a class="step-outer">
                            <span class="step-inner">
                                <span>
                                    <xsl:value-of select="$number" />
                                </span>
                            </span>
                            <xsl:value-of select="$header" />
                        </a>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </xsl:template>

</xsl:stylesheet>