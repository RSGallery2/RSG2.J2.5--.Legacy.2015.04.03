<?xml version="1.0" encoding="UTF-8"?>

<!-- This file is part of DobuDish                                           -->

<!-- DobuDish is free software; you can redistribute it and/or modify        -->
<!-- it under the terms of the GNU General Public License as published by    -->
<!-- the Free Software Foundation; either version 2 of the License, or       -->
<!-- (at your option) any later version.                                     -->

<!-- DobuDish is distributed in the hope that it will be useful,             -->
<!-- but WITHOUT ANY WARRANTY; without even the implied warranty of          -->
<!-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           -->
<!-- GNU General Public License for more details.                            -->

<!-- You should have received a copy of the GNU General Public License       -->
<!-- along with DobuDish; if not, write to the Free Software                 -->
<!-- Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA -->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:fo="http://www.w3.org/1999/XSL/Format"
                version='1.0'>

	<xsl:import href="../../../system/custom-xsl/fo-book.xsl"/>
	<xsl:import href="common.xsl"/>

	<!-- Next line(s) added by Mirjam -->
	<!-- See http://docbook.sourceforge.net/release/xsl/1.75.1/doc/fo/index.html for a list -->
	<xsl:param name="body.font.master">10</xsl:param><!-- Changes the font size of the body -->

	<!-- Title: font-->
	<xsl:param name="title.font.family">sans-serif</xsl:param><!-- Changes default font from Georgia to Arial(like)-->
	
	<!-- Title: font size + space before + font-weight --> 
	<xsl:attribute-set name="section.title.properties">
		<xsl:attribute name="font-family">
			<xsl:value-of select="$title.font.family"/>
		</xsl:attribute>
		<xsl:attribute name="font-weight">bold</xsl:attribute>
		<!-- font size is calculated dynamically by section.heading template -->
		<xsl:attribute name="keep-with-next.within-column">always</xsl:attribute>
		<xsl:attribute name="space-before.minimum">0.4em</xsl:attribute><!-- was: 0.8e -->
		<xsl:attribute name="space-before.optimum">0.6em</xsl:attribute><!-- was: 1.0em -->
		<xsl:attribute name="space-before.maximum">0.8em</xsl:attribute><!-- was: 1.2em -->
		<xsl:attribute name="text-align">start</xsl:attribute>
		<xsl:attribute name="start-indent">		
			<xsl:value-of select="$title.margin.left"/>
		</xsl:attribute>
	</xsl:attribute-set>
	
	<!-- Paragraph spacing -->
	<xsl:attribute-set name="normal.para.spacing">
		<xsl:attribute name="space-before.minimum">0.6em</xsl:attribute>
		<xsl:attribute name="space-before.optimum">0.8em</xsl:attribute>
		<xsl:attribute name="space-before.maximum">1.0em</xsl:attribute>
	</xsl:attribute-set>
	
	<!-- Body: font -->
	<xsl:param name="body.font.family">sans-serif</xsl:param>

	<!-- Section heading font sizes -->
	<xsl:attribute-set name="section.title.level1.properties">
	  <xsl:attribute name="font-size">
		<xsl:value-of select="$body.font.master * 1.4"/><!-- was 2.0736 -->
		<xsl:text>pt</xsl:text>
	  </xsl:attribute>
	</xsl:attribute-set>
	<xsl:attribute-set name="section.title.level2.properties">
	  <xsl:attribute name="font-size">
		<xsl:value-of select="$body.font.master * 1.2"/><!-- was 1.728 -->
		<xsl:text>pt</xsl:text>
	  </xsl:attribute>
	</xsl:attribute-set>
	<xsl:attribute-set name="section.title.level3.properties">
	  <xsl:attribute name="font-size">
		<xsl:value-of select="$body.font.master * 1"/><!-- was 1.44 -->
		<xsl:text>pt</xsl:text>
	  </xsl:attribute>
	</xsl:attribute-set>

	<!-- Style the title element of formal objects such as a figure -->
	<xsl:attribute-set name="formal.title.properties" use-attribute-sets="normal.para.spacing">
		<xsl:attribute name="font-weight">normal</xsl:attribute>
		<xsl:attribute name="font-style">italic</xsl:attribute>
		<xsl:attribute name="font-size">
			<xsl:value-of select="$body.font.master * 1.0"></xsl:value-of><!-- was: 1.2 -->
			<xsl:text>pt</xsl:text>
		</xsl:attribute>
		<xsl:attribute name="hyphenate">false</xsl:attribute>
		<xsl:attribute name="space-after.minimum">1.4em</xsl:attribute><!-- was 0.4em -->
		<xsl:attribute name="space-after.optimum">1.6em</xsl:attribute><!-- was 0.6em -->
		<xsl:attribute name="space-after.maximum">1.8em</xsl:attribute><!-- was 0.8em -->
	</xsl:attribute-set>
	
	<!-- What space do you want between list items? -->
	<xsl:attribute-set name="list.item.spacing">
		<xsl:attribute name="space-before.optimum">0.0em</xsl:attribute>
		<xsl:attribute name="space-before.minimum">0.0em</xsl:attribute>
		<xsl:attribute name="space-before.maximum">0.1em</xsl:attribute>
	</xsl:attribute-set>
	
	<!-- What spacing do you want before and after lists? -->
	<xsl:attribute-set name="list.block.spacing">
		<xsl:attribute name="space-before.optimum">0.0em</xsl:attribute>
		<xsl:attribute name="space-before.minimum">0.0em</xsl:attribute>
		<xsl:attribute name="space-before.maximum">0.2em</xsl:attribute>
		<xsl:attribute name="space-after.optimum">1em</xsl:attribute>
		<xsl:attribute name="space-after.minimum">0.8em</xsl:attribute>
		<xsl:attribute name="space-after.maximum">1.2em</xsl:attribute>
	</xsl:attribute-set>

	<!-- The outer page margin-->
	<xsl:param name="page.margin.outer">
		<xsl:choose>
			<xsl:when test="$double.sided != 0">1.0in</xsl:when>
			<xsl:otherwise>1.2in</xsl:otherwise>
		</xsl:choose>
	</xsl:param>

	<!-- Page margins: top and bottom -->
	<xsl:param name="page.margin.top">2.0cm</xsl:param>
	<xsl:param name="page.margin.bottom">2.5cm</xsl:param>


	
	<!-- Previous line(s) added by Mirjam -->
</xsl:stylesheet>
