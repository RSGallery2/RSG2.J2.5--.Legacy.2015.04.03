<?php

/**
* xml file for Slide Show Pro http://www.slideshowpro.net/
* @package RSGallery2
* @author Josiah Claassen <josiah@WhaleHosting.ca>
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
		    $thumb = $kid->thumb();
		    $thumb = $thumb->thumb();
		    $thumb = $thumb->name;
			$this->output .= "<album id='$title' title='$title' description='$descr' lgPath='$mosConfig_live_site/$imgPath/' tnPath='$mosConfig_live_site/$thumbPath/' tn='$mosConfig_live_site/$thumbPath/$thumb'>";  


            foreach( $kid->items() as $img ){
            	$imgDescr = $img->("title");
            	
                $this->output .= '  <img src="';
                $this->output .= $thumb;
                $this->output .= '" caption="';
                $this->output .= $imgDescr;
                $this->output .= "\" />\n";
			}
            $this->output .= "</album>";
		}
        $this->output .= "</gallery>";
	}
               
}