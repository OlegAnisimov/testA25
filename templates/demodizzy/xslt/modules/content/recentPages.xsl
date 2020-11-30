<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="udata[@method = 'getRecentPages']" mode="right" />
	<xsl:template match="udata[@method = 'getRecentPages'][items/item]" mode="right">
		<div class="infoblock">
			<div class="title"><h2>&recent-pages-block;</h2></div>
			<div class="body">
				<div class="in">
					<strong>&recent-pages-text;: </strong>
					<ol class="recentPages">
						<xsl:apply-templates select="items/item" mode="recentPages" />
						<li class="last">
							<a class="button" href="{$lang-prefix}/content/getRecentPages/">&recent-pages-all;</a>
						</li>
					</ol>
				</div>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="item" mode="recentPages">
		<li>
			<a class="first" href="/content/delRecentPage/{@id}">
				<xsl:attribute name="title">delete</xsl:attribute>
				<xsl:text>&#215;</xsl:text>
			</a>
			<a href="{@link}"><xsl:value-of select="@name" /></a>
		</li>
	</xsl:template>
	
	
	<xsl:template match="result[@module = 'content' and @method = 'getRecentPages']" >
		<xsl:apply-templates select="document('udata://content/getRecentPages/')" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'getRecentPages']">
		<p>&recent-pages-empty;</p>
	</xsl:template>
	
	<xsl:template match="udata[@method = 'getRecentPages'][items]">
		<div class="catalog {$catalog}">
            <xsl:call-template name="sorting" />
			<div class="objects">
				<xsl:apply-templates select="items/item" mode="short-view">
					<xsl:with-param name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
					<xsl:with-param name="from_recent" select="true()" />
				</xsl:apply-templates>
			</div>
		</div>
	</xsl:template>

</xsl:stylesheet>