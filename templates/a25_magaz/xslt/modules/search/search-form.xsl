<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template name="search-form-left-column">
		<form class="search" action="/search/search_do/" method="get">
			<input type="text" value="&search-default-text;" name="search_string" class="textinputs" onblur="javascript: if(this.value == '') this.value = '&search-default-text;';" onfocus="javascript: if(this.value == '&search-default-text;') this.value = '';"  x-webkit-speech="" speech="" />
			<div class="search-result" />

			<script id="search-result-template" type="text/template">
				<![CDATA[
				<% _.each(typesList, function(type){ %>
					<div class="search-block">
						<h3><%= type.module ? type.module : '&nbsp;' %></h3>
						<ul>
							<% _.each(type.elements, function(page){ %>
								<li><a href="<%= page.link %>"><%= page.context %></a></li>
							<% }); %>
						</ul>
					</div>
        		<% }); %>

				<div class="search-all-result">
					<span class="search-all-result"><%= allResults %></span>
				</div>
				]]>
			</script>
		</form>
	</xsl:template>
</xsl:stylesheet>