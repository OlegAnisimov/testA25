<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="purchasing[@stage = 'required'][@step = 'personal']">
            <xsl:choose>
                <xsl:when test="$user-type = 'guest'">
                    <xsl:apply-templates select="//steps" mode="checkout-steps" />
                    <div class="box-table">
                        <div class="box grid-50 tablet-grid-50">
                            <h2 class="subheader-font bigger-header margin-bottom">Вход</h2>

                            <form class="content-form" action="/users/login_do/" method="POST">
                                <div class="form-errs">
                                    <xsl:if test="$nolog = '1'">
                                        <p>Ошибка входа! Неверно введен логин или пароль.</p>
                                    </xsl:if>
                                </div>
                                <input type="hidden" name="from_page" value="{$request-uri}"/>
                                <div class="form-input">
                                    <label for="email" class="middle-color">Логин<span class="active-color">*</span></label>
                                    <input type="text" class="text-input dark-color light-bg" name="login" id="login" value="" required="required" />
                                </div>
                                <div class="form-input">
                                    <label for="password" class="middle-color">Пароль<span class="active-color">*</span></label>
                                    <input type="password" class="text-input dark-color light-bg" name="password" id="password" value="" required="required" minlength="3" />
                                </div>
                                <div class="form-submit">
                                    <a href="{$lang-prefix}/users/forget/" class="forgotten-link middle-color dark-hover suffix-10">Забыли пароль?</a>
                                    <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                                        Войти
                                        <span>
                                            <i class="icon-angle-right"></i>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="box grid-50 tablet-grid-50 last">
                            <h2 class="subheader-font bigger-header margin-bottom">Покупка без регистрации</h2>
                            <div class="form-errs">
                                <xsl:apply-templates select="document('udata://system/listErrorMessages')//item" mode="form-errs" />
                            </div>
                            <form class="content-form continue-for" action="{$lang-prefix}/emarket/purchase/required/personal/do" method="POST" enctype="multipart/form-data">
                                <xsl:apply-templates select="document(concat('udata://data/getEditForm/', customer-id))/udata" mode="purch-p-user-data" />
                            </form>
                        </div>
                    </div>

                    <hr />

                    <div class="content-holder align-right">
                        <a href="#" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover continue-but">
                            Продолжить
                            <span>
                                <i class="icon-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:apply-templates select="document('udata://content/redirect/(/emarket/purchase/)/')" />
                </xsl:otherwise>
            </xsl:choose>
	</xsl:template>

        <xsl:template match="item" mode="form-errs">
            <p>
                <xsl:value-of select="node()" />
            </p>
        </xsl:template>

        <xsl:template match="udata"  mode="purch-p-user-data">
            <xsl:apply-templates select="group" mode="purch-p-user-data" />
        </xsl:template>

        <xsl:template match="group"  mode="purch-p-user-data">
            <p><xsl:value-of select="@title" /></p>
            <xsl:apply-templates select="field" mode="purch-p-user-data" />
        </xsl:template>

        <xsl:template match="field"  mode="purch-p-user-data">
            <div class="form-input">
                <label for="field-id-{@field_id}" class="middle-color">
                    <xsl:value-of select="@title" />
                    <xsl:if test="@required = 'required'"><span class="active-color">*</span></xsl:if>
                </label>
                <input class="text-input dark-color light-bg" name="{@input_name}" id="field-id-{@field_id}" value="{.}">
                    <xsl:attribute name="type">
                        <xsl:choose>
                            <xsl:when test="@name = 'email'">email </xsl:when>
                            <xsl:otherwise>text</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:if test="@required = 'required'">
                        <xsl:attribute name="required">required</xsl:attribute>
                    </xsl:if>
                </input>
            </div>
        </xsl:template>
</xsl:stylesheet>