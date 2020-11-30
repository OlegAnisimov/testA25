<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">


	<xsl:template match="/result[@method = 'notfound']" priority="1">
            <section class="page-content">
                <xsl:apply-templates select="document('udata://core/navibar')/udata" />

                <div class="page-block page-block-bottom cream-bg grid-container">
                    <div class="content-page grid-100" umi:element-id="{$site-info-id}">
                        <div>
                            <br /><br /><br /><br />
                            <h1 style="font-size: 80px; text-align: center; margin-top:80px; margin-bottom:40px">404</h1>
                            <p style="text-align: center; font-size:40px;margin-bottom:20px">Что-то пошло не так.</p>
                            <br /><br /><br /><br />
                        </div>
                        <div umi:field-name="content_notfound" umi:empty="Контент">
                            <xsl:value-of select="$site-info//property[@name='content_notfound']/value" disable-output-escaping="yes" />
                        </div>
                        <div class="sitemap" style="margin-bottom:60px">
                            <xsl:apply-templates select="document('udata://content/sitemap')" />
                        </div>
                    </div>
                </div>
            </section>
	</xsl:template>
</xsl:stylesheet>