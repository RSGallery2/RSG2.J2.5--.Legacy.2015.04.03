<?php

/**
* CAUTION  EXREME BETA
************************
* Creates an RSS feed for newest images for last 3 days
* example: http://YOUR.SITE.NAME/index.php?option=com_rsgallery2&task=xml&xmlTemplate=rss_feed
*
* if don't supply gallery number, will send whole list
* @package RSGallery2
* @author Chef Groovy <chefgroovy@gantasyartwork.net>
*
* TODO 
*	9/20/08 - Clean Up
*	9/20/08 - Add link to gallery and description
*	9/20/08 - Add check for ACL
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
		$this->dateformat = "r";
		//$this->dateformat = $this->params->get('DateFormat');	
		$this->IncludeRootGallery = 0;
		//$this->IncludeRootGallery = $this->params->get('IncludeRootGallery');
		$this->SiteDescription = "This is the latest from Fantasy Artwork";
		$this->FeedTitle = "Fantasy Artwork";
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
	

	// GET ALL IMAGES THAT ARE PUBLISTED
	    	$query = ("SELECT * FROM #__rsgallery2_files WHERE published='1' AND (date + INTERVAL 3 DAY) >= NOW()");
			$database->setQuery($query);
			$filelist = $database->loadObjectList();

				
				
			foreach ($filelist as $img) {
				
				$this->output .= '<item>';
				$this->output .= '<title>'.$img->title.'</title>';
				$this->output .= '<link>' . $urlroot. '&amp;page=inline' ."&amp;" . 'id='. $img->id .'</link>';
				$this->output .= '<pubDate>' . gmdate($this->dateformat, strtotime($img->date)) .'</pubDate>';
				$this->output .= '<description><![CDATA[<img src="'.$mosConfig_live_site.'/images/rsgallery/thumb/'. $img->name.'.jpg"]]>.</description>';
				$this->output .= '</item>';
				
			}
			$this->output .= '</channel>';
			$this->output .= '</rss>';
	}   // end if no gallery specified
	
	else 	{

// Create list for specific gallery
// This is still formated to google sitemap mode

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
		global $rsgConfig;
		
		// have to use rss+xml content to get firefox to automatically detect rss
		Header("Content-type: application/rss+xml; charset=UTF-8");
		Header("Content-encoding: UTF-8");

		
		echo '<?xml version="1.0" ?>'."\n";


		echo '<rss version="2.0">';
		echo '<channel>';
		echo '<title>'.$this->FeedTitle.'</title>';
		echo '<link>'.$urlroot.'</link>';
		echo '<description>'.$this->SiteDescription.'</description>';
		echo '<lastBuildDate>Mon, 20 Sep 2008 18:37:00 GMT</lastBuildDate>';
		echo '<language>en-us</language>';
		echo '<pubDate>Tue, 10 Jun 2003 04:00:00 GMT</pubDate>';
		
		
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
