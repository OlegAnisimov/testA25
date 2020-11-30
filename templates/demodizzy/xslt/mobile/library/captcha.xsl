<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="udata[@module = 'system' and @method = 'captcha']" />
	<xsl:template match="udata[@module = 'system' and @method = 'captcha' and count(url)]">
			<div>
				<label class="required">
					<span>
						<xsl:text>&enter-captcha;:</xsl:text>
					</span>
					<input type="text" name="captcha" class="textinputs captcha" />
					<img src="{url}{url/@random-string}" id="captcha_img" />
					
				</label>
			</div>
	</xsl:template>
</xsl:stylesheet>