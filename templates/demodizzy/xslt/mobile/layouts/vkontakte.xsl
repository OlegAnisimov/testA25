<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://mobile/i18n/constants.dtd:file">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:umi="http://www.umi-cms.ru/TR/umi">

	<xsl:template match="/" mode="layout">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>
					<xsl:value-of select="$document-title" />
				</title>
				<xsl:if test="$user-type != 'sv'">
					<script charset="utf-8" src="/js/jquery/jquery-ui.js" type="text/javascript"></script>
				</xsl:if>
				<script charset="utf-8" src="/templates/demodizzy/js/__common.js" type="text/javascript"></script>
				<link type="text/css" rel="stylesheet" href="/templates/demodizzy/css/common.css" />
				<link type="text/css" rel="stylesheet" href="/templates/demodizzy/css/vkontakte.css" />
				
			</head>
			<body>
				<div id="main">
					<div id="page">
						<div class="center">
							<xsl:apply-templates select="$errors" />
							<xsl:apply-templates select="result" mode="header" />
							<div class="content">								
			<xsl:value-of select=".//property[@name = 'content']/value" disable-output-escaping="yes" />
								<xsl:apply-templates select="result" />
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div id="foot">
						
						<div class="copyright">
							<xsl:text>&copyright;</xsl:text>
						</div>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>