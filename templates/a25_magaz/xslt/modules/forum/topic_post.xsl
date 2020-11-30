<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns="http://www.w3.org/1999/xhtml"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="udata[@module = 'forum'][@method = 'topic_post']" />

	<xsl:template match="udata[@module = 'forum'][@method = 'topic_post'][action]">
		<a name="add"></a>
		<form method="post" action="{action}" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
			<xsl:choose>
				<xsl:when test="$user-type = 'guest'">
					<div class="form_element">
						<label class="required">
							<span>&comment-you-name;:</span>
							<input type="text" name="nickname" class="textinputs" />
						</label>
					</div>
					<div class="form_element">
						<label class="required">
							<span>&comment-you-email;:</span>
							<input type="text" name="email" class="textinputs" />
						</label>
					</div>
				</xsl:when>
				<xsl:otherwise>
					<input type="hidden" name="login" disabled="disabled" />
				</xsl:otherwise>
			</xsl:choose>
			<div class="form_element">
				<label class="required">
					<span>&comment-title;:</span>
					<input type="text" name="title" class="textinputs" />
				</label>
			</div>
			<div class="form_element">
				<label class="required">
					<span>&comments;:</span>
					<textarea name="body"></textarea>
				</label>
			</div>
			<div class="form_element">
				<xsl:apply-templates select="document('udata://system/captcha/')/udata" />
			</div>
			<div class="form_element">
				<input type="submit" class="button" value="&forum-topic-post;" />
			</div>
		</form>
	</xsl:template>

</xsl:stylesheet>