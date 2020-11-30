<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/result[@module = 'catalog' and @method = 'object']" mode="header">
		<a href="{$parent_link}" class="back_a">Вернуться назад</a>
		<h1 umi:element-id="{@pageId}" umi:field-name="h1" umi:empty="&empty-page-name;">
			<xsl:value-of select="@header" />
		</h1>
	</xsl:template>

	<xsl:template match="/result[@module = 'catalog' and @method = 'object']">
		<xsl:apply-templates select="document(concat('upage://', page/@id,'?show-empty'))/udata" mode="object-view" />
	</xsl:template>

	<xsl:template match="udata" mode="object-view">
		<xsl:variable name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
		<div class="catalog">
			<div class="item">
				<div class="image">
					<table class="table_r">
						<tr>
							<td>
								<div class="price_l">
									<span umi:element-id="{page/@id}" umi:field-name="price" class="price_span">
										<xsl:apply-templates select="document(concat('udata://emarket/price/', page/@id))" />
									</span>
								</div>
							</td>
						</tr>
					</table>
					<xsl:call-template name="catalog-thumbnail">
						<xsl:with-param name="element-id" select="page/@id" />
						<xsl:with-param name="field-name">photo</xsl:with-param>
						<xsl:with-param name="width">281</xsl:with-param>
					</xsl:call-template>
					<table class="table_r">
						<tr>
							<td class="descrip">
								<xsl:apply-templates select=".//property[@name = '&property-description;']" />
							</td>
						</tr>
					</table>
				</div>

				
				<div class="clear" />
				<form id="add_basket_{page/@id}" class="options" action="{$lang-prefix}/emarket/basket/put/element/{page/@id}/">
					<xsl:apply-templates select=".//group[@name = 'catalog_option_props']" mode="table_options" />
					<input type="submit" class="button big" id="add_basket_{$document-page-id}">
						<xsl:attribute name="value">
							<xsl:text>&basket-add;</xsl:text>
							<xsl:if test="$cart_items/item[page/@id = $document-page-id]">
								<xsl:text> (</xsl:text>
								<xsl:value-of select="sum($cart_items/item[page/@id = $document-page-id]/amount)" />
								<xsl:text>)</xsl:text>
							</xsl:if>
						</xsl:attribute>
					</input>
				</form>
				<div class="social">
					<div class="plusone">
						<div class="g-plusone" data-size="small" data-count="true"></div>
					</div>
					<!--script type="text/javascript">
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
					</script-->
					<span id="ya_share1"></span>
				</div>
				<xsl:apply-templates select=".//group[@name = '&group-parameter;']" mode="table" />
				<xsl:if test="$user-type = 'sv'">
					<xsl:apply-templates select=".//group[@name = 'manage_options']" mode="table" />
				</xsl:if>
				<!--xsl:apply-templates select="document(concat('udata://emarket/getCompareLink/', page/@id))" /-->
			</div>

			<!--<xsl:apply-templates select=".//property[@name = '&property-recommended;']" />
			<xsl:apply-templates select="document('udata://comments/insert')" />-->
		</div>
	</xsl:template>
	
	
	<xsl:template match="property[@name = '&property-description;']">
		<div class="descr">
			<h4>
				<xsl:text>&item-description;:</xsl:text>
			</h4>

			<div umi:element-id="{../../../@id}" umi:field-name="{@name}" umi:empty="&item-description;" class="item_description">
				<xsl:value-of select="value" disable-output-escaping="yes" />
			</div>
		</div>
	</xsl:template>
	
	
	<xsl:template match="group" mode="table">
		<table class="group_table">
			<thead>
				<tr>
					<th colspan="2">
						<xsl:value-of select="concat(title, ':')" />
					</th>
				</tr>
			</thead>
			<tbody umi:element-id="{../../@id}">
				<xsl:apply-templates select="property" mode="table" />
			</tbody>
		</table>
	</xsl:template>
	
	<xsl:template match="property" mode="table">
		<tr>
			<td>
				<span>
					<xsl:apply-templates select="document(concat('utype://', ../../../@type-id, '.', ../@name))/udata/group/field[@name = ./@name]/tip" mode="tip" />
					<xsl:value-of select="title" />
				</span>
			</td>

			<td umi:field-name="{@name}">
				<xsl:apply-templates select="." />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="group" mode="table_options">
		<xsl:if test="count(//option) &gt; 0">
			<h4><xsl:value-of select="concat(title, ':')" /></h4>
			<xsl:apply-templates select="property" mode="table_options" />
		</xsl:if>
	</xsl:template>

	<xsl:template match="property" mode="table_options">
		<table>
			<thead>
				<tr>
					<th colspan="3">
						<xsl:value-of select="concat(title, ':')" />
					</th>
				</tr>
			</thead>
			<tbody>
				<xsl:apply-templates select="value/option" mode="table_options" />
			</tbody>
		</table>
	</xsl:template>

	<xsl:template match="option" mode="table_options">
		<tr>
			<td style="width:20px;">
				<input type="radio" name="options[{../../@name}]" value="{object/@id}">
					<xsl:if test="position() = 1">
						<xsl:attribute name="checked">
							<xsl:text>checked</xsl:text>
						</xsl:attribute>
					</xsl:if>
				</input>
			</td>
			<td>
				<xsl:value-of select="object/@name" />
			</td>
			<td align="right">
				<xsl:value-of select="@float" />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="tip" mode="tip">
		<xsl:attribute name="title">
			<xsl:apply-templates />
		</xsl:attribute>
		<xsl:attribute name="style">
			<xsl:text>border-bottom:1px dashed; cursor:help;</xsl:text>
		</xsl:attribute>
	</xsl:template>

</xsl:stylesheet>