<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

        <xsl:variable name="cart" select="document('udata://emarket/cart/')/udata" />

	<xsl:template match="result[@module = 'emarket' and @method = 'purchasing_one_step']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <!-- Content  -->
                    <div class="content-page checkout-page grid-100">
                        <xsl:apply-templates select="udata" mode="pos-cont" />
                    </div><!-- END Content  -->
                </div><!-- END Page block  -->
            </section>
	</xsl:template>

        <xsl:template match="udata[error]" mode="pos-cont">
            <div class="box-table">
                <div class="box grid-100 tablet-grid-100 last">
                    <p class="pos-errs"><xsl:value-of select="error" /></p>
                </div>
            </div>
        </xsl:template>

<!--        <xsl:template match="udata[onestep/customer]" mode="pos-cont">
     
            <xsl:call-template name="pos-steps">
                <xsl:with-param name="active-step" select="'1'" />
            </xsl:call-template>

            <div class="box-table">
                <div class="box grid-50 tablet-grid-50">
                    <h2 class="subheader-font bigger-header margin-bottom">Авторизация</h2>

                    <div class="form-errs">
                        <xsl:if test="$nolog = '1'">
                            <p>Ошибка входа! Неверно введен логин или пароль.</p>
                        </xsl:if>
                    </div>
                    <form class="content-form" action="/users/login_do/" method="POST">
                        <input type="hidden" name="from_page" value="{$request-uri}"/>
                        <div class="form-input">
                            <label for="login" class="middle-color">Логин (ваш E-mail)<span class="active-color">*</span></label>
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
                    <h2 class="subheader-font bigger-header margin-bottom">Регистрация</h2>
                    <xsl:apply-templates select="document('udata://users/registrate')" mode="pos-register" />
                </div>
            </div>
        </xsl:template>-->

        <xsl:template match="udata" mode="pos-cont">
<!--            <xsl:call-template name="pos-steps">
                <xsl:with-param name="active-step" select="'2'" />
            </xsl:call-template>-->
            <form class="content-form" action="/emarket/saveInfo_custom" method="POST">
                <xsl:if test="not($cart/steps/item/@name = 'Доставка')">
                    <input type="hidden" name="delivery-address" value="delivery_{onestep/delivery/delivery/items/item[@type-class-name = 'self']/@id}" data-price="0"/>
                </xsl:if>
               
                    <div id="pos-step-2" class="step-tab active">
                       
                        <div class="box-table">
                            <div class="box grid-50 tablet-grid-50">
                               <div class="delivery-method-cont"></div>
                            </div>
                            <div class="box grid-50 tablet-grid-50 last">
                                <h2 class="subheader-font bigger-header margin-bottom">Способ оплаты</h2>
                                <xsl:apply-templates select="onestep/payment/items/item" mode="purchase-payment-method" />
                            </div>
                           
                            
                        </div>
                        <div class="box-table">
                            <xsl:if test="not(onestep/customer)">
                                <div class="box grid-50 tablet-grid-50">
                                    <h2 class="subheader-font bigger-header margin-bottom">Информация о покупателе</h2>
                                    <ul>
                                        <xsl:apply-templates select="$cart/customer//group[@name = 'short_info']/property[@name != 'login']" mode="summary-customer" />
                                        <xsl:apply-templates select="$cart/customer//property[@name = 'e-mail']" mode="summary-customer" />
                                    </ul>
                                </div>
                            </xsl:if>
                            <xsl:if test="$cart/steps/item/@name = 'Доставка'">
                                <div class="box grid-50 tablet-grid-50 last delivery_block_hid" style="padding-right: 2px;">
                                    <h2 class="subheader-font bigger-header margin-bottom">Адрес доставки</h2>

                                    <!--<xsl:apply-templates select="onestep/delivery/delivery/items/item" mode="purchase-delivery-choose" />-->
                                    <xsl:apply-templates select="onestep/delivery/items/item" mode="purchase-delivery-adrs" />
                                    <div class="form-input radio-input">
                                        <input type="radio" name="delivery-address" class="del-adr-new" id="adr-new" value="new" data-id="{onestep/delivery/@type-id}" required="required"/>
                                        <label for="adr-new" class="middle-color dark-hover">Новый адрес доставки</label>
                                    </div>
                                    <div class="new-adr-cont margin-top">
                                        <xsl:if test="$user-type != 'guest'">
                                            <xsl:attribute name="class">new-adr-cont margin-top not_gest_class</xsl:attribute>
                                        </xsl:if>
                                    </div>
                                </div>
                            </xsl:if>
                        </div>

                        
                        <xsl:if test="onestep/customer">
                             <xsl:apply-templates select="document(concat('udata://data/getEditForm/', onestep/customer/@id))" mode="cust-reg"/>
                        </xsl:if>

<!--                        <div class="content-holder align-right">
                            <a href="#" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover continue-but goto-step pos-validate-adr" data-step="3">
                                <xsl:if test="not($cart/steps/item/@name = 'Оплата')">
                                    <xsl:attribute name="class">button-normal button-with-icon light-color active-gradient dark-gradient-hover continue-but goto-step pos-validate-adr</xsl:attribute>
                                </xsl:if>
                                <xsl:text>Продолжить</xsl:text>
                                <span>
                                    <i class="icon-angle-right"></i>
                                </span>
                            </a>
                        </div>-->
                    </div>
               

                <xsl:if test="not($cart/steps/item/@name = 'Оплата')">
                    <input type="hidden" name="payment-id" value="{onestep/payment/items/item[@type-name = 'courier']/@id}" />
                </xsl:if>

