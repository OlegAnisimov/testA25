<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:variable name="filter" select="'filter'" />
	<xsl:variable name="from" select="'from'" />
	<xsl:variable name="to" select="'to'" />

	<xsl:template match="udata[@method = 'getSmartFilters'][group]">
		<xsl:variable name="fields" select="//field" />

		<form class="catalog_filter" data-category="{@category-id}">

			<div class="filters">
				<xsl:for-each select="$fields[position() mod 2 = 1]">
					<xsl:variable name="current" select="current()" />
					<xsl:variable name="next" select="$fields[@name = $current/@name]//following::field[1]"/>

					<div class="row">
						<xsl:if test="current()">
							<div class="cell">
								<xsl:apply-templates select="current()"  mode="filter" />
							</div>
						</xsl:if>
						<xsl:if test="$next">
							<div class="cell">
								<xsl:apply-templates select="$next"  mode="filter" />
							</div>
						</xsl:if>
					</div>
				</xsl:for-each>
			</div>

			<div class="clear"></div>
			<div class="buttons">
				<input class="button" type="submit" value="&apply;" id="show_result"/>
				<input class="button" type="button" value="&reset;" id="reset"/>
			</div>
		</form>
	</xsl:template>

	<xsl:template match="field" mode="filter">
		<div class="field">
			<h3><xsl:value-of select="@title" /></h3>
			<div class="data">
				<xsl:apply-templates select="." mode="filter_field"/>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="field" mode="filter_field">
		<xsl:apply-templates select="item" mode="filter_field" />
	</xsl:template>

	<xsl:template match="field/item" mode="filter_field">
		<label>
			<input type="checkbox" name="{$filter}[{../@name}][{position() - 1}]" value="{.}" />
			<xsl:value-of select="." />
		</label>
	</xsl:template>

	<xsl:template match="field[@data-type = 'boolean' or @data-type = 'file' or @data-type = 'img_file' or @data-type = 'swf_file' or @data-type = 'video_file']" mode="filter_field">
		<xsl:variable name="selected_value" select="item[@is-selected = '1']" />
		<label>
			<input type="radio" name="{$filter}[{@name}]" value="1" >
				<xsl:apply-templates select="$selected_value" mode="checked">
					<xsl:with-param name="compare" select="1"/>
				</xsl:apply-templates>
			</input>
			<xsl:text>Есть</xsl:text>
		</label>
		<label>
			<input type="radio" name="{$filter}[{@name}]" value="0" >
				<xsl:apply-templates select="$selected_value" mode="checked">
					<xsl:with-param name="compare" select="0"/>
				</xsl:apply-templates>
			</input>
			<xsl:text>Нет</xsl:text>
		</label>
		<label>
			<input type="radio" name="{$filter}[{@name}]" value="" >
				<xsl:if test="not($selected_value)">
					<xsl:attribute name="checked">
						<xsl:text>checked</xsl:text>
					</xsl:attribute>
				</xsl:if>
			</input>
			<xsl:text>Неважно</xsl:text>
		</label>
	</xsl:template>

	<xsl:template match="item" mode="checked">
		<xsl:param name="compare" />

		<xsl:if test="$compare = text()">
			<xsl:attribute name="checked">
				<xsl:text>checked</xsl:text>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>

	<xsl:template match="field[minimum][maximum]" mode="filter_field">
		<div>
			<xsl:attribute name="class">
				<xsl:choose>
					<xsl:when test="./@data-type = 'date'">
						<xsl:text>date_range</xsl:text>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>range</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:attribute>
			<xsl:choose>
				<xsl:when test="./@data-type != 'date'">
					<input type="text" name="{$filter}[{@name}][from]" data-minimum="{./minimum}">
						<xsl:attribute name="value">
							<xsl:apply-templates select="minimum" mode="filter_value"/>
						</xsl:attribute>
					</input>
					<span>-</span>
					<input type="text" name="{$filter}[{@name}][to]" data-maximum="{./maximum}" >
						<xsl:attribute name="value">
							<xsl:apply-templates select="maximum" mode="filter_value"/>
						</xsl:attribute>
					</input>
				</xsl:when>
				<xsl:otherwise>
					<xsl:variable name="date.maximum" select="document(concat('udata://system/convertDate/', ./maximum, '/m.d.y'))/udata"/>
					<xsl:variable name="date.minimum" select="document(concat('udata://system/convertDate/', ./minimum, '/m.d.y'))/udata"/>
					<input type="text" name="{$filter}[{@name}][from]" data-minimum="{$date.minimum}" data-maximum="{$date.maximum}">
						<xsl:attribute name="value">
							<xsl:apply-templates select="minimum" mode="date_value">
								<xsl:with-param name="default.date" select="$date.minimum"/>
							</xsl:apply-templates>
						</xsl:attribute>
					</input>
					<span>-</span>
					<input type="text" name="{$filter}[{@name}][to]" data-minimum="{$date.minimum}" data-maximum="{$date.maximum}" >
						<xsl:attribute name="value">
							<xsl:apply-templates select="maximum" mode="date_value">
								<xsl:with-param name="default.date" select="$date.maximum"/>
							</xsl:apply-templates>
						</xsl:attribute>
					</input>
				</xsl:otherwise>
			</xsl:choose>
		</div>
		<xsl:if test="./@data-type != 'date'">
			<div class="slider_group">
				<span class="min"></span>
				<div class="slider"></div>
				<span class="max"></span>
			</div>
		</xsl:if>
	</xsl:template>

	<xsl:template match="minimum|maximum" mode="filter_value">
		<xsl:choose>
			<xsl:when test="@selected">
				<xsl:value-of select="@selected" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="." />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="minimum|maximum" mode="date_value">
		<xsl:param name="default.date" />
		<xsl:choose>
			<xsl:when test="@selected">
				<xsl:value-of select="document(concat('udata://system/convertDate/', ./@selected, '/m.d.y'))/udata" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$default.date" />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

</xsl:stylesheet>