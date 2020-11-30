<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:date="http://exslt.org/dates-and-times"
                xmlns:udt="http://umi-cms.ru/2007/UData/templates"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                exclude-result-prefixes="xsl date udt xlink">

    <xsl:template match="udata[@module = 'webforms'][@method = 'add']">
        <xsl:param name="form-header">Свяжитесь с нами</xsl:param>
        <xsl:param name="underhead-txt" />
        <xsl:param name="class" select="''" />
        <form class="content-form margin-bottom{$class}" enctype="multipart/form-data" action="{$lang-prefix}/webforms/send_pre/" method="POST">
            <xsl:apply-templates select="items" mode="address" />
            <input type="hidden" name="system_form_id" value="{/udata/@form_id}" />
            <input type="hidden" name="ref_onsuccess" value="{$lang-prefix}/webforms/posted/{/udata/@form_id}/" />
            <div class="with-shadow grid-100 light-bg margin-bottom clearfix">
                <div class="content-page grid-100">
                    <h2 class="bigger-header with-border subheader-font">
                        <xsl:value-of select="$form-header" />
                    </h2>
                    <xsl:if test="$underhead-txt">
                        <p class="underhead-text">
                            <xsl:value-of select="$underhead-txt" />
                        </p>
                    </xsl:if>

                    <xsl:apply-templates select="groups/group" mode="webforms" />

                    <div class="form-input">
                        <xsl:apply-templates select="document('udata://system/captcha/')/udata" />
                    </div>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                    <xsl:text>Отправить</xsl:text>
                    <span>
                        <i class="icon-angle-right" />
                    </span>
                </button>
            </div>
        </form>
    </xsl:template>

    <xsl:template match="group" mode="webforms">
        <xsl:apply-templates select="field" mode="webforms" />
    </xsl:template>

    <xsl:template match="field" mode="webforms">
        <div class="form-input {@name}">
            <xsl:apply-templates select="." mode="webforms_input_type" />
        </div>
    </xsl:template>

    <xsl:template match="field[@name = 'nazvanie_stranicy']" mode="webforms">
        <input type="hidden" name="{@input_name}" value="{$document-result//property[@name='h1']/value}" />
    </xsl:template>

    <xsl:template match="field[@name = 'ssylka_na_stranicu']" mode="webforms">
        <input type="hidden" name="{@input_name}" value="https://{$domain}{$document-result/page/@link}" />
    </xsl:template>

    <xsl:template match="field" mode="webforms_input_type">
        <label for="field-{@name}-{@id}-{/udata/@form_id}" class="middle-color hidden-xs">
            <xsl:value-of select="@title" />
            <xsl:if test="@required = 'required'">
                <span class="active-color">*</span>
            </xsl:if>
        </label>
        <input type="text" class="text-input dark-color light-bg full-width-xs" name="{@input_name}" id="field-{@name}-{@id}-{/udata/@form_id}" placeholder="{@title}">
            <xsl:if test="@name = 'email'">
                <xsl:attribute name="type">email</xsl:attribute>
            </xsl:if>
            <xsl:if test="@required = 'required'">
                <xsl:attribute name="required">required</xsl:attribute>
            </xsl:if>
        </input>
    </xsl:template>

    <xsl:template match="field[@type = 'text' or @type='wysiwyg']" mode="webforms_input_type">
        <label for="field-{@name}-{@id}" class="middle-color hidden-xs">
            <xsl:value-of select="@title" />
            <xsl:if test="@required = 'required'">
                <span class="active-color">*</span>
            </xsl:if>
        </label>
        <textarea name="{@input_name}" class="text-input dark-color light-bg full-width-xs" id="field-{@name}-{@id}" placeholder="{@title}">
            <xsl:if test="@required = 'required'">
                <xsl:attribute name="required">required</xsl:attribute>
            </xsl:if>
        </textarea>
    </xsl:template>

    <xsl:template match="field[@type = 'password']" mode="webforms_input_type">
        <input type="password" name="{@input_name}" value="" class="textinputs"/>
    </xsl:template>

    <xsl:template match="field[@type = 'boolean']" mode="webforms_input_type">
        <input type="hidden" id="{@input_name}" name="{@input_name}" value="" />
        <input onclick="javascript:document.getElementById('{@input_name}').value = this.checked;" type="checkbox" value="1" />
    </xsl:template>

    <xsl:template match="field[@type = 'boolean'][@name = 'agreement']" mode="webforms_input_type">
        <div class="form-input">
            <span class="input-radio-group agreement-chkbox">
                <input type="checkbox" name="{@input_name}" id="agreement-{/udata/@form_id}" value="1" required="required" />
                <label for="agreement-{/udata/@form_id}">Согласен/согласна на обработку моих персональных данных <a target="_blank" href="{$site-info//property[@name='user_agreement_link']/value}" class="active-color dark-hover"><strong>(Ознакомиться с соглашением)</strong></a>
                </label>
            </span>
        </div>
    </xsl:template>

    <xsl:template match="field[@type = 'relation']" mode="webforms_input_type">
        <select name="{@input_name}" class="full-width-xs">
            <xsl:if test="@multiple">
                <xsl:attribute name="multiple">
                    <xsl:text>multiple</xsl:text>
                </xsl:attribute>
            </xsl:if>
            <option value=""></option>
            <xsl:apply-templates select="values/item" mode="webforms_input_type" />
        </select>
    </xsl:template>

    <xsl:template match="field[@type = 'file' or @type = 'img_file' or @type = 'swf_file' or @type = 'video_file']" mode="webforms_input_type">
        <xsl:text> &max-file-size; </xsl:text>
        <xsl:value-of select="@maxsize" />Mb
        <input type="file" name="{@input_name}" class="textinputs"/>
    </xsl:template>

    <xsl:template match="item" mode="webforms_input_type">
        <option value="{@id}">
            <xsl:apply-templates />
        </option>
    </xsl:template>

    <xsl:template match="field" mode="webforms_required" />

    <xsl:template match="field[@required = 'required']" mode="webforms_required">
        <xsl:attribute name="class">
            <xsl:text>required</xsl:text>
        </xsl:attribute>
    </xsl:template>

    <xsl:template match="items" mode="address">
        <xsl:apply-templates select="item" mode="address" />
    </xsl:template>

    <xsl:template match="item" mode="address">
        <input type="hidden" name="system_email_to" value="{@id}" />
    </xsl:template>

    <xsl:template match="items[count(item) &gt; 1]" mode="address">
        <xsl:choose>
            <xsl:when test="count(item[@selected='selected']) != 1">
                <div class="form_element">
                    <label class="required">
                        <span>
                            <xsl:text>Кому отправить:</xsl:text>
                        </span>
                        <select name="system_email_to">
                            <option value=""></option>
                            <xsl:apply-templates select="item" mode="address_select" />
                        </select>
                    </label>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="item[@selected='selected']" mode="address" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="item" mode="address_select">
        <option value="{@id}">
            <xsl:apply-templates />
        </option>
    </xsl:template>

</xsl:stylesheet>
