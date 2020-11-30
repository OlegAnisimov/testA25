<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="result[@method = 'forget']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <!-- Page block content  -->
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="sidebar-shadow push-25"></div>
                    <!-- Sidebar  -->
                    <div class="sidebar grid-25 cream-gradient transition-all" id="sidebar-mobile">
                        <!-- Sidebar submenu box -->
                        <div class="sidebar-box sidebar-top cream-gradient">
                            <xsl:apply-templates select="document('udata://menu/draw/(content_sidebar)')/udata" mode="content-p-sidebar" />
                        </div><!-- END Sidebar submenu box -->
                    </div><!-- END Sidebar  -->

                    <!-- Content  -->
                    <div class="content-with-sidebar grid-75">
                        <form class="content-form margin-bottom" enctype="multipart/form-data" action="{$lang-prefix}/users/forget_do/" method="POST" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
                            <div class="with-shadow grid-100 light-bg margin-bottom clearfix">
                                <div class="content-page grid-100">
                                    <h2 class="bigger-header with-border subheader-font">
                                        <xsl:text>Данные для восстановления</xsl:text>
                                    </h2>
                                    <div class="form-input">
                                        <label for="email" class="middle-color">Логин (ваш E-mail)<span class="active-color">*</span></label>
                                        <input type="email" class="text-input dark-color light-bg" name="forget_email" id="email" value="" required="required" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-submit">
                                <button type="submit" class="button-normal button-with-icon light-color active-gradient dark-gradient-hover">
                                    Выслать пароль
                                    <span>
                                        <i class="icon-angle-right"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div><!-- END Content  -->
                </div> <!-- END Page block  -->
            </section>
	</xsl:template>

	<xsl:template match="result[@method = 'forget_do']">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />
                <div class="page-block page-block-bottom cream-bg grid-container">
                    <p>
                        <xsl:text>&registration-activation-note;</xsl:text>
                    </p>
                </div>
            </section>
	</xsl:template>
</xsl:stylesheet>