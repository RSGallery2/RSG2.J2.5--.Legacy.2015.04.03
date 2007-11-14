<?php

/**
* XSPF (SPIFF) XML Template
* XSPF is the XML format for sharing playlists.
* http://www.xspf.org/
* @package RSGallery2
* @author Jonathan DeLaigle <delaigle.jonathan@gmail.com>
*/
class rsgXmlGalleryTemplate_xspf extends rsgXmlGalleryTemplate_generic{

function getName(){
	return 'XSPF("spiff") XSPF is the XML format for sharing playlists.';
}

function prepare(){
	global $rsgConfig, $mosConfig_live_site;
	$audioPath 		= $rsgConfig->get("imgPath_original");
	$tempAudio 		= array();
	$galleryName 	= $this->gallery->name;

	foreach ( $this->gallery->items() as $audio ) {
		if ( is_a( $audio, 'rsgItem_audio') ) {
			$original = $audio->original();
			array_push 	($tempAudio, array( 'title' => $audio->name, 'location' => $original->url() ,
				'descr' => $audio->descr ));
		} else {
			$thumb = $audio->thumb();
			$albumArt = '<image>';
				$albumArt .= $thumb->url();
			$albumArt .= '</image>';
		}
	}

	if ( count( $tempAudio ) ) {
		$this->buildXML($albumArt, $tempAudio, $galleryName);
	} else {
		return;
	}
}

	function buildXML( $albumArt_temp, $audio_temp, $galleryName ) {
		$albumArt = '';
		if ( $albumArt_temp ) { 
			$albumArt = $albumArt_temp;
		}
	
		$this->output .= '<playlist version="0" xmlns="http://xspf.org/ns/0/">';
		$this->output .= '<title>';
			$this->output .= $galleryName;
		$this->output .= '</title>';
		$this->output .= '<trackList>';
	
		foreach ( $audio_temp as $audio ) {
			$this->output .= '<track>';
				$this->output .= '<title>';
					$this->output .= $audio['title'];
				$this->output .= '</title>';
	
				$this->output .= '<location>';
					$this->output .= $audio['location'];
				$this->output .= '</location>';
	
			if( $audio['descr'] ) {
				$this->output .= '<info>';
					$this->output .= $audio['descr'];
				$this->output .= '</info>';
			}
	
			$this->output .= $albumArt;
	
			$this->output .= '</track>';
		}
	
		$this->output .= '</trackList>';
		$this->output .= '</playlist>';
	
	}
}
?>

