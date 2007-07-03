<?php

/**
* xml file for Slide Show Pro http://www.slideshowpro.net/
* @package RSGallery2
* @author Anon. <josiah@WhaleHosting.ca>
*/

class rsgXmlGalleryTemplate_slideshowpro extends rsgXmlGalleryTemplate_generic{
    function getName(){
        return 'SlideShowPro Template';
    }
    
    function prepare(){
        global $rsgConfig, $mosConfig_live_site;
        $imgPath = $rsgConfig->get("imgPath_display");
        $thumbPath = $rsgConfig->get("imgPath_thumb");

        $this->output = "\n";
        $this->output .= "<gallery>";
        //$this->output .= "<br />";
        
        foreach( $this->gallery->kids() as $kid ){
            $title = htmlentities($kid->get("name"));
		    $descr = htmlentities($kid->get("description"));
		    
		    // get  thumbnail item for gallery
		    $thumb = $kid->thumb();
		    if( $thumb ){
				// get thumbnail resource of image item
				$thumb = $thumb->thumb();
				// get full url of thumbnail
				$thumb = $thumb->url();
		    }
		    else $thumb = '';
		    
			$this->output .= "<album id='$title' title='$title' description='$descr' lgPath='$mosConfig_live_site/$imgPath/' tnPath='$mosConfig_live_site/$thumbPath/' tn='$thumb'>";  


            foreach( $kid->items() as $img ){
            	$imgDescr = $img->get("title");
            	
            	// get display resource of image item
				$image = $img->display();
				// name of image item
				$image = $image->name;
				// get just the filename
				$image = pathinfo($image);
				$image = $image["basename"];
            	
                $this->output .= '  <img src="';
                $this->output .= "$image";
                $this->output .= '" caption="';
                $this->output .= $imgDescr;
                $this->output .= "\" />\n";
			}
            $this->output .= "</album>";
		}
        $this->output .= "</gallery>";
	}
               
}