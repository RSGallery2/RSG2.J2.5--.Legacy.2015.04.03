<?php

/**
* Creates an RSS feed for specified gallery
* Call with &gid=GALLERYNUMBER
* example: http://YOUR.SITE.NAME/index.php?option=com_rsgallery2&task=xml&xmlTemplate=rss_feedp&gid=17
*
* if don't supply gallery number, will send whole list
* @package RSGallery2
* @author Chef Groovy <chefgroovy@gantasyartwork.net>
*
* TODO 
*	9/13/08 - Convert from google sitemap
*/

class rsgXmlGalleryTemplate_rss_feed extends rsgXmlGalleryTemplate_generic {
    var $gallery;

	// deprecated
    var $output;


    /**
        class constructor
        @param rsgGallery object
    **/
    function rsgXmlGalleryTemplate_rss_feed( $gallery  ){
     	global $rsgConfig;
		$this->gallery = $gallery;
					// GET TEMPLATE PARAMS
				$template = preg_replace( '#\W#', '', rsgInstance::getVar( 'xmlTemplate', 'meta' ) );
				$template = strtolower( $template );

				// load parameters for template
				jimport('joomla.filesystem.file');
				// Read the ini file
				$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'params.ini';
				if (JFile::exists($ini)) {
					$content = JFile::read($ini);
				} else {
					$content = null;
				}
				$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template .DS.'templateDetails.xml';
				$this->params = new JParameter($content, $xml, 'rsgTemplate');
		
		// These variables will be in the template parameters eventually
		//$this->dateformat = "Y-m-d";
		$this->dateformat = $this->params->get('DateFormat');	
		//$this->IncludeRootGallery = 1;
		$this->IncludeRootGallery = $this->params->get('IncludeRootGallery');
	}

    /**
        Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
    **/
    function prepare() {
		global $database, $mosConfig_live_site;
		$urlroot= $mosConfig_live_site. "/index.php?option=com_rsgallery2";

		$this->output = '';


		if ( ! $this->gallery->id )	// check if not gallery specified
		{
		
// No Gallery Specified Mode
	
	// Create link to root
	if ($this->IncludeRootGallery == 1) {
			$this->output .= "<url>";
			$this->output .= "<loc>" . $urlroot . "</loc>"; 
			$this->output .= '<changefreq>daily</changefreq>' ;
			$this->output .= '</url>';
	}

	// GET ALL IMAGES THAT ARE PUBLISTED
	    	$query = ("SELECT * FROM #__rsgallery2_files WHERE published='1'");
			$database->setQuery($query);
			$filelist = $database->loadObjectList();

			foreach ($filelist as $img) {
				$this->output .= '<url>';
				$this->output .= "<loc>$urlroot"."&amp;page=inline" . "&amp;" . 'id='. $img->id .'</loc>'; 
				$this->output .= '<changefreq>weekly</changefreq>' ;
				$this->output .= '<lastmod>'. gmdate($this->dateformat, strtotime($img->date)) .'</lastmod>' ;
				$this->output .= '</url>';
			}
			$this->output .= '</urlset>';
	}   // end if no gallery specified
	
	else 	{

// Create list for specific gallery

	//CREATE LINK TO GALLERY
	if ($this->IncludeRootGallery == 1) {
			$this->output .= '<url>';
			$this->output .= "<loc>$urlroot"."&amp;" . 'gid='. $this->gallery->id .'</loc>'; 
			$this->output .= '<changefreq>daily</changefreq>' ;
			if (count($this->gallery->items()) > 0) 
				$this->output .= '<priority>1.0</priority>' ;
			$this->output .= '</url>';
	}
	
// CREATE FILE LINKS
		foreach( $this->gallery->items() as $img ){

			$this->output .= '<url>';
			$this->output .= "<loc>$urlroot"."&amp;page=inline" . "&amp;" . 'id='. $img->id .'</loc>'; 
			$this->output .= '<changefreq>weekly</changefreq>' ;
			$this->output .= '<priority>0.5</priority>' ;
//Use this is want full date and time
			//	$this->output .= '<lastmod>'. gmdate('Y-m-d\TH:i:s\Z', strtotime($img->date)) .'</lastmod>' ;
			$this->output .= '<lastmod>'. gmdate($this->dateformat, strtotime($img->date)) .'</lastmod>' ;
			$this->output .= '</url>';
        }
			$this->output .= '</urlset>';
	 }
	
}  //END PREPARE FUNCTION

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
		Anyone know how to do it the other way?????
    **/
    function printGallery(){
        if( $this->output )
			echo $this->output;
    }
}
?>
