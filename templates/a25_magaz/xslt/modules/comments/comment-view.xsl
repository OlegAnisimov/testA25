<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="result[@module = 'comments'][@method = 'comment']">
			<xsl:variable name="publish_time" select="//property[@name = 'publish_time']/value/@unix-timestamp" />
			<xsl:variable name="author_id" select="//property[@name='author_id']/value/item/@id" />
				<p>
					<strong umi:element-id="{page/@id}" umi:field-name="publish_time">
						<xsl:apply-templates select="document(concat('udata://system/convertDate/',$publish_time,'/d.m.Y%20%E2%20H:i/'))/udata" />
					</strong>
					<xsl:text> | </xsl:text>
					<xsl:apply-templates select="document(concat('udata://users/viewAuthor/', $author_id, '/'))/udata" />
				</p>
				<div umi:element-id="{page/@id}" umi:field-name="message">
					<xsl:value-of select="//group[@name='comment_props']/property[@name='message']/value" disable-output-escaping="yes" />
				</div>
	</xsl:template>

</xsl:stylesheet>