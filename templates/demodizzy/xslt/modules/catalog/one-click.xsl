<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet	version="1.0"
					xmlns="http://www.w3.org/1999/xhtml"
					xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
					xmlns:date="http://exslt.org/dates-and-times"
					xmlns:udt="http://umi-cms.ru/2007/UData/templates"
					xmlns:xlink="http://www.w3.org/TR/xlink"
					exclude-result-prefixes="xsl date udt xlink">

	<xsl:variable name="customer-info" select="document(concat('uobject://', document('udata://users/auth')/udata/user_id))" />
	<xsl:variable name="customer-email" select="$customer-info//group[@name = 'idetntify_data']/property[@name = 'e-mail']/value" />

	<xsl:template match="/">
		<div class="content">
			<form class="buyer_data" enctype="multipart/form-data" method="post" action="/{udata/group/@lang}/emarket/getOneClickOrder/">
				<xsl:apply-templates select="udata/group/field" />
			</form>
		</div>

		<div class="clear"></div>
	</xsl:template>

	<xsl:template match="field">
		<div>
			<label>
				<xsl:if test="@required">
					<xsl:attribute name="class">required</xsl:attribute>
				</xsl:if>
				<span>
					<xsl:value-of select="@title" /><xsl:if test="@required"><xsl:text>*</xsl:text></xsl:if>:
				</span>
				<input type="text" class="textinputs" name="data[new][{@name}]">
					<xsl:variable name="inputName" select="@name" />
					<xsl:if test="@required">
						<xsl:attribute name="required">required</xsl:attribute>
					</xsl:if>
					<xsl:choose>
						<xsl:when test="$customer-info//object/@type-guid = 'users-user' and $inputName = 'email'">
							<xsl:attribute name="value"><xsl:value-of select="$customer-email"/></xsl:attribute>
						</xsl:when>
						<xsl:otherwise>
							<xsl:attribute name="value"><xsl:value-of select="$customer-info//property[@name = $inputName]/value"/></xsl:attribute>
						</xsl:otherwise>
					</xsl:choose>
				</input>
			</label>
		</div>
	</xsl:template>
</xsl:stylesheet>