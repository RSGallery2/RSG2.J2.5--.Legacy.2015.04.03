<?php

/**
* xml file for FlashGallery free flash slideshow http://www.flashgallery.org/
* @package RSGallery2
* @author Daniël Tulp <mail@danieltulp.nl>
*/
class rsgXmlGalleryTemplate_FlashGallery extends rsgXmlGalleryTemplate_generic{

    function getName(){
        return 'FlashGallery';
    }
    function prepare(){
        $this->output = '';
		//$descr=rsgImagesItem::rsgImagesItem->descr( $img['name'] );
        $this->output = '';
        $this->output .= "<images>\n";
		foreach( $this->gallery->items() as $img ){
			$this->output .= "<pic>\n<image>".imgUtils::getImgDisplay( $img['name'] );
			$this->output .= "</image>\n<thumbnail>";
			$this->output .= imgUtils::getImgThumb( $img['name'] );
			$this->output .= "</thumbnail>\n<caption>test</caption>\n</pic>\n";
		}
		$this->output .= "</images>";
    }
}
?>