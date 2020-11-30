<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="udata[@module = 'system' and @method = 'captcha']" />

	<xsl:template match="udata[@module = 'system' and @method = 'captcha' and count(url) > 0]">
		<div>
			<label class="required">
				<span>
					<xsl:text>&enter-captcha;:</xsl:text>
				</span>
				<input type="text" name="captcha" class="textinputs captcha" />
				<img src="{url}{url/@random-string}&amp;lang_id={url/@lang_id}" id="captcha_img" />
				<span id="captcha_reset">
					<xsl:text>&reset-captcha;</xsl:text>
				</span>
			</label>
		</div>
	</xsl:template>

	<xsl:template match="udata[@module = 'system' and @method = 'captcha' and count(recaptcha-url) > 0]">
		<div>
			<label>
				<script src='{recaptcha-url}?hl=ru'></script>
				<div class="{recaptcha-class}" data-sitekey="{recaptcha-sitekey}"></div>
			</label>
		</div>
	</xsl:template>

</xsl:stylesheet>
