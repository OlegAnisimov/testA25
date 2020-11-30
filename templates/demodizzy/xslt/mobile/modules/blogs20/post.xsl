<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet	version="1.0"
				xmlns="http://www.w3.org/1999/xhtml"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt">

	<xsl:template match="result[@module = 'blogs20'][@method = 'post']">
		<xsl:variable name="publish_time" select="//property[@name = 'publish_time']/value/@unix-timestamp" />
		<xsl:variable name="tags" select="//property[@name = 'tags']" />
		<div id="blog_items">
			<div class="blog_item">
				<div class="blog_header">
					<xsl:if test="$publish_time">
						<span umi:element-id="{$document-page-id}" umi:field-name="publish_time">
							<xsl:text>Добавлено </xsl:text>
							<xsl:apply-templates select="document(concat('udata://system/convertDate/',$publish_time,'/d.m.Y/'))/udata" />
						</span>
					</xsl:if>
					<xsl:if test="$tags">
						<span umi:element-id="{@document-page-id}" umi:field-name="tags">
							<xsl:text>(</xsl:text>
							<xsl:apply-templates select="$tags/value" mode="post_tags" />
							<xsl:text>)</xsl:text>
						</span>
					</xsl:if>
				</div>
				<div umi:element-id="{$document-page-id}" umi:field-name="content">
					<xsl:value-of select="document(concat('udata://blogs20/postView/',$document-page-id))/udata/content" disable-output-escaping="yes" />
				</div>
			</div>
			<!--         -->
				<div class="social">
				<div class="plusone">
					<div class="g-plusone" data-size="small" data-count="true"></div>
				</div>
					<script type="text/javascript">
					jQuery(document).ready(function(){ jQuery.getScript('//yandex.st/share/share.js', function() {
					new Ya.share({
						'element': 'ya_share1',
						'elementStyle': {
							'type': 'button',
							'linkIcon': true,
							'border': false,
							'quickServices': ['yaru', 'vkontakte', 'facebook', 'twitter', 'odnoklassniki', 'moimir', 'lj']
						},
						'popupStyle': {
							'copyPasteField': true
						}
					 });
						});
					});
					</script>
					<span id="ya_share1"></span>
				</div>
				<div class="clear" />
		<!--         -->
			<a name="subitems"></a>
				<xsl:apply-templates select="document(concat('udata://blogs20/commentsList/',$document-page-id,'/'))/udata" />
			<a name="comment_add" />
			<h3>Добавить комментарий:</h3>
			<xsl:apply-templates select="document('udata://blogs20/checkAllowComments/')/udata" />
		</div>
	</xsl:template>
	

	<xsl:template match="value" mode="post_tags">
		<a href="/blogs20/postsByTag/{.}">
			<xsl:value-of select="." />
		</a>
		<xsl:if test="position() != last()">
			<xsl:text>, </xsl:text>
		</xsl:if>
	</xsl:template>

	<xsl:template match="result[@module = 'blogs20'][@method = 'postEdit']">
		<xsl:apply-templates select="document('udata://blogs20/postEdit/')/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'blogs20'][@method = 'postEdit']">
		<xsl:variable name="post" select="document('udata://blogs20/postView/')/udata" />
		<xsl:copy-of select="$post" />
		<div class="add_form">
			<form name="frm_editblogmsg" method="post" action="/blogs20/postEdit/{$post/pid}/do/" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
				<div class="form_element">
					<label class="required">
						<span><xsl:text>Тема:</xsl:text></span>
						<input type="text" name="title" class="textinputs" value="{$post/name}"/>
					</label>
				</div>
				<div class="form_element">
					<label class="required">
						<span><xsl:text>Сообщение:</xsl:text></span>
						<textarea name="content"><xsl:value-of select="$post/content" disable-output-escaping="yes" /></textarea>
					</label>
				</div>
				<div class="form_element">
					<input type="submit" class="button" value="Сохранить" />
				</div>
			</form>
		</div>
	</xsl:template>

	<xsl:template match="udata[@module = 'blogs20'][@method = 'postEdit'][error]">
		<xsl:apply-templates select="error" />
	</xsl:template>

</xsl:stylesheet>