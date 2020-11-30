<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns="http://www.w3.org/1999/xhtml"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				exclude-result-prefixes="xsl date udt umi">

	<xsl:template match="udata[@module = 'forum'][@method = 'confs_list']" />

	<xsl:template match="udata[@module = 'forum'][@method = 'confs_list'][total and total &gt; 0]">
		<table>
			<thead>
				<tr>
					<th>&forum;</th>
					<th>&forum-topics-count;</th>
					<th>&forum-answers;</th>
					<th>&forum-last-message;</th>
				</tr>
			</thead>
			<tbody>
				<xsl:apply-templates select="items/item" mode="confs_list" />
			</tbody>
		</table>
		<xsl:apply-templates select="total" />
	</xsl:template>

	<xsl:template match="item" mode="confs_list">
		<tr>
			<td>
				<a href="{@link}" umi:element-id="{@id}" umi:field-name="name"><xsl:apply-templates /></a>
				<div umi:element-id="{@id}" umi:field-name="descr">
					<xsl:value-of select="document(concat('upage://', @id, '.descr'))/udata/property/value" disable-output-escaping="yes" />
				</div>
			</td>
			<td align="center"><xsl:value-of select="@topics_count" /></td>
			<td align="center"><xsl:value-of select="@messages_count" /></td>
			<td><xsl:apply-templates select="document(concat('udata://forum/conf_last_message/', @id, '/'))/udata" /></td>
		</tr>
	</xsl:template>

</xsl:stylesheet>