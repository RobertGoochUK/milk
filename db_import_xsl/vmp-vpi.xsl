<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="text"/>

	<xsl:template match="VIRTUAL_PRODUCT_INGREDIENT/VPI">
		<xsl:value-of select="VPID"/>, <xsl:value-of select="ISID"/>, <xsl:value-of select="STRNT_NMRTR_VAL"/>, <xsl:value-of select="STRNT_NMRTR_UOMCD"/>, <xsl:value-of select="STRNT_DNMTR_VAL"/>, <xsl:value-of select="STRNT_DNMTR_UOMCD"/>
		<xsl:text>&#xa;</xsl:text>
	</xsl:template>

	<xsl:template match="@*|node()">
		<xsl:apply-templates select="@*|node()"/>
	</xsl:template>

</xsl:stylesheet>
