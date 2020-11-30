<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet	version="1.0"
				xmlns="http://www.w3.org/1999/xhtml"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="result[@module = 'forum'][@method = 'conf']">
		<div id="forum">
			<div class="forum_descr" umi:element-id="{$document-page-id}" umi:field-name="descr">
				<xsl:value-of select="//property[@name = 'descr']/value" disable-output-escaping="yes" />
			</div>
			<xsl:apply-templates select="document(concat('udata://forum/confs_list//', $document-page-id, '/'))/udata" />
			<xsl:apply-templates select="document('udata://forum/conf/')/udata">
				<xsl:with-param name="test_add_topic" select="document(concat('udata://forum/confs_list//', $document-page-id, '/'))/udata/total" />
			</xsl:apply-templates>
		</div>
	</xsl:template>

	<xsl:template match="udata[@module = 'forum'][@method = 'conf']">
		<xsl:param name="test_add_topic" select="0" />
		<xsl:if test="total">
			<table>
				<thead>
					<tr>
						<th><xsl:text>Темы</xsl:text></th>
						<th><xsl:text>Ответов</xsl:text></th>
						<th><xsl:text>Последнее&#160;сообщение</xsl:text></th>
					</tr>
				</thead>
				<tbody>
					<xsl:apply-templates select="lines/item" mode="topic_list" />
				</tbody>
			</table>
		</xsl:if>
		<xsl:if test="not($test_add_topic)">
			<xsl:apply-templates select="document(concat('udata://forum/topic_post/', $document-page-id))/udata" />
		</xsl:if>
	</xsl:template>

	<xsl:template match="item" mode="topic_list">
		<tr>
			<td width="200"><a href="{@link}" class="forum" umi:element-id="{@id}" umi:field-name="name"><xsl:apply-templates /></a></td>
			<td align="center"><xsl:value-of select="@messages_count" /></td>
			<td><xsl:apply-templates select="document(concat('udata://forum/topic_last_message/', @id, '/'))/udata" /></td>
		</tr>
	</xsl:template>

</xsl:stylesheet>