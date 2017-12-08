<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:eg="http://www.tei-c.org/ns/Examples"
  xmlns:tei="http://www.tei-c.org/ns/1.0"
  xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl"
  xmlns:exsl="http://exslt.org/common"
  xmlns:msxsl="urn:schemas-microsoft-com:xslt"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"
  extension-element-prefixes="exsl msxsl"
  exclude-result-prefixes="xsl tei xd eg fn">

	<xsl:include href="xml-to-string.xsl"/>


	<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>
  <xsl:strip-space elements="*"/>


	<!-- special characters -->
	<xsl:param name="quot"><text>"</text></xsl:param>
	<xsl:param name="apos"><text>'</text></xsl:param>

	<!-- parameters for file paths or URLs -->
	<!-- modify filePrefix to point to files on your own server,
		or to specify a relative path, e.g.:
		<xsl:param name="filePrefix" select="'http://dcl.slis.indiana.edu/teibp'"/>

	-->
	<xsl:param name="filePrefix" select="'http://discovery.civilwargovernors.org/files/xslt/'"/>

  <xsl:key name="ids" match="//*" use="@xml:id"/>

	<xsl:template match="/" name="htmlShell" priority="99">
    <div id="tei_wrapper">
      <xsl:apply-templates/>
      <br />
      <div class="browse-divider"><hr class="star" /></div>
      <br />
      <xsl:choose>
        <xsl:when test="starts-with(/tei:TEI/@xml:id, 'KYR')">
          <xsl:choose>
            <xsl:when test="//tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:respStmt/tei:resp[@n='proof_2']">
              <p><em>Civil War Governors of Kentucky</em> is always evolving. Help us improve the edition by suggesting a correction or addition to this record. </p>
            </xsl:when>
            <xsl:otherwise>
              <p><em>Early Access</em> is a work in progress. Help us improve our edition by suggesting a correction for this document. </p>
            </xsl:otherwise>
          </xsl:choose>              
        </xsl:when>
        <xsl:otherwise>
          <p><em>Civil War Governors of Kentucky</em> is always evolving. Help us improve the edition by suggesting a correction or addition to this record. </p>
        </xsl:otherwise>
      </xsl:choose>
    </div>
	</xsl:template>

  <xsl:template match="@*">
    <xsl:copy/>
  </xsl:template>

  <xsl:template match="*" name="teibp-default">
    <xsl:element name="{local-name()}">
      <xsl:apply-templates select="@*"/>
      <xsl:apply-templates select="node()"/>
    </xsl:element>
  </xsl:template>

	<xsl:template match="tei:teiHeader//tei:title">
		<tei-title>
			<xsl:apply-templates select="@*|node()"/>
		</tei-title>
	</xsl:template>

	<xsl:template match="@xml:id">
		<!-- @xml:id is copied to @id, which browsers can use
			for internal links.
		-->
		<!--
		<xsl:attribute name="xml:id">
			<xsl:value-of select="."/>
		</xsl:attribute>
		-->
		<div><xsl:attribute name="id">
			<xsl:value-of select="."/>
		</xsl:attribute></div>
	</xsl:template>

  <xsl:template match="tei:teiHeader">
  </xsl:template>

  <!-- add line break for each <lb> -->
  <xsl:template match="tei:lb">
    <xsl:apply-templates/><br />
  </xsl:template>

  <!-- replace hi tags with html tags -->
  <xsl:template match="tei:hi">
    <xsl:choose>
			<xsl:when test="@rend='sup'">
        <sup><xsl:apply-templates/></sup>
			</xsl:when>
      <xsl:when test="@rend='sub'">
        <sub><xsl:apply-templates/></sub>
      </xsl:when>
      <xsl:when test="@rend='italic'">
        <em><xsl:apply-templates/></em>
      </xsl:when>
      <xsl:when test="@rend='bold'">
        <strong><xsl:apply-templates/></strong>
      </xsl:when>
      <xsl:when test="@rend='underline'">
        <span style="text-decoration:underline;"><xsl:apply-templates/></span>
      </xsl:when>
      <xsl:when test="@rend='str'">
        <strike><xsl:apply-templates/></strike>
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <!-- wrap notes with greater than and less than characters -->
  <xsl:template match="tei:note">
    <xsl:choose>
      <xsl:when test="@place='header'">
        <xsl:text>&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;</xsl:text>
      </xsl:when>
      <xsl:when test="@place='footer'">
        <xsl:text>&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;</xsl:text>
      </xsl:when>
      <xsl:when test="@place='body'">
        <xsl:text>&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;</xsl:text>
      </xsl:when>
      <xsl:when test="@place='stamp'">
        <xsl:text>&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;</xsl:text>
      </xsl:when>
      <xsl:when test="@place='left margin'">
        <xsl:text>&lt;&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;</xsl:text>
      </xsl:when>
      <xsl:when test="@place='right margin'">
        <xsl:text>&lt; </xsl:text>
        <xsl:apply-templates/>
        <xsl:text> &gt;&gt;</xsl:text>
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <!-- replace gap with [ ] -->
  <xsl:template match="tei:gap">
    <xsl:text>[ ]</xsl:text>
  </xsl:template>

  <!-- replace <unclear>some text</unclear> with [some text] and <unclear /> with [...] -->
  <xsl:template match="tei:unclear">
    <xsl:choose>
      <xsl:when test="string-length(.) &gt; 0">
        <xsl:text>[</xsl:text>
        <xsl:apply-templates/>
        <xsl:text>]</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:text>[...]</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <!-- figure: hr, postmark, seal -->
  <xsl:template match="tei:figure">
    <xsl:choose>
      <xsl:when test="@type='seal'">
        <xsl:choose>
          <xsl:when test="string-length(.) &gt; 0">
            <xsl:text>{</xsl:text>
            <xsl:apply-templates/>
            <xsl:text>}</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>{Seal}</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="@type='postmark'">
        <xsl:choose>
          <xsl:when test="string-length(.) &gt; 0">
            <xsl:text>{</xsl:text>
            <xsl:apply-templates/>
            <xsl:text>}</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>{Postmark}</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="@type='hr'">
        <xsl:apply-templates/><hr />
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <!-- stamps -->
  <xsl:template match="tei:stamp">
    <xsl:choose>
      <xsl:when test="@type='revenue'">
        <xsl:text>{Revenue Stamp}</xsl:text>
      </xsl:when>
      <xsl:when test="@type='postage'">
        <xsl:text>{Postage Stamp}</xsl:text>
      </xsl:when>
      <xsl:when test="@type='clerical'">
        <xsl:text>{Clerical Stamp}</xsl:text>
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <!-- tables -->
  <xsl:template match="tei:table">
   <table><xsl:apply-templates/></table>
  </xsl:template>

  <xsl:template match="tei:row">
   <tr><xsl:apply-templates/></tr>
  </xsl:template>

  <xsl:template match="tei:cell">
   <td><xsl:apply-templates/></td>
  </xsl:template>

	<xsl:template match="eg:egXML">
		<xsl:element name="{local-name()}">
			<xsl:apply-templates select="@*"/>

			<xsl:call-template name="xml-to-string">
				<xsl:with-param name="node-set">
					<xsl:copy-of select="node()"/>
				</xsl:with-param>
			</xsl:call-template>
		</xsl:element>
	</xsl:template>

