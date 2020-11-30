<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/result[@module = 'catalog' and @method = 'object']">
		<xsl:apply-templates select="document(concat('udata://content/addRecentPage/', $document-page-id))/udata" />
		<xsl:apply-templates select="document(concat('upage://', page/@id,'?show-empty'))/udata" mode="object-view" />
	</xsl:template>

	<xsl:template match="udata" mode="object-view">
		<xsl:variable name="cart_items" select="document('udata://emarket/cart/')/udata/items" />
		<div class="catalog" itemscope="itemscope" itemtype="http://schema.org/Product">
			<div class="item">
				<link itemprop="name" content="{page/name}" />
                <div class="price" itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer">
                    <span umi:element-id="{page/@id}" umi:field-name="price" itemprop="price">
                        <xsl:apply-templates select="document(concat('udata://emarket/price/', page/@id))" />
                    </span>
                    <link itemprop="itemCondition" href="http://schema.org/NewCondition" />
                </div>
                <xsl:if test="//property[@name='old_price']/value != 0">
                    <div class="oldprice">
                        <span umi:element-id="{page/@id}" umi:field-name="old_price">
                            <xsl:apply-templates select="document(concat('udata://emarket/price/', page/@id))" mode="old_price">
                                <xsl:with-param name="price" select="//property[@name='old_price']/value" />
                            </xsl:apply-templates>
                        </span>
                    </div>
                </xsl:if>



				<!-- Основная фотография товара -->
				<xsl:variable name="main-photo" select="//property[@name = 'photo']/value" />
				<div class="image">
					<a class="fancybox-group" href="{$main-photo/text()}">
						<xsl:call-template name="catalog-thumbnail">
							<xsl:with-param name="element-id" select="$document-page-id" />
							<xsl:with-param name="source" select="$main-photo/text()" />
							<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
							<xsl:with-param name="field-name" select="'photo'" />
							<xsl:with-param name="width" select="281" />
						</xsl:call-template>
					</a>

					<!-- Дополнительные фотографии товара -->
					<xsl:apply-templates select="//property[@name='photos']/value" mode="photos"/>

					<xsl:if test="//property[@name='new']/value = 1">
						<img src="/templates/demodizzy/images/new_blue.png" class="new" />
					</xsl:if>
				</div>

				<xsl:apply-templates select=".//property[@name = '&property-description;']" />
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
					<script type="text/javascript">
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
					</script>
					<span id="ya_share1"></span>
					<xsl:apply-templates select="document(concat('udata://vote/getElementRating//', $document-page-id))" mode="elementRating" />
				</div>
				<xsl:apply-templates select=".//group[@name = '&group-parameter;']" mode="table" />
				<xsl:if test="$user-type = 'sv'">
					<xsl:apply-templates select=".//group[@name = 'manage_options']" mode="table" />
				</xsl:if>
				<a href="javascript:window.print();" class="for_print">&for-print;</a>
				<xsl:apply-templates select="document(concat('udata://emarket/getCompareLink/', page/@id))" />
			</div>

			<div class="block">
				<xsl:apply-templates select=".//property[@name = '&property-recommended;']" />
			</div>

			<xsl:apply-templates select="document('udata://comments/insert')" />
		</div>
	</xsl:template>

	<xsl:template match="property[@name = '&property-description;']">
		<div class="descr" itemprop="description">
			<h4>
				<xsl:text>&item-description;:</xsl:text>
			</h4>

			<div umi:element-id="{../../../@id}" umi:field-name="{@name}" umi:empty="&item-description;">
				<xsl:value-of select="value" disable-output-escaping="yes" />
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="property[@name = '&property-description;' and value = '']">
		<div class="descr" itemprop="description">
			<div umi:element-id="{../../../@id}" umi:field-name="{@name}" umi:empty="&item-description;">
				
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="group" mode="table">
		<table class="object">
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
			<xsl:apply-templates select="title" mode="table"/>
			<xsl:apply-templates select="value" mode="table"/>
		</tr>
	</xsl:template>
	<xsl:template match="property/title" mode="table">
		<td>
			<span>
				<xsl:apply-templates select="document(concat('utype://', ../../../../@type-id, '.', ../../@name))/udata/group/field[@name = ../@name]/tip" mode="tip" />
				<xsl:value-of select="." />
			</span>
		</td>
	</xsl:template>
	<xsl:template match="property/value" mode="table">
		<td umi:field-name="{../@name}">
			<xsl:apply-templates select=".." />
		</td>
	</xsl:template>
	<xsl:template match="property[@type='symlink']/value" mode="table">
		<td umi:field-name="{../@name}" umi:type="catalog::object">
			<xsl:apply-templates select=".." />
		</td>
	</xsl:template>
	<xsl:template match="property[@type='wysiwyg']/value" mode="table">
		<td umi:field-name="{../@name}">
			<xsl:value-of select="." disable-output-escaping="yes" />
		</td>
	</xsl:template>
	<xsl:template match="group" mode="div">
		<div class="item_properties">
			<div>
				<xsl:value-of select="concat(title, ':')" />
			</div>
			<xsl:apply-templates select="property" mode="div" />
		</div>
	</xsl:template>

	<xsl:template match="property" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:field-name="{@name}">
			<xsl:apply-templates select="." />
		</span>
		<xsl:text>; </xsl:text>
	</xsl:template>

	<xsl:template match="property[last()]" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:field-name="{@name}">
			<xsl:apply-templates select="." />
		</span>
		<xsl:text>. </xsl:text>
	</xsl:template>

	<xsl:template match="property[@name = 'udachno_sochetaetsya_s']" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:type="catalog::object" umi:field-name="{@name}">
			<xsl:apply-templates select="value/page" mode="div" />
		</span>
		<xsl:text>; </xsl:text>
	</xsl:template>

	<xsl:template match="property[@name = 'udachno_sochetaetsya_s' and last()]" mode="div">
		<xsl:variable name="property.name" select="./@name"/>
		<span>
			<xsl:apply-templates select="document(concat('utype://', ../../../@type-id))/udata/type/fieldgroups/group/field[@name = $property.name]/tip" mode="tip" />
			<xsl:value-of select="title" />
		</span>
		<xsl:text>: </xsl:text>
		<span umi:type="catalog::object" umi:field-name="{@name}">
			<xsl:apply-templates select="value/page" mode="div" />
		</span>
		<xsl:text>. </xsl:text>
	</xsl:template>
	
	<xsl:template match="page" mode="div">	
		<a href="{@link}">
			<xsl:value-of select="name" />
		</a>
		<xsl:text>, </xsl:text>
	</xsl:template>
	
	<xsl:template match="page[last()]" mode="div">	
		<a href="{@link}">
			<xsl:value-of select="name" />
		</a>
	</xsl:template>
	
	<xsl:template match="group" mode="table_options">
		<xsl:if test="count(//option) &gt; 0">
			<h4><xsl:value-of select="concat(title, ':')" /></h4>
			<xsl:apply-templates select="property" mode="table_options" />
		</xsl:if>
	</xsl:template>

	<xsl:template match="property" mode="table_options">
		<table class="object">
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

	<xsl:template match="value" mode="photos">
		<a class="fancybox-group" href="{text()}">
			<xsl:call-template name="catalog-thumbnail">
				<xsl:with-param name="element-id" select="$document-page-id" />
				<xsl:with-param name="source" select="text()" />
				<xsl:with-param name="empty">&empty-photo;</xsl:with-param>
				<xsl:with-param name="field-name" select="../@name" />
				<xsl:with-param name="width" select="90" />
			</xsl:call-template>
		</a>
	</xsl:template>

</xsl:stylesheet>