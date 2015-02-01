<?php
/**
* Maintenance options for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2010 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( $rsgOptions_path . 'maintenance.html.php' );
require_once( $rsgOptions_path . 'maintenance.class.php' );

$cid = JRequest::getVar("cid", array(), 'default', 'array' );
$task = rsgInstance::getCmd( 'task', null);

switch ($task) {
	/* Regenerate thumbs calls */
	case 'regenerateThumbs':
		HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('MAINT_REGEN'));
		regenerateImages();
		HTML_RSGALLERY::RSGalleryFooter();
		break;
	case 'executeRegenerateImages':
		executeRegenerateImages();
		break;
		
	/* Consolidate database calls */
	case 'consolidateDB':
		consolidateDB();
		break;
	case 'createImages':
		createImages();
		break;
	case 'deleteImages':
		deleteImages();
		break;
	case 'createDbEntries':
		createDbEntries();
		break;
		
	/* Optimize DB calls*/
	case 'optimizeDB':
		optimizeDB();
		break;
	
	/* Migration calls */
	case 'showMigration':
		HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('Migration options'));
		showMigration();
		HTML_RSGALLERY::RSGalleryFooter();
		break;
	case 'doMigration':
		doMigration();
		break;
		
	case 'test':
		test();
		break;
	default:
		HTML_RSGALLERY::RSGalleryHeader('cpanel', JText::_('MAINT_HEADER'));
		showMaintenanceCP( $option );
		HTML_RSGALLERY::RSGalleryFooter();
		break;
}

function test() {
	// Test function
	echo 'Test function found in admin/options/maintenance.php<br />';
	echo '<a href='.JRoute::_('index.php?option=com_rsgallery2&rsgOption=maintenance&task=test').'>index.php?option=com_rsgallery2&rsgOption=maintenance&task=test</a><p></p>';
	// Test code - start
/*	$database =& JFactory::getDBO();
//	include(JPATH_BASE.DS.'components'.DS.'com_rsgallery2'.DS.'includes'.DS.'install.class.php');
	
/**/
	// Test code - end
}

/**
 * Shows Migration main screen
 * It shows detected gallerysystem and offers a migration option
 */
function showMigration() {
    require_once(JPATH_RSGALLERY2_ADMIN.'/includes/install.class.php');
    
    //Initialize new install instance
    $rsgInstall = new rsgInstall();

    if (isset($_REQUEST['type']))
        $type = mosGetParam ( $_REQUEST, 'type'  , '');
    else
        $type = NULL;


	if( $type=='' ) {
		$rsgInstall->showMigrationOptions();	
	} else {
        $result = $rsgInstall->doMigration( $type );
        if( $result !==true ) {
            echo $result;
            HTML_RSGallery::showCP();
    	} else {
        	echo JText::_('migration successful');
        	HTML_RSGallery::showCP();
    	}
	}
}

function doMigration() {
	$type  	= rsgInstance::getCmd('type', null);
	require_once(JPATH_RSGALLERY2_ADMIN.'/includes/install.class.php');
	
	$migrate_class = "migrate_".$type; 
	$migrate = new $migrate_class;
	$migrate->migrate();
}

/**
 * Shows Control Panel for maintenance of RSGallery2
 */
function showMaintenanceCP() {
	html_rsg2_maintenance::showMaintenanceCP();
}

function regenerateImages() {
	//Select the right gallery, multiple galleries or select them all
	$lists['gallery_dropdown'] = galleryUtils::galleriesSelectList(null, "gid[]", true, "MULTIPLE");
	
	html_rsg2_maintenance::regenerateImages($lists);
}
/**
 * Function will regenerate thumbs for a specific gallery or set of galleries
 * @todo Check if width really changed, else no resize needed. 
 * Perhaps by sampling the oldest thumb from the gallery and checking dimensions against current setting. 
 */
function executeRegenerateImages() {
	global $rsgConfig, $mainframe;
	$error = 0;
	$gid = rsgInstance::getVar( 'gid', array());//array so use getVar and not getInt
	if ( empty($gid) ) {
		$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs", JText::_('NO_GALLERY_SELECTED'));
		return;
	}

	foreach ($gid as $id) {
    	if ($id > 0) {
    		//Check if resize is really needed. It takes a lot of resources when changing thumbs when dimensions did not change!
    		if ( !rsg2_maintenance::thumbSizeChanged($id) ) {
				$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs", JText::_('THUMBNAIL_SIZE_DID_NOT_CHANGE_REGENERATION_NOT_NEEDED'));
				return;
			} else {
				$gallery = rsgGalleryManager::_get($id);
				$images = $gallery->items();
				foreach ($images as $image) {
					$imagename = imgUtils::getImgOriginal($image->name, true);
					if (!imgUtils::makeThumbImage($imagename)) {
						//Error counter
						$error++;
					}
				}
    		}
    	}
    }
    if ($error > 0) {
    	$msg = JText::_('MAINT_REGEN_ERRORS');
    } else {
		$msg = JText::_('MAINT_REGEN_NO_ERRORS');
    }
    $mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=regenerateThumbs", $msg);
}


