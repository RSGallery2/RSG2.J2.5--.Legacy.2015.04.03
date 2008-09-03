<?php

/**
* Creates a Google compatible sitemap for a given gallery
* Call with &gid=GALLERYNUMBER
* will eventually make it display entire list i gallery id not supplied
*
* @package RSGallery2
* @author Chef Groovy <chefgroovy@gantasyartwork.net>
*/
class rsgXmlGalleryTemplate_xml_google_sitemap {
    var $gallery;

	// deprecated
    var $output;

    /**
        class constructor
        @param rsgGallery object
    **/
    function rsgXmlGalleryTemplate_xml_google_sitemap( $gallery ){
     	$this->gallery = $gallery;
    }

    /**
        Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
    **/
    function prepare() {
		global $database, $mosConfig_live_site;
		$urlroot= $mosConfig_live_site. "/index.php?option=com_rsgallery2";

	$this->output = '';

//CREATE LINK TO GALLERY
			$this->output .= '<url>';
			$this->output .= "<loc>$urlroot"."&amp;" . 'gid='. $this->gallery->id .'</loc>'; 
			$this->output .= '<changefreq>daily</changefreq>' ;
			$this->output .= '</url>';
	
// CREATE FILE LINKS
		foreach( $this->gallery->items() as $img ){

			$this->output .= '<url>';
			$this->output .= "<loc>$urlroot"."&amp;page=inline" . "&amp;" . 'id='. $img->id .'</loc>'; 
			$this->output .= '<changefreq>weekly</changefreq>' ;
			//$this->output .= '<priority>0.5</priority>' ;
//Use this is want full date and time
			//	$this->output .= '<lastmod>'. gmdate('Y-m-d\TH:i:s\Z', strtotime($img->date)) .'</lastmod>' ;
			$this->output .= '<lastmod>'. gmdate("Y-m-d", strtotime($img->date)) .'</lastmod>' ;
			$this->output .= '</url>';
        }
			$this->output .= '</urlset>';
	
	
	}  //END PREPARE

    function printHead(){
		Header("Content-type: text/xml; charset=UTF-8");
		Header("Content-encoding: UTF-8");

		
		echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		
		echo '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"'."\n";
		echo ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n";
		echo ' xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84'."\n";
		echo ' http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">'."\n";
		
    }
    
    /**
        outputs XML
		@deprecated this function is only to support older xml templates
    **/
    function printGallery(){
        if( $this->output )
			echo $this->output;
    }
}
?>
