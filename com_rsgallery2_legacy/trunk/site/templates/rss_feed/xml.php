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
*
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
		
//					// GET TEMPLATE PARAMS
//
//				WILL ADD TEMPLATE PARAMS LATER
//				
//				$template = preg_replace( '#\W#', '', rsgInstance::getVar( 'xmlTemplate', 'meta' ) );
//				$template = strtolower( $template );
//
//				// load parameters for template
//				jimport('joomla.filesystem.file');
//				// Read the ini file
//				$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'params.ini';
//				if (JFile::exists($ini)) {
//					$content = JFile::read($ini);
//				} else {
//					$content = null;
//				}
//				$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template .DS.'templateDetails.xml';
//				$this->params = new JParameter($content, $xml, 'rsgTemplate');


// Set these to match your site

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


	// GET ALL IMAGES THAT ARE PUBLISTED
	    	$query = ("SELECT * FROM #__rsgallery2_files WHERE published='1' AND (date + INTERVAL 3 DAY) >= NOW()");
			$database->setQuery($query);
			$filelist = $database->loadObjectList();

			foreach ($filelist as $img) {
											
				$this->output .= '<item>' . "\n";
				$this->output .= '<title>'.$this->_getGalleryName( $img->gallery_id ) . " - " . $img->title.'</title>' . "\n";
				$this->output .= '<link>' . $urlroot. '&amp;page=inline&amp;id='. $img->id .'</link>' . "\n";
				$this->output .= '<pubDate>' . gmdate($this->dateformat, strtotime($img->date)) .'</pubDate>' . "\n";
				$this->output .= '<description><![CDATA[<a href="'.$urlroot. '&amp;page=inline&amp;id='. $img->id .'"><img src="'.$mosConfig_live_site.'/images/rsgallery/thumb/'. $img->name.'.jpg"/>]]></description>' . "\n";
				$this->output .= '<guid isPermaLink="true">'.$urlroot. '&amp;page=inline' ."&amp;" . 'id='. $img->id.'</guid>';
				$this->output .= '</item>' . "\n";
			}
			$this->output .= '<atom:link href="'. $urlroot . '&amp;task=xml&amp;xmlTemplate=rss_feed' .'" rel="self" type="application/rss+xml" />';
			$this->output .= '</channel>' . "\n";
			$this->output .= '</rss>' . "\n";

	
}  //END PREPARE FUNCTION

    function printHead(){
		global $rsgConfig;
		global $mosConfig_live_site;
		
		// have to use rss+xml content to get firefox to automatically detect rss
		Header("Content-type: application/rss+xml; charset=UTF-8");
		Header("Content-encoding: UTF-8");

		echo '<?xml version="1.0" ?>'."\n";
		echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
		echo '<channel>' . "\n";
		echo '<title>'.$this->FeedTitle.'</title>' . "\n";
		echo '<link>'. $mosConfig_live_site . '</link>' . "\n";
		echo '<description>'.$this->SiteDescription.'</description>' . "\n";
		echo '<lastBuildDate>'.date("r").'</lastBuildDate>' . "\n";
		echo '<language>en-us</language>' . "\n";
		echo '<pubDate>'.date("r").'</pubDate>' . "\n";
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
	
	
	function _getGalleryName($id)
	{
		global $database;
		$query="SELECT name FROM #__rsgallery2_galleries WHERE id=" . $id;
		$database->setQuery($query);
		$galname = $database->loadResult();

		return $galname;
	}
	
}
?>
