<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="field" mode="legal_form">
		<xsl:param name="title" />
		<xsl:param name="customer_email" />
		
		<xsl:variable name="form_title">
			<xsl:choose>
				<xsl:when test="$title">
					<xsl:value-of select="$title" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="@title" />
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		
		<div>
			<label title="{@tip}">
				<xsl:attribute name="class">required</xsl:attribute>
				<span>
					<xsl:value-of select="concat($form_title, ':')" />
				</span>
				<xsl:apply-templates select="." mode="data_form">
					<xsl:with-param name="customer_email" select="$customer_email" />
				</xsl:apply-templates>
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="field[@type = 'string']" mode="data_form">
		<xsl:param name="customer_email" />
		<input type="text" name="{@input_name}" value="{.}" class="textinputs" >
			<xsl:if test="$customer_email">
				<xsl:attribute name="value">
					<xsl:value-of select="$customer_email" />
				</xsl:attribute>
			</xsl:if>
		</input>
	</xsl:template>


	<xsl:template match="field[@type = 'relation']" mode="data_form">
		<select type="text" name="{@input_name}">
			<xsl:if test="@multiple = 'multiple'">
				<xsl:attribute name="multiple">multiple</xsl:attribute>
			</xsl:if>
			<xsl:apply-templates select="values/item" mode="data_form" />
		</select>
	</xsl:template>
	
	<xsl:template match="item" mode="data_form">
		<option value="{@id}">
			<xsl:copy-of select="@selected" />
			<xsl:value-of select="." />
		</option>
	</xsl:template>


	<xsl:template match="field[@type = 'boolean']" mode="data_form">
		<input type="hidden" name="{@input_name}" value="0" />
		<input type="checkbox" name="{@input_name}" value="1">
			<xsl:copy-of select="@checked" />
		</input>
	</xsl:template>


	<xsl:template match="field[@type = 'text' or @type = 'wysiwyg']" mode="data_form">
		<textarea name="{@input_name}" class="textinputs">
			<xsl:value-of select="." />
		</textarea>
	</xsl:template>


	<xsl:template match="field[@type = 'file' or @type = 'img_file']" mode="data_form">
		<input type="file" name="{@input_name}" class="textinputs" />
	</xsl:template>
	
	<xsl:template match="@required" mode="data_form">
		<xsl:attribute name="class">required</xsl:attribute>
	</xsl:template>
	
</xsl:stylesheet>