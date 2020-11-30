<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:variable name="filters" select="document(concat('udata://catalog/getSmartFilters//', $document-page-id, '/0/1'))/udata" />

	<xsl:template match="/result[@method = 'category']">
		<xsl:apply-templates select="document(concat('usel://special-offers/', '?limit=&specials-limit;'))" mode="special-offers" />
	</xsl:template>

	<xsl:template match="/result[@method = 'category'][count(/result/parents/page) &gt; 0]">
        <xsl:choose>
            <xsl:when test="$sort_field = 'Price'">
                <xsl:apply-templates select="document(concat('udata://catalog/getSmartCatalog//', page/@id, '///2/', document('utype://catalog-object')//field[@name='price']/@name  ,'/', $sort_direction,'/'))/udata" />
            </xsl:when>
            <xsl:when test="$sort_field = 'Name'">
                <xsl:apply-templates select="document(concat('udata://catalog/getSmartCatalog//', page/@id, '///2/', document('utype://catalog-object')//field[@name='title']/@name  ,'/', $sort_direction,'/'))/udata" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="document(concat('udata://catalog/getSmartCatalog//', page/@id, '///2/'))/udata" />
            </xsl:otherwise>
        </xsl:choose>
	</xsl:template>

	<xsl:template match="udata[@method = 'getSmartCatalog']">
		<xsl:if test="not($filters/error)">
			<xsl:apply-templates select="$filters" />
		</xsl:if>

		<div class="catalog" umi:element-id="{category_id}" umi:module="catalog" umi:method="getObjectsList" umi:sortable="sortable" umi:add-method="popup">
			<xsl:text>&empty-category;</xsl:text>
		</div>
	</xsl:template>
	
	<xsl:template match="udata[@method = 'getSmartCatalog'][total &gt; 0]">
		<xsl:if test="not($filters/error)">
			<xsl:apply-templates select="$filters" />
		</xsl:if>

		<div class="catalog_sort" />
		<div class="catalog {$catalog}">
            <xsl:call-template name="sorting" />
			<div class="objects" umi:element-id="{category_id}" umi:module="catalog" umi:method="getObjectsList" umi:sortable="sortable">
				<xsl:apply-templates select="lines/item" mode="short-view">
					<xsl:with-param name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
				</xsl:apply-templates>
				<div class="clear" />
			</div>
		</div>
		<div class="clear" />
		
		<xsl:apply-templates select="total" />
	</xsl:template>
</xsl:stylesheet>