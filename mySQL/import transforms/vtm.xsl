<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="text"/>
	<xsl:template match="VTM[not(INVALID)]">
		<xsl:value-of select="VTMID"/>, "<xsl:value-of select="NM"/>"<xsl:text>&#xa;</xsl:text>
	</xsl:template>
	<xsl:template match="@*|node()">
		<xsl:apply-templates select="@*|node()"/>
	</xsl:template>
</xsl:stylesheet>
