<?php
/**
* Main task switch for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Access Denied.' );

/**
 * This is the main task switch where we decide what to do.
 */
switch( rsgInstance::getVar( 'rsgOption', '' )) {
    case 'rsgComments':
        require_once(JPATH_RSGALLERY2_SITE . DS . 'lib' . DS . 'rsgcomments' . DS . 'rsgcomments.php');
        break;
    default:
		switch( rsgInstance::getVar( 'task', '' ) ){
    		case 'xml':
        		xmlFile();
    			break;
		    case "downloadfile":
				$id = mosGetParam ( $_REQUEST, 'id'  , '');
				downloadFile($id);
				break;
			default:
				require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'meta' . DS . 'display.class.php' );
				template();
		}
}

/**
 * this is the primary and default function
 * it loads a template to run
 * that template's rsgDisplay has a switch for $page to handle various features
 */
function template(){
	global $rsgConfig;

	//Set template selection
	$template = preg_replace( '#\W#', '', mosGetParam ( $_REQUEST, 'rsgTemplate', $rsgConfig->get('template') ));
	
	require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template . DS . 'index.php');
}

function xmlFile(){
    $gid = mosGetParam ( $_REQUEST, 'gid', null );
    
    // if this succeeds we know we have a proper gallery.
    $gallery = rsgGalleryManager::get( $gid );
    
    $xmlTemplateFile = preg_replace( '#\W#', '', mosGetParam ( $_REQUEST, 'xmlTemplate', 'generic' ) );
    
    // require generic template which all other templates should extend
    require_once( JPATH_RSGALLERY2_SITE . '/xml_templates/generic.php' );
    // require the template specified to be used
    require_once( JPATH_RSGALLERY2_SITE . '/xml_templates/' . $xmlTemplateFile );
    
    // prepare and output xml
    $xmlTemplate = "rsgXmlGalleryTemplate_$xmlTemplateName";
    $xmlTemplate = new $xmlTemplate( $gallery );
    $xmlTemplate->prepare();
    $xmlTemplate->printHead();
    $xmlTemplate->printGallery();

    die();// quit now so that only the xml is sent and not the joomla template
}

/**
 * Forces a download box to download single images
 * Thanks to Rich Malak <rmalak@fuseideas.com>for his invaluable contribution
 * to this very important feature!
 * @param int Id of the file to download
 */
function downloadFile($id) {
	global $rsgConfig;
	//Clean and delete current output buffer 
	ob_end_clean();
	
	//Get filename from image ID
	$filename = galleryUtils::getFileNameFromId($id);
	
	//Set correct path for image to download
	if ( $rsgConfig->get('keepOriginalImage') == false ) {
		$file = JPATH_ROOT.$rsgConfig->get('imgPath_display'). DS .$filename;
	} else {
		$file = JPATH_ROOT.$rsgConfig->get('imgPath_original'). DS .$filename;
	}
	//Open up the file
	if ( $fd = fopen($file, "r") ) {
	    $fsize 		= filesize($file);
	    $path_parts = pathinfo($file);
	    $ext 		= strtolower($path_parts["extension"]);
	    
	    //Check the extension and provide the right headers for the file
	    switch ($ext) {
	        case "pdf":
	        	header("Content-type: application/pdf"); // add here more headers for diff. extensions
	        	header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachement' to force a download
	        	break;
	        case "jpg":
	        	header("Content-type: image/jpeg");
	        	header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
	        	break;
	        case "gif":
	        	header("Content-type: image/gif");
	        	header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
	        	break;
	        case "png":
	        	header("Content-type: image/png");
	        	header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
	        	break;
	    	default:
	        	header("Content-type: application/octet-stream");
	        	header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
	    }
	    header("Content-length: $fsize");
	    header("Cache-control: private");
	    //Read the contents of the file
	    while(!feof($fd)) {
	        $buffer = fread($fd, 4096);
	        echo $buffer;
	    }
	}
	//Close file after use!
	fclose ($fd);
}