<!-- add a white space in empty milestone so it doesn't wrap around other elements -->
  <xsl:template match="tei:milestone">
    <xsl:variable name="milenum" select="@n" />
   <milestone>
     <xsl:attribute name="n">
       <xsl:value-of select="$milenum" />
     </xsl:attribute>
     <xsl:text> </xsl:text>
   </milestone>
  </xsl:template>

  <!-- wrap content following cb elements in a div, with a class indicating the number of columns in the preceding milestone n attribute (if milestone n=2, then div class=column1of2 or div class=column2of2) -->
  <xsl:template match="tei:p[tei:cb]">
    <xsl:choose>
      <xsl:when test="count(tei:cb) = number(tei:cb/preceding::tei:milestone/@n)">
        <div>
          <xsl:if test="preceding-sibling::tei:p[tei:cb]">
            <xsl:attribute name="class">teip</xsl:attribute>
          </xsl:if>
          <xsl:apply-templates/>
        </div>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="tei:p/tei:cb">
    <xsl:variable name="milenum" select="preceding::tei:milestone/@n" />
    <xsl:variable name="className" select="concat('column', @n, 'of', $milenum)" />
    <xsl:if test="not(@n = '1')"> <!-- if it's not the first, close the tag for the previous one -->
      <xsl:text disable-output-escaping="yes">&lt;/div&gt;</xsl:text>
    </xsl:if>
    <xsl:text disable-output-escaping="yes">&lt;</xsl:text>div class="<xsl:value-of select="$className"/>"<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
  </xsl:template>

  <!-- wrap the nodes following the last milestone element in a div, with a class indicating the number of columns (if milestone n=1, then div class=column1) -->
  <xsl:template match="tei:milestone">
    <xsl:if test="@n = '1' and preceding::tei:cb[@n]">
      <xsl:text disable-output-escaping="yes">&lt;/div&gt;</xsl:text>
    </xsl:if>
  </xsl:template>

  <xsl:template match="tei:pb">
   <pb>
     <xsl:text> </xsl:text>
   </pb>
  </xsl:template>

	<xsl:template match="eg:egXML//comment()">
		<xsl:comment><xsl:value-of select="."/></xsl:comment>
	</xsl:template>

  <xsl:template match="tei:persName | tei:orgName | tei:placeName ">
    <a href="{substring(@ref, 6)}"><xsl:value-of select='.'/></a>
  </xsl:template>

</xsl:stylesheet>
