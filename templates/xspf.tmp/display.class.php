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
class rsgDisplay_xspf extends rsgDisplay{
	function mainPage(){
		$this->showGalleries();
	}

	function showGalleries () {
		global $rsgConfig, $mosConfig_live_site;
		$audioPath 		= $rsgConfig->get("imgPath_original");
		$tempAudio 		= array();
		
		foreach ( $this->gallery->items() as $audio ) {
			if ( $audio->type == 'audio' ) {
				$original = $audio->original();
				array_push 	($tempAudio, array( 'title' => $audio->title, 'location' => $original->url() ,
					'descr' => $audio->descr, 'id' => $audio->id ));
			}
		}

		$this->audio_clips = $tempAudio;
		
		if ( count( $tempAudio ) ) {
			$this->display( 'gallery.php' );
		} else {
			return;
		}
	}

	function getPlayer ($tmpplayer = null) {
		if(mosGetParam( $_GET, 'player' )) {
			$tmpplayer = mosGetParam( $_GET, 'player' );
		}
				
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
	
	function showPlayer( $song_url, $title ) {
		global $mosConfig_live_site;
		$player = $this->getPlayer('slim');
		
		$player_type = $player['player'];
		$width = $player['width'];
		$height = $player['height'];
		
		echo <<<EOD
	<object type="application/x-shockwave-flash" width="$width" height="$height"
data="$mosConfig_live_site/components/com_rsgallery2/flash/xspf/$player_type.swf?song_url=$song_url&song_title=$title">
		<param name="movie" 
value="$mosConfig_live_site/components/com_rsgallery2/flash/xspf/$player_type.swf?song_url=$song_url&song_title=$title" />
	</object>
EOD;
	}
}
