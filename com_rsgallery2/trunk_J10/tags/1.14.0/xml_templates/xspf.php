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
    	$this->output = '';
    	$tempAudio = Array();
		$albumArt = '';
		
		$galleryName = $this->gallery->name;	
		foreach ( $this->gallery->itemRows() as $audio ) {
			$mimeType = MimeTypes::getMimeType(audioUtils::getAudio( $audio['name'] ));
			if ( ( $mimeType == 'image/jpeg' || $mimeType == 'image/gif' || $mimeType == 'image/png')) {
				$albumArt = '<image>';
					$albumArt .= imgUtils::getImgDisplay( $audio['name'] );
				$albumArt .= '</image>';
			} else {
				array_push ($tempAudio, array( 'title' => $audio['name'], 'location' => audioUtils::getAudio( $audio['name'] ),
					'descr' => $audio['descr'] ));
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
    	
    	$this->output .= '<playlist version="0" xmlns="http://xspf.org/ns/0">';
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
