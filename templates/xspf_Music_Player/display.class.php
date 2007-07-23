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
		$gallery = $this->gallery;
		$this->showGalleries( $gallery );

		foreach( $gallery->kids() as $kid ){
			$this->showGalleries( $kid );
		}
	}
	
	function showGalleries ( $gallery ) {
		if ( is_object( $gallery ) ) {
			foreach( $gallery->items() as $item ) {
				if( is_a( $item,'rsgItem_audio' ) ) {
					$show = true;
				}
			}
			
			$gid = $gallery->id;
			if ( !$gid ) return;
			
			if ( $show ) {
				$gname = $gallery->name;
				$items = $gallery->items();
				
				$player = $this->getPlayer();
				
				$this->showPlayer( $gid, $gname, $player );				
			} else {
				echo 'It does not seem there are any audio files in the gallery. I believe that the contents of this gallery will be better shown using a different gallery <hr />';
				return;
			}	
		} else {
			return;
		}
	}
	
	function getPlayer () {
		$tmpplayer = mosGetParam( $_GET, 'player' );
		
		$player = array();
		
		switch ( $tmpplayer ) {
			case 'button':
				$player['player'] = 'musicplayer';
				$player['width'] = '17';
				$player['height'] = "17";
				break;
			case 'slim':
				$player['player'] = 'xspf_player_slim';
				$player['width'] = '400';
				$player['height'] = "15";
				break;
			default:
				$player['player'] = 'xspf_player';
				$player['width'] = '400';
				$player['height'] = "170";
		}
			return $player;
	}
	
	function showPlayer( $gid, $gname, $player ) {
		global $mosConfig_live_site;
		
		$player_type = $player['player'];
		$width = $player['width'];
		$height = $player['height'];
		
		echo <<<EOD
		<div align="center" >
		<h2 class="xspf_gallery_name">$gname</h2>
	<object type="application/x-shockwave-flash" width="$width" height="$height"
data="$mosConfig_live_site/components/com_rsgallery2/flash/xspf/$player_type.swf?playlist_url=$mosConfig_live_site/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dxspf%26gid%3D$gid">
		<param name="movie" 
value="$mosConfig_live_site/components/com_rsgallery2/flash/xspf/$player_type.swf?playlist_url=$mosConfig_live_site/index.php%3Foption%3Dcom_rsgallery2%26task%3Dxml%26xmlTemplate%3Dxspf%26gid%3D$gid" />
	</object>
	</div>
	<div class="gallery_space">&nbsp;</div>
EOD;
	}

}