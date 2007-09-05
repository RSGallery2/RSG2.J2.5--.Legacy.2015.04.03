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
class rsgDisplay_tables_wToddFlash extends rsgDisplay_tables{
	
	function showThumbs(){
		if( !$this->gallery)
			return;
			
		$gid = $this->gallery->id;
		echo <<<EOD
<center>
	<object type="application/x-shockwave-flash" data="/components/com_rsgallery2/flash/todd_dominey.swf?xmlName=/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey%26gid%3D$gid" width="400" height="400" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="/components/com_rsgallery2/flash/todd_dominey.swf?/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dtodd_dominey%26gid%3D$gid" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
	</object>
</center>
EOD;
	}
}