<?php

/**
* xml file for FlashGallery free flash slideshow http://www.flashgallery.org/
* @package RSGallery2
* @author Daniël Tulp <mail@danieltulp.nl>
*/
class rsgXmlGalleryTemplate_SimpleFlash extends rsgXmlGalleryTemplate_generic{

    function getName(){
        return 'SimpleFlash';
    }
    function prepare(){
        $this->output = '';
		//$descr=rsgImagesItem::rsgImagesItem->descr( $img['name'] );
        $this->output = '';
        $this->output .= "<gallery>\n";
		foreach( $this->gallery->items() as $img ){
			$this->output .= "<image title=\"";
			$this->output .= $img['name'];
			$this->output .= "\" main=\"";
			$this->output .= imgUtils::getImgDisplay( $img['name'] );
			$this->output .= "\" thmb=\"";
			$this->output .= imgUtils::getImgThumb( $img['name'] );
			$this->output .= "\" />\n";
		}
		$this->output .= "</gallery>";
    }
}
?>