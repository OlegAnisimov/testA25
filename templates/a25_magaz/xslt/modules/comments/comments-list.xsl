<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				xmlns:xlink="http://www.w3.org/TR/xlink"
				xmlns:fb="http://www.facebook.com/2008/fbml"
				exclude-result-prefixes="xsl umi xlink fb">

	<xsl:template match="udata[@module = 'comments' and @method = 'insert']">
		<hr /><a name="comments" />
		<h4>
			<xsl:text>&comments;:</xsl:text>
		</h4>
		<div itemprop="review" itemscope="itemscope" itemtype="http://schema.org/Review" class="comments" umi:module="comments" umi:add-method="none" umi:region="list" umi:sortable="sortable">
			<xsl:apply-templates select="items/item" mode="comment" />
			<xsl:apply-templates select="document(concat('udata://system/numpages/', total, '/', per_page))" />
			<xsl:choose>
				<xsl:when test="$user-type = 'guest'">
					<xsl:apply-templates select="add_form" mode="guest" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates select="add_form" mode="user" />
				</xsl:otherwise>
			</xsl:choose>
		</div>
		<xsl:apply-templates select="document('udata://comments/insertVkontakte')" />
		<xsl:apply-templates select="document('udata://comments/insertFacebook')" />
	</xsl:template>

	<xsl:template match="udata[@module = 'comments' and @method = 'insert' and not(items)]">
		<xsl:apply-templates select="document('udata://comments/insertVkontakte')" />
		<xsl:apply-templates select="document('udata://comments/insertFacebook')" />
	</xsl:template>

	<xsl:template match="udata[@module = 'comments' and @method = 'insertVkontakte' and @type='vkontakte']">
		<script type="text/javascript" src="https://vk.com/js/api/openapi.js"></script>
		<script type="text/javascript"><![CDATA[VK.init({apiId: ]]><xsl:value-of select="api" /><![CDATA[, onlyWidgets: true});]]></script>
		<hr /><a name="comments" />
		<h4>
			<xsl:text>&comments-vk;:</xsl:text>
		</h4>
		<div id="vk_comments"></div>
		<script type="text/javascript"><![CDATA[
		VK.Widgets.Comments("vk_comments", {limit: ]]><xsl:value-of select="per_page" /><![CDATA[, width: "]]><xsl:value-of select="width" /><![CDATA[", attach: "]]><xsl:value-of select="extend" /><![CDATA["});
		]]></script>
	</xsl:template>

	<xsl:template match="udata[@module = 'comments' and @method = 'insertVkontakte' and not(@type)]"/>

	<xsl:template match="udata[@module = 'comments' and @method = 'insertFacebook' and @type='facebook']">
		<xsl:variable name="href" select="concat('https://', $domain, $lang-prefix, $request-uri)" />
		<hr /><a name="comments" />
		<h4>
			<xsl:text>&comments; Facebook:</xsl:text>
		</h4>
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/ru_RU/all.js#xfbml=1"></script>
		<fb:comments href="{$href}" num_posts="{per_page}" width="{width}" colorscheme="{colorscheme}"></fb:comments>
	</xsl:template>

	<xsl:template match="udata[@module = 'comments' and @method = 'insertFacebook' and not(@type)]" />

	<xsl:template match="udata[@method = 'insert']/add_form" mode="guest">
		<a name="add-comment" />
		<form method="post" action="{action}">
			<div class="form_element">
				<label>
					<span>&comment-title;:</span>
					<input type="text" name="title" class="textinputs" />
				</label>
			</div>
			<div class="form_element">
				<label>
					<span>&comment-you-name;:</span>
					<input type="text" name="author_nick" class="textinputs" />
				</label>
			</div>
			<div class="form_element">
				<label>
					<span>&comment-you-email;:</span>
					<input type="text" name="author_email" class="textinputs" />
				</label>
			</div>
			<div class="form_element">
				<label>
					<span>&comment-body;:</span>
					<textarea name="comment"></textarea>
				</label>
			</div>
			<div class="form_element">
				<xsl:apply-templates select="document('udata://system/captcha/')/udata" />
			</div>
			<div class="form_element">
				<input type="submit" class="button" value="&comment-submit;" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="udata[@method = 'insert']/add_form" mode="user">
		<a name="add-comment" />
		<form method="post" action="{action}">
			<div class="form_element">
				<label>
					<span>
						<xsl:text>&comment-body;:</xsl:text>
					</span>
					<textarea name="comment" />
				</label>
			</div>
			<div class="form_element">
				<input type="submit" class="button" value="&comment-submit;" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="item" mode="comment">
		<div class="item" umi:element-id="{@id}" umi:region="row">
			<div class="descr" umi:field-name="message" umi:delete="delete" umi:empty="&empty;" itemprop="description">
				<xsl:value-of select="." disable-output-escaping="yes" />
			</div>

			<div class="date">
				<xsl:apply-templates select="document(@xlink:author-href)" />
				<xsl:text> (</xsl:text>
				<span umi:field-name="publish_time" itemprop="datePublished">
					<xsl:attribute name="content">
						<xsl:call-template name="format-date">
							<xsl:with-param name="date" select="@publish_time" />
							<xsl:with-param name="pattern" select="'Y-m-d'" />
						</xsl:call-template>
					</xsl:attribute>
					<xsl:call-template name="format-date">
						<xsl:with-param name="date" select="@publish_time" />
					</xsl:call-template>
				</span>
				<xsl:text>)</xsl:text>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>
