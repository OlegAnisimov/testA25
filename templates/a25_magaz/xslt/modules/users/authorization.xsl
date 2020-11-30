<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="user">
		<div class="login">
			<ul class="privat_office">
				<li><xsl:text>&welcome;</xsl:text></li>
				<li>
					<xsl:apply-templates select="$user-info//property[@name = 'lname']" />
					<xsl:apply-templates select="$user-info//property[@name = 'fname']" />
					<xsl:apply-templates select="$user-info//property[@name = 'father_name']" />
				</li>
				<xsl:if test="$user-type = 'sv'">
					<li><a href="#" id="on_edit_in_place" class="link_transfer_class">&on-fast-edit;</a></li>
					<li><a href="/admin/">&to-admin;</a></li>
				</xsl:if>
			</ul>
			<div>
				<a href="{$lang-prefix}/emarket/personal/" class="office">
					<xsl:text>&office;</xsl:text>
				</a>
				<a href="{$lang-prefix}/users/logout/" class="exit">
					<xsl:text>&log-out;</xsl:text>
				</a>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="property[@name = 'fname' or @name = 'lname' or @name = 'father_name']">
		<xsl:value-of select="value" />
		<xsl:text> </xsl:text>
	</xsl:template>

	<xsl:template match="user[@type = 'guest']">
		<form class="login" action="{$lang-prefix}/users/login_do/" method="post">
			<xsl:choose>
  				<xsl:when test="/result[@demo='1']">
			<div>
						<input type="text" value="demo" name="login" onfocus="javascript: if(this.value == '&login;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&login;';" />
					</div>
					<div>
						<input type="password" value="demo" name="password" onfocus="javascript: if(this.value == '&password;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&password;';" />
					</div>
  				</xsl:when>
  				<xsl:otherwise>
					<div>
				<input type="text" value="&login;" name="login" onfocus="javascript: if(this.value == '&login;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&login;';" />
			</div>
			<div>
				<input type="password" value="&password;" name="password" onfocus="javascript: if(this.value == '&password;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&password;';" />
			</div>
  				</xsl:otherwise>
			</xsl:choose>

			<div>
				<input type="submit" class="button" value="&log-in;" />
				<a href="{$lang-prefix}/users/registrate/">
					<xsl:text>&registration;</xsl:text>
				</a>
			</div>

			<a href="{$lang-prefix}/users/forget/">
				<xsl:text>&forget-password;</xsl:text>
			</a>

			<xsl:apply-templates select="document('udata://users/getLoginzaProvider')/udata"/>

			<script src="/templates/a25_magaz/js/ulogin.js"></script>
			<a href="#" id="uLogin"
				 data-ulogin="display=window;theme=classic;fields=first_name,last_name,nickname,email;providers=;hidden=;redirect_uri=http://{/result/@domain}/users/ulogin;mobilebuttons=0;">
				<img src="http://ulogin.ru/img/button.png?version=img.2.0.0" alt="ulogin" />
			</a>

		</form>
	</xsl:template>

	<xsl:template match="udata[@method='getLoginzaProvider']">
			<div class="loginza_block">
				<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
				<a href="{widget_url}" class="loginza">
					<img src="http://loginza.ru/img/sign_in_button_gray.gif" alt="Loginza"/>
				</a>
				</div>
	</xsl:template>

	<xsl:template match="result[@method = 'login' or @method = 'login_do' or @method = 'loginza' or @method = 'ulogin' or @method = 'auth']">
		<xsl:if test="@not-permitted = 1">
			<p><xsl:text>&user-not-permitted;</xsl:text></p>
		</xsl:if>
		<xsl:if test="user[@type = 'guest'] and (@method = 'login_do' or @method = 'loginza' or @method = 'ulogin')">
			<p><xsl:text>&user-reauth;</xsl:text></p>
		</xsl:if>
		<xsl:apply-templates select="document('udata://users/auth/')/udata" />
	</xsl:template>

	<xsl:template match="udata[@module = 'users'][@method = 'auth']">
            <xsl:apply-templates select="document('udata://content/redirect/(/)/')" />
<!--		<form method="post" action="/users/login_do/">
			<input type="hidden" name="from_page" value="{from_page}" />
			<div>
				<label>
					<span>
						<xsl:text>&login;:</xsl:text>
					</span>
					<input type="text" name="login" class="textinputs" value="&login;" onfocus="javascript: if(this.value == '&login;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&login;';" />
				</label>
			</div>
			<div>
				<label>
					<span>
						<xsl:text>&password;:</xsl:text>
					</span>
					<input type="password" name="password" class="textinputs" value="&password;" onfocus="javascript: if(this.value == '&password;') this.value = '';" onblur="javascript: if(this.value == '') this.value = '&password;';" />
				</label>
			</div>
			<div>
				<div style="float:right;">
					<a href="{$lang-prefix}/users/registrate/">
						<xsl:text>&registration;</xsl:text>
					</a>
					<a href="/users/forget/" style="margin:0 15px;">
						<xsl:text>&forget-password;</xsl:text>
					</a>
				</div>
				<input type="submit" class="button" value="&log-in;" />
			</div>
		</form>-->
	</xsl:template>

	<xsl:template match="udata[@module = 'users'][@method = 'auth'][user_id]">
		<div>
			<xsl:text>&welcome; </xsl:text>
			<xsl:choose>
				<xsl:when test="translate(user_name, ' ', '') = ''"><xsl:value-of select="user_login" /></xsl:when>
				<xsl:otherwise><xsl:value-of select="user_name" /> (<xsl:value-of select="user_login" />)</xsl:otherwise>
			</xsl:choose>
		</div>
		<div>
			<a href="{$lang-prefix}/users/logout/">
				<xsl:text>&log-out;</xsl:text>
			</a>
			<xsl:text> | </xsl:text>
			<a href="{$lang-prefix}/emarket/personal/">
				<xsl:text>&office;</xsl:text>
			</a>
		</div>
	</xsl:template>

</xsl:stylesheet>
