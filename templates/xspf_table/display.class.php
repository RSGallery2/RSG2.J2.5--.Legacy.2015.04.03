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
class rsgDisplay_xspf_table extends rsgDisplay{
	function mainPage(){
		$this->showGalleries();
	}

	function showGalleries () {
		global $rsgConfig, $mosConfig_live_site;
		$audioPath 		= $rsgConfig->get("imgPath_original");
		$tempAudio 		= array();
		
		foreach ( $this->gallery->items() as $audio ) {
			if ( is_a( $audio, 'rsgItem_audio') ) {
				$original = $audio->original();
				array_push 	($tempAudio, array( 'title' => $audio->title, 'location' => $original->url() ,
					'descr' => $audio->descr, 'id' => $audio->id ));
			}
		}

		if ( count( $tempAudio ) ) {
			$this->itemsDisplay($tempAudio);
		} else {
			return;
		}
	}

	function itemsDisplay($audio_clips) {
		?>
		<h2 class="xspf_semantic_gallery_title"><?php echo $this->gallery->name?></h2>
		<?php if($this->gallery->description){?><p class="xspf_semantic_gallery_desc"><?php echo $this->gallery->description?></p><?php }?>
		<?php
		$player = $this->getPlayer('slim');
		foreach($audio_clips as $audio_clip){
			?>
			<ul class="xspf_semantic_item">
				<li class="xspf_semantic_item_title"><h3><?php echo $audio_clip['title'];?></h3></li>
				<li class="xspf_semantic_item_player"><?php $this->showPlayer($audio_clip['location'], $audio_clip['title'], $player)?></li>
				<li class="xspf_semantic_item_download"><?php $this->_writeDownloadLink( $audio_clip['id'] );?></li>
				<?php if($audio_clip['descr']){?><li class="xspf_semantic_item_desc"><?php echo $audio_clip['descr'];?></li><?php }?>
				<li class="xspf_semantic_item_spacer" style="clear: right; background: none;">&nbsp;</li>
			</ul>
			<?php		
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
	
	function showPlayer( $song_url, $title, $player ) {
		global $mosConfig_live_site;
		
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



	/**
	 * Write downloadlink for image
	 * @param int image ID
	 * @param string Button or HTML link (button/link)
	 * @return HTML for downloadlink
	 */
	function _writeDownloadLink($id, $showtext = true, $type = 'button') {
	global $rsgConfig, $mosConfig_live_site;
	if ( $rsgConfig->get('displayDownload') ) {
		echo "<div class=\"rsg2-toolbar\">";
		if ($type == 'button') {
			?>
			<a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>">
			<img height="20" width="20" src="<?php echo $mosConfig_live_site;?>/administrator/images/download_f2.png" alt="<?php echo _RSGALLERY_DOWNLOAD?>">
			<?php
			if ($showtext == true) {
				?>
				<br /><span style="font-size:smaller;"><?php echo _RSGALLERY_DOWNLOAD?></span>
				<?php
			}
			?>
			</a>
			<?php
		} else {
			?>
			<a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>"><?php echo _RSGALLERY_DOWNLOAD?></a>
			<?php
		}
		}
	}
}