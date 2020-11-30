<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="udata[@module = 'vote'][@method = 'insertlast']">
		<div class="infoblock">
			<div class="title"><h2><xsl:text>&vote;</xsl:text></h2></div>
			<div class="body">
				<div class="in">
					<div class="vote_text">
						<xsl:value-of select="text" />
					</div>
					<xsl:choose>
						<xsl:when test="items/item/@id">
							<form id="postForm_{id}" onsubmit="site.forms.vote(this, {id}); return false;">
								<ul class="vote"><xsl:apply-templates select="items/item" mode="vote" /></ul>
								<input class="button" type="submit" value="&reply;" />
							</form>
						</xsl:when>
						<xsl:when test="items/item/@score">
							<table class="vote">
								<xsl:apply-templates select="items/item" mode="results" />
								<tr>
									<td><xsl:text>&total-votes;: </xsl:text></td>
									<td class="right"><strong><xsl:value-of select="total_posts" /></strong></td>
									<td class="right"></td>
								</tr>
							</table>
						</xsl:when>
						<xsl:otherwise>
							<ul><li><xsl:text>&poll-no-results;</xsl:text></li></ul>
						</xsl:otherwise>
					</xsl:choose>
				</div>
			</div>
		</div>
	</xsl:template>

</xsl:stylesheet>