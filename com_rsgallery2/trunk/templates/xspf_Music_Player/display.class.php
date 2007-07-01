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
class rsgDisplay_xspf_Music_Player extends rsgDisplay{
	function mainPage(){
		
		$gid = $this->gallery->id;
		if ( !$gid ) return;
		
		$this->showPlayer( $gid );
	}
	
	function showPlayer( $gid ) {
		global $mosConfig_live_site;
		echo <<<EOD
		<center>
	<object type="application/x-shockwave-flash" width="400" height="170"
data="$mosConfig_live_site/components/com_rsgallery2/flash/xspf_player.swf?playlist_url=http://localhost/grndlvl/joomla/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dxspf%26gid%3D3">
		<param name="movie" 
value="$mosConfig_live_site/components/com_rsgallery2/flash/xspf_player.swf?playlist_url=http://localhost/grndlvl/joomla/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dxspf%26gid%3D3" />
	</object>
	</center>
EOD;
	}

}