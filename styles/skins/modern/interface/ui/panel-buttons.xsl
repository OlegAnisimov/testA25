<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://common">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:variable name="edition" select="/result/@edition" />
	<xsl:variable name="result-user-id" select="/result/@user-id" />

	<!-- Шаблон колонтитула административной панели -->
	<xsl:template name="panel-buttons">
		<xsl:param name="user-group" select="document(concat('uobject://',$result-user-id))//property[@name = 'groups']/value" />

        <div class="website collapsed" title="">
            <a id="site_link" target="_blank" href="{$lang-prefix}/">
				<i class="small-ico i-home" title="&to-site;" /><span><xsl:text>&to-site;</xsl:text></span>
            </a>
        </div>

        <div class="cache collapsed">
            <a id="cache" href="/admin/config/cache/" >
                <i class="small-ico i-cache" title="">
                    <xsl:attribute name="title">
                        <xsl:call-template name="cache-message" />
                    </xsl:attribute>
                </i>
                <span>
					<xsl:call-template name="cache-message" />
                </span>
            </a>
        </div>

        <div id="quickpanel" class="website collapsed">
            <a href="#" id="note">
				<i class="small-ico i-note" title="&js-panel-note;" />
                <span><xsl:text>&js-panel-note;</xsl:text></span>
            </a>
        </div>

        <div class="helper collapsed" >
            <a id="ask_support" href="javascript:askSupport();">
				<i class="small-ico i-help" title="&ask_support;" />
                <span><xsl:text>&ask_support;</xsl:text></span>
            </a>
        </div>

		<xsl:variable name="isLastVersion" select="document('udata://autoupdate/isLastVersion')/udata" />

		<xsl:if test="$isLastVersion = ''">
			<xsl:variable name="lastVersion" select="document('udata://autoupdate/getLastVersion')/udata" />
			<xsl:variable name="lastRevision" select="document('udata://autoupdate/getLastRevision')/udata" />

			<div class="website collapsed">
				<a id="autoupdateButton" href="" data-version="{$lastVersion}" data-revision="{$lastRevision}">
					<i class="small-ico i-update" title="&update-available;" />
					<span>
						<xsl:text>&update-available;</xsl:text>
					</span>
				</a>
			</div>
		</xsl:if>

        <xsl:variable name="support_end_date"
                      select="document('udata://autoupdate/getSupportEndDate')/udata" />

        <xsl:variable name="supportEndDateString"
                      select="concat($support_end_date/date/@day, ' ', $support_end_date/date/@month_rus, ' ', $support_end_date/date/@year)" />

        <xsl:choose>
            <xsl:when test="$support_end_date/error" />
            <xsl:otherwise>
                <div class="website collapsed">
                    <a class="license-prolongation" target="__blank" href="https://umi-cms.ru/support/update/" >

                        <i class="support-date  {$support_end_date/date/@status}" style=" display: inline-block;height: 15px;width: 20px;" title="">
                            <xsl:attribute name="title">
                                <xsl:text>&support-acting-until-text; </xsl:text><xsl:value-of select="$supportEndDateString" />
                            </xsl:attribute>
                        </i>
                        <span style="margin-left: 7px;">&support-acting-until-text;
                        <xsl:value-of select="$supportEndDateString" />
                        </span>
                    </a>
                </div>
            </xsl:otherwise>
        </xsl:choose>
	</xsl:template>

	<!-- Шаблон кнопки кэша -->
	<xsl:template name="cache-message">
		<xsl:choose>
			<xsl:when test="$cache-enabled = 0">
				<xsl:text>&cache-disabled-message;</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>&cache-enabled-message;</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<!-- Шаблон информации о триальной версии системы -->
	<xsl:template match="trial" mode="trial-days-left">
		<div class="website collapsed">
			<a href="https://umi-cms.ru/buy/" target="_blank" style="margin-left:10px;">
				<i class="small-ico i-reminder" />
				<span>
					<xsl:apply-templates select="@daysleft" mode="prefix" />
					<xsl:text>: </xsl:text>
					<span class="days-left">
						<xsl:apply-templates select="@daysleft" />
						<xsl:text> </xsl:text>
						<xsl:apply-templates select="@daysleft" mode="suffix" />
					</span>
					<xsl:text>. </xsl:text>
				</span>
			</a>
		</div>
	</xsl:template>

	<!-- Шаблоны вариаций слова "День" -->
	<xsl:template match="@daysleft" mode="suffix">&days-left-number1;</xsl:template>
	<xsl:template match="@daysleft[not(. &gt; 10 and . &lt; 20) and ((. mod 10) = 2 or (. mod 10) = 3 or (. mod 10) = 4)]"
				  mode="suffix">
		&days-left-number2;
	</xsl:template>
	<xsl:template match="@daysleft[not(. &gt; 10 and . &lt; 20) and ((. mod 10) = 1)]" mode="suffix">&days-left-number3;</xsl:template>

	<!-- Шаблоны вариаций слова "Осталось" -->
	<xsl:template match="@daysleft" mode="prefix">&days-left1;</xsl:template>
	<xsl:template match="@daysleft[((. mod 10) = 1)]" mode="prefix">&days-left2;</xsl:template>

</xsl:stylesheet>
