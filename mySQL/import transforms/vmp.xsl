<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="text"/>
	<!-- <xsl:template match="VMPS/VMP[not(INVALID)]"> -->
	<xsl:template match="VMPS/VMP[not(INVALID) and (not(NON_AVAILCD) or (NON_AVAILCD='0000'))]">
		<xsl:value-of select="VPID"/>, <xsl:value-of select="VTMID"/>, "<xsl:value-of select="NM"/>", <xsl:value-of select="PRES_STATCD"/>, <xsl:value-of select="UDFS"/>, <xsl:value-of select="UDFS_UOMCD"/>, <xsl:value-of select="UNIT_DOSE_UOMCD"/>
		<xsl:text>&#xa;</xsl:text>
	</xsl:template>
	<xsl:template match="@*|node()">
		<xsl:apply-templates select="@*|node()"/>
	</xsl:template>
</xsl:stylesheet>
