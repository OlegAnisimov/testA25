<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="result[@module = 'blogs20'][@method = 'blog']">
		<xsl:variable name="ownerId" select="document(concat('udata://blogs20/viewBlogAuthors/',$document-page-id,'/'))/udata/users/item[@is_owner = '1']/@user_id" />
		<xsl:variable name="avatar" select="document(concat('uobject://',$ownerId,'.avatar'))/udata/property/value/@path" />
		<div class="blog">
			<div id="author_info">
				<div class="blog_feed">
					<a href="{$lang-prefix}/data/atom/{$document-page-id}" target="_blank">atom</a>
					<a href="{$lang-prefix}/data/rss/{$document-page-id}" target="_blank">rss</a>
				</div>
				<xsl:apply-templates select="document(concat('uobject://',$ownerId))/udata" mode="author_info" />
				<xsl:if test="not($ownerId)"><h1><xsl:value-of select="page/name" /></h1></xsl:if>
				<div class="clear" />
			</div>
			<xsl:apply-templates select="document(concat('udata://blogs20/getPostsList/',$document-page-id))/udata" />
			<xsl:if test="$ownerId = $user-id or $user-type = 'sv'">
				<div class="add_form">
					<form name="frm_addblogmsg" method="post" action="/blogs20/postAdd/{$document-page-id}/" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
						<div class="form_element">
							<label class="required">
								<span>&comment-title;:</span>
								<input type="text" name="title" class="textinputs" />
							</label>
						</div>
						<div class="form_element">
							<label class="required">
								<span>&comment-message;:</span>
								<textarea name="content"></textarea>
							</label>
						</div>
						<div class="form_element">
							<label>
								<span>&comment-tags;:</span>
								<input type="text" name="tags" class="textinputs" />
							</label>
						</div>
						<div class="form_element">
							<input type="submit" class="button" value="&blog-addpost;" />
						</div>
					</form>
				</div>
			</xsl:if>
		</div>
	</xsl:template>

	<xsl:template match="udata" mode="author_info">
		<h1>
			<xsl:choose>
				<xsl:when test="//group[@name = 'short_info']">
					<xsl:value-of select="//property[@name = 'fname']/value" />&#160;<xsl:value-of select="//property[@name = 'lname']/value" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="//property[@name = 'login']/value" />
				</xsl:otherwise>
			</xsl:choose>
		</h1>
		<div class="prof"><xsl:value-of select="//property[@name = 'jobpost']/value" /></div>
	</xsl:template>

</xsl:stylesheet>