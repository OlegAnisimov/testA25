<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="udata[@method = 'search'][group]">
		<form class="catalog_filter">
			<div class="left">
				<xsl:apply-templates select=".//field[not(@data-type = 'boolean')]" mode="search" />
			</div>
			<div class="right">
				<xsl:apply-templates select=".//field[@data-type = 'boolean']" mode="search" />
			</div>
			<div class="clear"></div>
			<div>
				<input class="button" type="submit" value="&apply;" />
				<input class="button" type="button" value="&reset;" onclick="javascript: window.location = '?';" />
			</div>
		</form>
	</xsl:template>
	

	<xsl:template match="field" mode="search">
		<div>
			<label>
				<span>
					<xsl:value-of select="@title" />
				</span>
				<input type="text" name="fields_filter[{@name}]" value="{value}" class="textinputs" />
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="field[@data-type = 'relation' or @data-type = 'symlink']" mode="search">
		<div>
			<label>
				<span>
					<xsl:value-of select="@title" />
				</span>
				<select name="fields_filter[{@name}]">
					<option />
					<xsl:apply-templates select="values/item" mode="search" />
				</select>
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="field/values/item" mode="search">
		<option value="{@id}">
			<xsl:copy-of select="@selected" />
			<xsl:value-of select="." />
		</option>
	</xsl:template>

	<xsl:template match="field[@data-type = 'boolean']" mode="search">
		<div>
			<label>
				<input type="checkbox" name="fields_filter[{@name}]" value="1">
					<xsl:if test="checked">
						<xsl:attribute name="checked">checked</xsl:attribute>
					</xsl:if>
				</input>
				<xsl:value-of select="@title" />
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="field[@data-type = 'price' or @data-type = 'int' or @data-type = 'float']" mode="search">
		<div class="grade">
			<label class="right">
				<span>
					<xsl:text>&range-to;</xsl:text>
				</span>
				<input type="text" name="fields_filter[{@name}][1]" value="{value_to}" class="textinputs" />
			</label>
			<label>
				<span>
					<xsl:value-of select="@title" />
					<xsl:text> &range-from;</xsl:text>
				</span>
				<input type="text" name="fields_filter[{@name}][0]" value="{value_from}" class="textinputs" />
			</label>
		</div>
	</xsl:template>
</xsl:stylesheet>