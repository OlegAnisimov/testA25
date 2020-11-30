<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xlink="http://www.w3.org/TR/xlink">

	<xsl:template match="result[@method = 'registrate']">
		<xsl:apply-templates select="document('udata://users/registrate')" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'registrate']">
		<form id="registrate" enctype="multipart/form-data" method="post" action="{$lang-prefix}/users/registrate_do/" onsubmit="site.forms.data.save(this); return site.forms.data.check(this);">
			<div>
				<label class="required">
					<span>
						<xsl:text>&login;:</xsl:text>
					</span>
					<input type="text" name="login" class="textinputs" />
				</label>
			</div>
			<div>
				<label class="required">
					<span>
						<xsl:text>&password;:</xsl:text>
					</span>
					<input type="password" name="password" class="textinputs" />
				</label>
			</div>
			<div>
				<label class="required">
					<span>
						<xsl:text>&password-confirm;:</xsl:text>
					</span>
					<input type="password" name="password_confirm" class="textinputs" />
				</label>
			</div>
			<div>
				<label class="required">
					<span>
						<xsl:text>&e-mail;:</xsl:text>
					</span>
					<input type="text" name="email" class="textinputs" />
				</label>
			</div>
			
			<xsl:apply-templates select="document(@xlink:href)" />
			
			<xsl:apply-templates select="document('udata://system/captcha')" />

			<div>
				<input type="submit" class="button" value="&registration-do;" />
			</div>
		</form>
	</xsl:template>

	<xsl:template match="result[@method = 'registrate_done']">		
		<xsl:apply-templates select="document('udata://users/registrate_done')/udata"/>
	</xsl:template>
	
	<xsl:template match="udata[@method = 'registrate_done']">
		<xsl:choose>
			<xsl:when test="result = 'without_activation'">
				<h4>
					<xsl:text>&registration-done;</xsl:text>
				</h4>
			</xsl:when>
			<xsl:when test="result = 'error'">
				<h4>
					<xsl:text>&registration-error;</xsl:text>
				</h4>
			</xsl:when>
			<xsl:when test="result = 'error_user_exists'">
				<h4>
					<xsl:text>&registration-error-user-exists;</xsl:text>
				</h4>
			</xsl:when>
			<xsl:otherwise>
				<h4>
					<xsl:text>&registration-done;</xsl:text>
				</h4>
				<p>
					<xsl:text>&registration-activation-note;</xsl:text>
				</p>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	
	<xsl:template match="result[@method = 'activate']">
		<xsl:variable name="activation-errors" select="document('udata://users/activate')/udata/error" />
		
		<xsl:choose>
			<xsl:when test="count($activation-errors)">
				<xsl:apply-templates select="$activation-errors" />
			</xsl:when>
			<xsl:otherwise>
				<p>
					<xsl:text>&account-activated;</xsl:text>
				</p>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	
	<!-- User settings -->
	<xsl:template match="result[@method = 'settings']">
		<xsl:apply-templates select="document('udata://users/settings')" />
	</xsl:template>
	
	<xsl:template match="udata[@method = 'settings']">
		<xsl:variable name="csrf_token" select="/result/@csrf" />

		<form enctype="multipart/form-data" method="post" action="{$lang-prefix}/users/settings_do/" id="con_tab_profile">
			<input type="hidden" name="csrf" value="{$csrf_token}" />
			<div>
				<label>
					<span>
						<xsl:text>&login;:</xsl:text>
					</span>
					<input type="text" name="login" class="textinputs" disabled="disabled" value="{$user-info//property[@name = 'login']/value}" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&current-password;:</xsl:text>
					</span>
					<input type="password" name="current-password" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&password;:</xsl:text>
					</span>
					<input type="password" name="password" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&password-confirm;:</xsl:text>
					</span>
					<input type="password" name="password_confirm" class="textinputs" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&e-mail;:</xsl:text>
					</span>
					<input type="text" name="email" class="textinputs" value="{$user-info//property[@name = 'e-mail']/value}" />
				</label>
			</div>
			
			<xsl:apply-templates select="document(concat('udata://data/getEditForm/', $user-id))" />

			<div>
				<input type="submit" class="button" value="&save-changes;" />
			</div>
		</form>
	</xsl:template>
</xsl:stylesheet>
