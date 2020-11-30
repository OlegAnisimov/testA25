<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="purchasing[@stage = 'required'][@step = 'personal']">
		<xsl:apply-templates select="//steps" />
		<div class="personal_data">
			<form enctype="multipart/form-data" method="post" action="{$lang-prefix}/emarket/purchase/required/personal/do/">
				<xsl:apply-templates select="document(concat('udata://data/getEditForm/', customer-id))" />
				<div>
					<input type="submit" class="button big" value="&continue;" />
				</div>
			</form>
		</div>
		<div class="personal_yandex">
			<a href="http://market.yandex.ru/addresses.xml?callback=http%3A%2F%2F{$domain}%2Femarket%2Fpurchase%2Fautofill%2Fyandex">
				<img src="/templates/demodizzy/images/yandex.png" />
			</a>
		</div>
		
		<div class="clear"></div>
	</xsl:template>
</xsl:stylesheet>