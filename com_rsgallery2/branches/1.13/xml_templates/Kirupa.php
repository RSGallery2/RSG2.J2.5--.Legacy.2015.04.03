<?php

/**
* xml file for FlashGallery free flash slideshow http://www.flashgallery.org/
* @package RSGallery2
* @author Daniël Tulp <mail@danieltulp.nl>
*/
class rsgXmlGalleryTemplate_Kirupa extends rsgXmlGalleryTemplate_generic{

    function getName(){
        return 'Kirupa';
    }
    function prepare(){
	global $rsgImagesItem;
        $this->output = '';
        $this->output .= "<images>\n";
		foreach( $this->gallery->items() as $img ){
			$this->output .= "<pic>\n";
			$this->output .= "<thumb>";
			$this->output .= imgUtils::getImgThumb( $img['name'] );
			$this->output .= "</thumb>\n";
			$this->output .= "<image>";
			$this->output .= imgUtils::getImgDisplay( $img['name'] );
			$this->output .= "</image>\n";
			$this->output .= "<caption>";
			$this->output .= $img['name'];
			$this->output .= "</caption>\n";
			$this->output .= "</pic>\n";
		}
		$this->output .= "</images>";
    }
}
?>