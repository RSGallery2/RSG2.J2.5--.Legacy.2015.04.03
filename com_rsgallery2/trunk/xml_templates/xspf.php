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
		
		$this->output .= '<playlist version="0" xmlns="http://xspf.org/ns/0">';
		$this->output .= '<trackList>';
		foreach ( $this->gallery->itemRows() as $audio ) {
			$this->output .= '<track>';
				$this->output .= '<title>';
					$this->output .= $audio['name'];
				$this->output .= '</title>';
				$this->output .= '<location>';
					$this->output .= audioUtils::getAudio( $audio['name'] );
				$this->output .= '</location>';
			$this->output .= '</track>';
		}
		$this->output .= '</trackList>';
		$this->output .= '</playlist>';
    }
}
?>
