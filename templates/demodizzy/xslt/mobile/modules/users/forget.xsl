<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="result[@method = 'forget']">
		<form id="forget" method="post" action="/users/forget_do/">
			<div>
				<label>
					<span>
						<input type="radio" id="forget_login" name="choose_forget" checked="checked" />
						<xsl:text>&login;</xsl:text>
					</span>
				</label>
				<label>
					<span>
						<input type="radio" id="forget_email" name="choose_forget" />
						<xsl:text>E-mail</xsl:text>
					</span>
				</label>
			</div>
			<div>
				<input type="text" name="forget_login" style="margin-left:15px;" />
			</div>
			<div>
				<input type="submit" class="button" value="&forget-button;" />
				<div class="clear" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="result[@method = 'forget_do']">
		<p>
			<xsl:text>&forget-password-note;</xsl:text>
		</p>
	</xsl:template>
</xsl:stylesheet>