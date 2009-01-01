<?php

/**
* Creates a Google compatible sitemap for a given gallery
* Call with &gid=GALLERYNUMBER
* example: http://www.fantasyartwork.net/index.php?option=com_rsgallery2&task=xml&xmlTemplate=gadget&gid=17
*
*	Provides XML for a random image for Google Gadget

CREATE A GADGET WITH THIS CODE EXAMPLE
************************************************
<?xml version="1.0" encoding="UTF-8" ?>
<Module>
  <ModulePrefs title="Fantasy Art v10" height="250" scaling="false" />

<Content type="url" href="http://dev.fantasyartwork.net/index.php?option=com_rsgallery2&amp;task=xml&amp;xmlTemplate=gadget" /> 
</Module>
*************************************************

*/

class rsgXmlGalleryTemplate_gadget extends rsgXmlGalleryTemplate_generic {
    var $gallery;

	// deprecated
    var $output;


    /**
        class constructor
        @param rsgGallery object
    **/
    function rsgXmlGalleryTemplate_gadget ( $gallery  )  {
     	global $rsgConfig;
		global $database, $mosConfig_live_site;
		$thumbdir = $mosConfig_live_site . "/images/rsgallery/thumb/";
		$urlroot= $mosConfig_live_site. "/index.php?option=com_rsgallery2";
		$this->gallery = $gallery;
$query="SELECT * FROM #__rsgallery2_files WHERE published='1' ORDER BY rand() LIMIT 2 ";
		$database->setQuery($query);
		$randomimage = $database->loadObjectList();
		
		
	?>
    <div align="center"> <a href="<?php echo $this->output .= "$urlroot"."&amp;page=inline" . "&amp;" . 'id='. $randomimage[0]->id ; ?>" target="_blank"><img src="<?php echo $thumbdir . $randomimage[0]->name . '.jpg'; ?>" height="100" /></a>
      <a href="<?php echo $this->output .= "$urlroot"."&amp;page=inline" . "&amp;" . 'id='. $randomimage[1]->id ; ?>" target="_blank"><img src="<?php echo $thumbdir . $randomimage[1]->name . '.jpg'; ?>" height="100" /></a>
  
	</div>
        <?php

		// These variables will be in the template parameters eventually
		//$this->dateformat = "Y-m-d";
		//$this->dateformat = $this->params->get('DateFormat');	
		//$this->IncludeRootGallery = 1;
		//$this->IncludeRootGallery = $this->params->get('IncludeRootGallery');
	}

    /**
        Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
    **/
   function prepare() {
//		global $database, $mosConfig_live_site;
//		$urlroot= $mosConfig_live_site. "/index.php?option=com_rsgallery2";
//		$thumbdir = $mosConfig_live_site . "/images/rsgallery/thumb/";
/*
		$this->output = '';
echo $this->output .= "<head>"."\n";
echo $this->output .= "<title>Untitled Document</title>"."\n";
echo $this->output .= "</head>"."\n";
echo $this->output .= "<body>"."\n";
echo $this->output .= "Just a final 22 test"."\n";
echo $this->output .= "</body>"."\n";
echo $this->output .= "</html>"."\n";
echo $this->output .= "\n";*/
	
		
		
// Create a gadget for google
	
}  //END PREPARE FUNCTION

    function printHead(){



				
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