function consolidateDB() {
	$consolidate = new rsg2_consolidate();
	$consolidate->consolidateDB();
}

function createImages() {
	global $mainframe, $rsgConfig;
	//Check if id or name is set
	if ( isset( $_REQUEST['id'] ) ) {
		$id = rsgInstance::getInt( 'id', null);
		$name = galleryUtils::getFileNameFromId($id);
	}
	elseif ( isset($_REQUEST['name'] ) ) {
		$name    = rsgInstance::getVar( 'name', null);
	} else {
		$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance", JText::_('No fileinformation found. This should never happen!'));
	}
	
	//Just for readability of code
	$original = JPATH_ORIGINAL . DS . $name;
	$display  = JPATH_DISPLAY . DS . imgUtils::getImgNameDisplay($name);
	$thumb    = JPATH_THUMB . DS . imgUtils::getImgNameThumb($name);
	
	//If only thumb exists, no generation possible so redirect.
	if (!file_exists($original) AND !file_exists($display) AND file_exists($thumb) ) {
		$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB", JText::_('RSGALLERY_MAINT_REGEN_ONLY_THUMB'));
		return;
	}
	//Go make images
	if ( file_exists($original) ) {
		//Check if display image exists, if not make it.
		if (!file_exists($display)) {
	    	imgUtils::makeDisplayImage($original, NULL, $rsgConfig->get('image_width') );
	    }
		if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($original);
	    }
	} else {
	    if (file_exists($display)) {
	        copy($display, $original);
	    }
	    if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($display);
	    }
	}
	$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB",$name.' '.JText::_('RSGALLERY_MAINT_REGEN_SUCCESS'));
}

function deleteImages() {
	global $mainframe;
	$database =& JFactory::getDBO();
	$name = rsgInstance::getVar('name', null);
    
    if ( imgUtils::deleteImage( $name ) ) {
    	$txt = JText::_('Image(s) deleted succesfully!');
    } else {
    	$txt = JText::_('Image(s) were not deleted!');
    }
    
    $mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB", $txt);
}

function createDbEntries() {
	$name = rsgInstance::getVar('name'  , null);
	$t_id = rsgInstance::getInt('t_id'  , null);
    $gid = rsgInstance::getInt('gallery_id'  , null);
    echo "<pre>";
    print_r($name);
    echo "</pre>";
    echo "We are going to create an entry for $name in $t_id.";
}

/**
 * Used in the consolidate database function
 * Creates images based on an image id or an image name
 * Note: Does not seem to be used in 2.2.1
 */
function regenerateImage() {
	global $mainframe;
	global $rsgConfig;
	$database =& JFactory::getDBO();
	
	//Check if id or name is set
	if ( isset( $_REQUEST['id'] ) ) {
		$id = rsgInstance::getInt( 'id', null);
		$name = galleryUtils::getFileNameFromId($id);
	}
	elseif ( isset($_REQUEST['name'] ) ) {
		$name    = rsgInstance::getVar( 'name', null);
	} else {
		$mainframe->redirect("index2.php?option=com_rsgallery2&task=batchupload", JText::_('No fileinformation found. This should never happen!'));
	}
	
	//Just for readability of code
	$original = JPATH_ORIGINAL . DS . $name;
	$display  = JPATH_DISPLAY . DS . imgUtils::getImgNameDisplay($name);
	$thumb    = JPATH_THUMB . DS . imgUtils::getImgNameThumb($name);
	    
	if ( file_exists($original) ) {
		//Check if display image exists, if not make it.
		if (!file_exists($display)) {
	    	imgUtils::makeDisplayImage($original, NULL, $rsgConfig->get('image_width') );
	    }
		if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($original);
	    }
	} else {
	    if (file_exists($display)) {
	        copy($display, $original);
	    }
	    if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($display);
	    }
	}
}

/**
 * Checks database for problems and optimizes tables
 */
function optimizeDB() {
	global $mainframe;
	$database =& JFactory::getDBO();
	
	require_once(JPATH_ROOT . DS . "administrator" . DS . "components" . DS . "com_rsgallery2" . DS . "includes" . DS . "install.class.php");
	$install = new rsgInstall();
	$tables = $install->tablelistNew;
	foreach ($tables as $table) {
		$query = "OPTIMIZE TABLE". $database->nameQuote($table);
		$database->setQuery($query);
		$database->query();
	}
	$mainframe->redirect("index2.php?option=com_rsgallery2&rsgOption=maintenance",JText::_('RSGALLERY_MAINT_OPTIMIZE_SUCCESS'));
}
?>