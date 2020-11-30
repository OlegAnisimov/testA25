<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
        <xsl:template match="udata[@method = 'getCreateForm' or @method = 'getEditForm']">
		<xsl:apply-templates select="group" mode="form" />
	</xsl:template>
        
	<xsl:template match="udata[@method = 'getCreateForm' or @method = 'getEditForm']" mode="cust-reg">
            <div class="custom_form_reg">
                <hr style="margin-bottom:10px;"/>
		<xsl:apply-templates select="group" mode="form-cust" />
            </div>
	</xsl:template>
	
	<xsl:template match="group" mode="form-cust">
            <xsl:if test="@name='personal_info'">
		<h2>
			<xsl:value-of select="@title" />
		</h2>
            </xsl:if>
		<!--<xsl:apply-templates select="field" mode="form" />-->
                <xsl:apply-templates select="field" mode="form-cust" />
	</xsl:template>


        <xsl:template match="field[@name='lname' or @name='father_name']" mode="form-cust"/>
        <xsl:template match="field" mode="form-cust">
            <div class="form-input">
                <label for="password_confirm" class="middle-color">
                    <xsl:value-of select="concat(@title, ':')" />
                    <xsl:apply-templates select="@required" mode="form-cust" />
                </label>
                <input type="text" class="text-input dark-color light-bg" name="{@input_name}" id="{@input_name}" value="{.}" >
                    <xsl:apply-templates select="@required" mode="form" />
                </input>
            </div>
        </xsl:template>

        <xsl:template match="field" mode="form">
            <div>
                <label title="{@tip}">
                    <xsl:apply-templates select="@required" mode="form" />
                    <span>
                        <xsl:value-of select="concat(@title, ':')" />
                    </span>
                    <input type="text" name="{@input_name}" value="{.}" class="textinputs" />
                </label>
            </div>
        </xsl:template>


	<xsl:template match="field[@type = 'relation']" mode="form">
		<div>
			<label title="{@tip}">
				<xsl:apply-templates select="@required" mode="form" />
				<span>
					<xsl:value-of select="concat(@title, ':')" />
				</span>
				<select type="text" name="{@input_name}">
					<xsl:if test="@multiple = 'multiple'">
						<xsl:attribute name="multiple">multiple</xsl:attribute>
					</xsl:if>
					<xsl:apply-templates select="values/item" mode="form" />
				</select>
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="item" mode="form">
		<option value="{@id}">
			<xsl:copy-of select="@selected" />
			<xsl:value-of select="." />
		</option>
	</xsl:template>


	<xsl:template match="field[@type = 'boolean']" mode="form">
		<div>
			<label title="{@tip}">
				<xsl:apply-templates select="@required" mode="form" />
				<span>
					<xsl:value-of select="concat(@title, ':')" />
				</span>
				<input type="hidden" name="{@input_name}" value="0" />
				<input type="checkbox" name="{@input_name}" value="1">
					<xsl:copy-of select="@checked" />
				</input>
			</label>
		</div>
	</xsl:template>


	<xsl:template match="field[@type = 'text' or @type = 'wysiwyg']" mode="form">
		<div>
			<label title="{@tip}">
				<xsl:apply-templates select="@required" mode="form" />
				<span>
					<xsl:value-of select="concat(@title, ':')" />
				</span>
				<textarea name="{@input_name}" class="textinputs">
					<xsl:value-of select="." />
				</textarea>
			</label>
		</div>
	</xsl:template>


	<xsl:template match="field[@type = 'file' or @type = 'img_file']" mode="form">
		<div>
			<label title="{@tip}">
				<xsl:apply-templates select="@required" mode="form" />
				<span>
					<xsl:value-of select="concat(@title, ':')" />
				</span>
				
				<input type="file" name="{@input_name}" class="textinputs" />
			</label>
		</div>
	</xsl:template>
	
	<xsl:template match="@required" mode="form">
		<xsl:attribute name="required">required</xsl:attribute>
	</xsl:template>
        
        <xsl:template match="@required" mode="form-cust">
		<span class="active-color">*</span>
	</xsl:template>
        
        
</xsl:stylesheet>