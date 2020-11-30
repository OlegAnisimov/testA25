<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="search-form-left-column">
		<form class="search s_top" action="/search/search_do/" method="get">
			<input type="text" value="&search-default-text;" name="search_string" class="search"
				onblur="javascript: if(this.value == '') this.value = '&search-default-text;';"
				onfocus="javascript: if(this.value == '&search-default-text;') this.value = '';" x-webkit-speech="" speech="" />
			<input type="submit" value="Найти" class="button" />
		</form>
	</xsl:template>

</xsl:stylesheet>