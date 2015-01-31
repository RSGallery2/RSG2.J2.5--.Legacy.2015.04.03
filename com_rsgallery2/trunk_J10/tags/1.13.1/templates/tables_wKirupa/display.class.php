<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @author RSG2 Team
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

/**
 * Template class for RSGallery2
 */
class rsgDisplay_tables_wKirupa extends rsgDisplay_tables{
	
	function showThumbs(){
	global $mosConfig_live_site;
		if( !$this->gallery)
			return;
			
		$gid = $this->gallery->id;
		echo <<<EOD
<center>
	<object type="application/x-shockwave-flash" data= "$mosConfig_live_site/components/com_rsgallery2/flash/kirupa_flash_xml_gallery.swf?xmlName=$mosConfig_live_site/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3DKirupa%26gid%3D$gid" width="500" height="500" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value= "$mosConfig_live_site/components/com_rsgallery2/flash/kirupa_flash_xml_gallery.swf?$mosConfig_live_site/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3DKirupa%26gid%3D$gid" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
	</object>
</center>
EOD;
	}
}