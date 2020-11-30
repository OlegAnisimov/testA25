<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="result[@method = 'registrate']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="sidebar-shadow push-25"></div>
                    <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <xsl:apply-templates select="document('udata://menu/draw/(content_sidebar)')/udata" mode="content-p-sidebar" />
                        </div>
                    </div>

                    <div class="content-with-sidebar grid-75">
                        <xsl:apply-templates select="document('udata://users/registrate')" />
                    </div>
                </div>
            </section>
	</xsl:template>

	<xsl:template match="udata[@method = 'registrate']">
            <form class="content-form margin-bottom" enctype="multipart/form-data" action="{$lang-prefix}/users/pre_registrate_do/" method="POST" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);" id="reg-page-form">
                <div class="with-shadow grid-100 light-bg margin-bottom clearfix">
                    <div class="content-page grid-100">
                        <h2 class="bigger-header with-border subheader-font">
                            <xsl:text>Данные для регистрации</xsl:text>
                        </h2>
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
                                <label for="agreement">Согласен/согласна на обработку моих персональных данных <a target="_blank" href="{$site-info//property[@name='user_agreement_link']/value}" class="active-color dark-hover"><strong>(Ознакомиться с соглашением)</strong></a></label>
                            </span>
                        </div>
                    </div>
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

	<xsl:template match="result[@method = 'registrate_done']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <xsl:apply-templates select="document('udata://users/registrate_done')/udata"/>
                </div>
            </section>
	</xsl:template>

	<xsl:template match="udata[@method = 'registrate_done']">
            <xsl:choose>
                <xsl:when test="result = 'without_activation'">
                    <h4>
                        <xsl:text>&registration-done;</xsl:text>
                    </h4>
                </xsl:when>
                <xsl:when test="result = 'error'">
                    <h4>
                        <xsl:text>&registration-error;</xsl:text>
                    </h4>
                </xsl:when>
                <xsl:when test="result = 'error_user_exists'">
                    <h4>
                        <xsl:text>&registration-error-user-exists;</xsl:text>
                    </h4>
                </xsl:when>
                <xsl:otherwise>
                    <h4>
                        <xsl:text>&registration-done;</xsl:text>
                    </h4>
                    <p>
                        <xsl:text>&registration-activation-note;</xsl:text>
                    </p>
                </xsl:otherwise>
            </xsl:choose>
	</xsl:template>


	<xsl:template match="result[@method = 'activate']">
            <xsl:variable name="activation-errors" select="document('udata://users/activate')/udata/error" />
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <xsl:choose>
                        <xsl:when test="count($activation-errors)">
                            <xsl:apply-templates select="$activation-errors" />
                        </xsl:when>
                        <xsl:otherwise>
                            <p>
                                <xsl:text>&account-activated;</xsl:text>
                            </p>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
            </section>
	</xsl:template>


	<!-- User settings -->
	<xsl:template match="result[@method = 'settings']">
            <xsl:apply-templates select="document('udata://users/settings')" mode="usr-prof" />
	</xsl:template>

        <xsl:template match="udata" mode="usr-prof">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="sidebar-shadow push-25"></div>

                    <!-- Sidebar  -->
                    <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                        <!-- Sidebar submenu box -->
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <nav class="submenu">
                                <ul class="expandable-menu">
                                    <li class="align-right back">
                                        <a href="#sidebar-mobile" class="dark-color active-hover click-slide">
                                            <i class="icon-chevron-right"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{$lang-prefix}/users/settings" class="dark-color active-hover selected">Профиль</a>
                                    </li>
                                    <li class="sidebar-divider"></li>
                                    <li>
                                        <a href="{$lang-prefix}/emarket/personal" class="dark-color active-hover">История заказов</a>
                                    </li>
                                    <li class="sidebar-divider"></li>
                                    <li>
                                        <a href="{$lang-prefix}/users/logout/" class="dark-color active-hover">Выход</a>
                                    </li>
                                </ul>
                            </nav>
                        </div><!-- END Sidebar submenu box -->
                    </div><!-- END Sidebar  -->

                    <!-- Content  -->
                    <div class="content-with-sidebar grid-75">
                        <div class="content-page grid-100">
                            <div class="my-profile-header middle-color clearfix">
                                <div class="my-profile-info">
                                    <xsl:variable name="user-info" select="document(concat('uobject://', user_id))/udata" />
                                    <h1 class="active-color header-font">
                                        <span class="dark-color">Здравствуйте, </span>
                                        <xsl:value-of select="concat($user-info//property[@name='fname']/value, ' ',$user-info//property[@name='lname']/value)" />
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="with-shadow grid-100 light-bg">
                            <xsl:apply-templates select="document(concat('udata://data/getEditForm/', user_id))/udata" mode="usr-sets-form" />
                        </div>
                    </div><!-- END Content  -->
                </div> <!-- END Page block  -->
            </section>
        </xsl:template>

        <xsl:template match="udata" mode="usr-sets-form">
            <div class="content-page grid-100">
                <form class="content-form margin-bottom" action="{$lang-prefix}/users/settings_do/" method="POST" enctype="multipart/form-data">
                    <xsl:apply-templates select="group" mode="usr-sets-form" />

                    <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                        Сохранить
                        <span>
                            <i class="icon-angle-right"></i>
                        </span>
                    </button>

                </form>
            </div>

            <div class="content-page grid-100">
                <form class="content-form margin-bottom" action="{$lang-prefix}/users/settings_do/" method="POST" enctype="multipart/form-data">
                    <h2 class="bigger-header with-border subheader-font">Изменение пароля</h2>
                    <div class="form-input">
                        <label for="usr-set-f-pass" class="middle-color">
                            <xsl:text>Новый пароль</xsl:text>
                            <span class="active-color">*</span>
                        </label>
                        <input class="text-input dark-color light-bg" type="password" name="{@input_name}" id="usr-set-f-pass" value="" required="required" minlength="3" />
                    </div>
                    <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                        Изменить
                        <span>
                            <i class="icon-angle-right"></i>
                        </span>
                    </button>
                </form>
            </div>
        </xsl:template>

        <xsl:template match="group" mode="usr-sets-form">
            <h2 class="bigger-header with-border subheader-font">
                <xsl:value-of select="@title" />
            </h2>
            <xsl:apply-templates select="field" mode="usr-sets-form" />
        </xsl:template>

        <xsl:template match="field" mode="usr-sets-form">
            <div class="form-input">
                <label for="usr-set-f-{@name}" class="middle-color">
                    <xsl:value-of select="@title" />
                    <xsl:if test="@required = 'required'">
                        <span class="active-color">*</span>
                    </xsl:if>
                </label>
                <input class="text-input dark-color light-bg" name="{@input_name}" id="usr-set-f-{@name}" value="{.}">
                    <xsl:attribute name="type">
                        <xsl:choose>
                            <xsl:when test="@name = 'phone'">tel</xsl:when>
                            <xsl:when test="@name = 'email'">email</xsl:when>
                            <xsl:otherwise>text</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:if test="@required = 'required'">
                        <xsl:attribute name="required">required</xsl:attribute>
                    </xsl:if>
                </input>
            </div>
        </xsl:template>

<!--	<xsl:template match="udata[@method = 'settings']">
		<xsl:variable name="csrf_token" select="/result/@csrf" />

		<form enctype="multipart/form-data" method="post" action="{$lang-prefix}/users/settings_do/" id="con_tab_profile">
			<input type="hidden" name="csrf" value="{$csrf_token}" />
			<div>
				<label>
					<span>
						<xsl:text>&login;:</xsl:text>
					</span>
					<input type="text" name="login" class="textinputs" disabled="disabled" value="{$user-info//property[@name = 'login']/value}" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&current-password;:</xsl:text>
					</span>
					<input type="password" name="current-password" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&password;:</xsl:text>
					</span>
					<input type="password" name="password" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&password-confirm;:</xsl:text>
					</span>
					<input type="password" name="password_confirm" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&e-mail;:</xsl:text>
					</span>
					<input type="text" name="email" class="textinputs" value="{$user-info//property[@name = 'e-mail']/value}" />
				</label>
			</div>

			<xsl:apply-templates select="document(concat('udata://data/getEditForm/', $user-id))" />

			<div>
				<input type="submit" class="button" value="&save-changes;" />
			</div>
		</form>
	</xsl:template>-->
</xsl:stylesheet>