<!--                <xsl:if test="$cart/steps/item/@name = 'Оплата'">
                    <div id="pos-step-3" class="step-tab">
                        <xsl:if test="not($cart/steps/item/@name = 'Доставка')">
                            <xsl:attribute name="class">step-tab active</xsl:attribute>
                            <xsl:attribute name="id">pos-step-2</xsl:attribute>
                        </xsl:if>
                        <div class="box-table">
                            <div class="box grid-100 tablet-grid-100 last">
                                <h2 class="subheader-font bigger-header margin-bottom">Способ оплаты</h2>
                                <xsl:apply-templates select="onestep/payment/items/item" mode="purchase-payment-method" />
                            </div>
                        </div>

                        <hr />

                        <div class="content-holder align-right">
                            <a href="#" class="pull-left button-normal light-color middle-gradient dark-gradient-hover goto-step" data-step="2">
                                <b class="hide-on-desktop hide-on-tablet">Назад</b>
                                <b class="hide-on-mobile">К предыдущему шагу</b>
                            </a>

                            <a href="#" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover continue-but goto-step pos-validate-adr" data-step="4">
                                <xsl:if test="not($cart/steps/item/@name = 'Доставка')">
                                    <xsl:attribute name="data-step">3</xsl:attribute>
                                    <xsl:attribute name="class">button-normal button-with-icon light-color active-gradient dark-gradient-hover continue-but goto-step</xsl:attribute>
                                </xsl:if>
                                Продолжить
                                <span>
                                    <i class="icon-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </xsl:if>-->


                <div id="pos-step-4" class="step-tab">
                    <hr/>
<!--                    <xsl:if test="not($cart/steps/item/@name = 'Доставка') and $cart/steps/item/@name = 'Оплата'">
                        <xsl:attribute name="id">pos-step-3</xsl:attribute>
                    </xsl:if>
                    <xsl:if test="$cart/steps/item/@name = 'Доставка' and not($cart/steps/item/@name = 'Оплата')">
                        <xsl:attribute name="id">pos-step-3</xsl:attribute>
                    </xsl:if>
                    <xsl:if test="not($cart/steps/item/@name = 'Доставка') and not($cart/steps/item/@name = 'Оплата')">
                        <xsl:attribute name="class">step-tab active</xsl:attribute>
                        <xsl:attribute name="id">pos-step-2</xsl:attribute>
                    </xsl:if>-->
                    <xsl:apply-templates select="$cart" mode="summary-info" />
                </div>
            </form>
        </xsl:template>

        <xsl:template match="udata" mode="pos-register">
            <div class="form-errs">
                <xsl:apply-templates select="document('udata://system/listErrorMessages')//item" mode="form-errs" />
            </div>
            <form class="content-form continue-for" id="registrate" enctype="multipart/form-data" method="post" action="{$lang-prefix}/users/pre_registrate_do/" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
                <div class="form-input">
                    <label for="email" class="middle-color">Логин (ваш E-mail)<span class="active-color">*</span></label>
                    <input type="email" class="text-input dark-color light-bg" name="email" id="email" value="" required="required" />
                </div>
                <div class="form-input">
                    <label for="password" class="middle-color">Пароль<span class="active-color">*</span></label>
                    <input type="password" class="text-input dark-color light-bg" name="password" id="password" value="" required="required" minlength="3" />
                </div>
                <div class="form-input">
                    <label for="password_confirm" class="middle-color">Повторите пароль<span class="active-color">*</span></label>
                    <input type="password" class="text-input dark-color light-bg" name="password_confirm" id="password_confirm" value="" required="required" minlength="3" />
                </div>

                <xsl:apply-templates select="document(@xlink:href)" mode="pos-reg-fields" />

                <div class="form-input">
                    <span class="input-radio-group agreement-chkbox">
                        <input type="checkbox" name="agreement" id="agreement" value="1" checked="checked" required="required" />
                        <label for="agreement">Согласен/согласна на обработку моих персональных данных <a target="_blank" href="{$site-info//property[@name='user_agreement_link']/value}" class="active-color dark-hover">
                                <strong>(Ознакомиться с соглашением)</strong>
                            </a>
                        </label>
                    </span>
                </div>
                <div class="form-submit">
                    <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                        Зарегистрироваться
                        <span>
                            <i class="icon-angle-right"></i>
                        </span>
                    </button>
                </div>
            </form>
        </xsl:template>

        <xsl:template match="udata"  mode="pos-reg-fields">
            <xsl:apply-templates select="group" mode="pos-reg-fields" />
        </xsl:template>

        <xsl:template match="group" mode="pos-reg-fields">
            <p><xsl:value-of select="@title" /></p>
            <xsl:apply-templates select="field" mode="new-adrs-fields" />
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
            <div class="form-input">
                <label for="new-adr-{@name}" class="middle-color">
                    <xsl:value-of select="@title" />
                </label>
                <xsl:if test="@required  = 'required'">
                    <span class="active-color">*</span>
                </xsl:if>
                <textarea class="textarea-input dark-color light-bg" name="{@input_name}" id="new-adr-{@name}" value="">
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

        <xsl:template name="pos-steps">
            <xsl:param name="active-step" />
            <xsl:variable name="steps" select="document('udata://emarket/cart/')/udata/steps" />
            <div class="checkout-progress progress pos-steps-crumbs">
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
            </div>
            <hr class="hide-on-mobile" />
        </xsl:template>

        <xsl:template match="item" mode="pos-steps-step-new">
            <xsl:param name="active" />
            <xsl:variable name="number" select="position()+1" />
            <xsl:variable name="header" select="@name" />
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