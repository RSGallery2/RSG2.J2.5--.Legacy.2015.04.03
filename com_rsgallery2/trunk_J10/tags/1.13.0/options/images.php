<?php
/**
* Images option for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $rsgOptions_path . 'images.html.php' );
require_once( $rsgOptions_path . 'images.class.php' );

$cid = josGetArrayInts( 'cid' );

switch ($task) {
	case 'new':
		editImage( $option, 0 );
		break;
		
	case 'upload':
		uploadImage( $option );
		break;
		
	case 'save_upload':	
		saveUploadedImage( $option );
		break;
		
	case 'edit':
		editImage( $option, $cid[0] );
		break;

	case 'editA':
		editImage( $option, $id );
		break;

	case 'save':
		saveImage( $option );
		break;

	case 'remove':
		removeImages( $cid, $option );
		break;

	case 'publish':
		publishImages( $cid, 1, $option );
		break;

	case 'unpublish':
		publishImages( $cid, 0, $option );
		break;

	case 'approve':
		break;

	case 'cancel':
		cancelImage( $option );
		break;

	case 'orderup':
		orderImages( intval( $cid[0] ), -1, $option );
		break;

	case 'orderdown':
		orderImages( intval( $cid[0] ), 1, $option );
		break;
	
	case 'saveorder':
		saveOrder( $cid );
		break;
	
	case 'reset_hits':
		resetHits( $cid );
		break;
		
	case 'showImages':
	default:
		showImages( $option );
		break;
}

/**
* Compiles a list of records
* @param database A database connector object
*/
function showImages( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$gallery_id 		= intval( $mainframe->getUserStateFromRequest( "gallery_id{$option}", 'gallery_id', 0 ) );
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 	= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search 	= $database->getEscaped( trim( strtolower( $search ) ) );

	$where = array();

	if ($gallery_id > 0) {
		$where[] = "a.gallery_id = $gallery_id";
	}
	if ($search) {
		$where[] = "LOWER(a.title) LIKE '%$search%'";
	}

	// get the total number of records
	$query = "SELECT COUNT(1)"
	. "\n FROM #__rsgallery2_files AS a"
	. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT a.*, cc.name AS category, u.name AS editor"
	. "\n FROM #__rsgallery2_files AS a"
	. "\n LEFT JOIN #__rsgallery2_galleries AS cc ON cc.id = a.gallery_id"
	. "\n LEFT JOIN #__users AS u ON u.id = a.checked_out"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
	. "\n ORDER BY a.gallery_id, a.ordering"
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	// build list of categories
	$javascript 	= 'onchange="document.adminForm.submit();"';
	$lists['gallery_id']			= galleryUtils::galleriesSelectList( $gallery_id, 'gallery_id', false, $javascript );
	html_rsg2_images::showImages( $option, $rows, $lists, $search, $pageNav );
}

/**
* Compiles information to add or edit
* @param integer The unique id of the record to edit (0 if new)
*/
function editImage( $option, $id ) {
	global $database, $my, $mosConfig_absolute_path;

	$lists = array();

	$row = new rsgImagesItem( $database );
	// load the row from the db table
	$row->load( (int)$id );

	// fail if checked out not by 'me'
	if ($row->isCheckedOut( $my->id )) {
		mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption", "he module $row->title is currently being edited by another administrator." );
	}

	if ($id) {
		$row->checkout( $my->id );
	} else {
		// initialise new record
		$row->published = 1;
		$row->approved 	= 1;
		$row->order 	= 0;
		$row->gallery_id 	= intval( mosGetParam( $_POST, 'gallery_id', 0 ) );
	}

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text"
	. "\n FROM #__rsgallery2_files"
	. "\n WHERE gallery_id = " . (int) $row->gallery_id
	. "\n ORDER BY ordering"
	;
	$lists['ordering'] 			= mosAdminMenus::SpecificOrdering( $row, $id, $query, 1 );

	// build list of categories
	$lists['gallery_id']			= galleryUtils::galleriesSelectList( $row->gallery_id, 'gallery_id', true );
	// build the html select list
	$lists['published'] 		= mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );

	$file 	= $mosConfig_absolute_path .'/administrator/components/com_rsgallery2/options/images.item.xml';
	$params = new mosParameters( $row->params, $file, 'component' );

	html_rsg2_images::editImage( $row, $lists, $params, $option );
}

/**
* Saves the record on an edit form submit
* @param database A database connector object
*/
function saveImage( $option, $redirect = true ) {
	global $database, $my, $rsgOption;

	$row = new rsgImagesItem( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save params
	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
		$txt = array();
		foreach ( $params as $k=>$v) {
			$txt[] = "$k=$v";
		}
		$row->params = implode( "\n", $txt );
	}

	$row->date = date( 'Y-m-d H:i:s' );
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "gallery_id = " . (int) $row->gallery_id );
	
	if ($redirect)
		mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption" );
}

/**
* Deletes one or more records
* @param array An array of unique category id numbers
* @param string The current url option
*/
function removeImages( $cid, $option ) {
	global $database, $rsgOption, $rsgConfig;
	
	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}
	//Delete images from filesystem
	if (count( $cid )) {
		//Delete images from filesystem
		foreach ($cid as $id) {
			$name 		= galleryUtils::getFileNameFromId($id);
			$thumb 		= JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/' . imgUtils::getImgNameThumb( $name );
        	$display 	= JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/' . imgUtils::getImgNameDisplay( $name );
        	$original 	= JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/' . $name;
        
        	if( file_exists( $thumb ))
            	if( !unlink( $thumb )) return new PEAR_Error( "error deleting thumb image: " . $thumb );
        	if( file_exists( $display ))
            	if( !unlink( $display )) return new PEAR_Error( "error deleting display image: " . $display );
        	if( file_exists( $original ))
            	if( !unlink( $original )) return new PEAR_Error( "error deleting original image: " . $original );
		}
		
		//Delete from database
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__rsgallery2_files"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption", _RSGALLERY_ALERT_IMGDELETEOK );
}

/**
* Publishes or Unpublishes one or more records
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current url option
*/
function publishImages( $cid=null, $publish=1,  $option ) {
	global $database, $my, $rsgOption;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__rsgallery2_files"
	. "\n SET published = " . intval( $publish )
	. "\n WHERE id IN ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new rsgImagesItem( $database );
		$row->checkin( $cid[0] );
	}
	mosRedirect( "index2.php?option=com_rsgallery2&rsgOption=$rsgOption" );
}
/**
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderImages( $uid, $inc, $option ) {
	global $database, $rsgOption;
	$row = new rsgImagesItem( $database );
	$row->load( (int)$uid );
	$row->updateOrder();
	$row->move( $inc, "published >= 0" );
	$row->updateOrder();

	mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption" );
}

/**
* Cancels an edit operation
* @param string The current url option
*/
function cancelImage( $option ) {
	global $database, $rsgOption;
	$row = new rsgImagesItem( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption" );
}

/**
 * Uploads single images
 */
function uploadImage( $option ) {
	global $database;
	//Check if there are galleries created
	$database->setQuery( "SELECT id FROM #__rsgallery2_galleries" );
    $database->query();
    if( $database->getNumRows()==0 ){
        HTML_RSGALLERY::requestCatCreation( );
        return;
    }
    
	//Create gallery selectlist
	$lists['gallery_id']			= galleryUtils::galleriesSelectList( NULL, 'gallery_id', false );
	html_rsg2_images::uploadImage( $lists, $option );
}

function saveUploadedImage( $option ) {
	global $id, $rsgOption;
	if (isset($_REQUEST['title'])) 		$title = mosGetParam ( $_REQUEST, 'title'  , '');  
	if (isset($_REQUEST['descr'])) 		$descr = mosGetParam ( $_REQUEST, 'descr'  , ''); 
	if (isset($_REQUEST['gallery_id'])) $gallery_id = mosGetParam ( $_REQUEST, 'gallery_id'  , '');
	if (isset($_FILES['images'])) 		$files = mosGetParam ($_FILES, 'images','');

	//For each error that is found, store error message in array
	$errors = array();
	foreach ($files["error"] as $key => $error) {
		if( $error != UPLOAD_ERR_OK ) {
			if ($error == 4) {//If no file selected, ignore
				continue;
			} else {
				//Create meaningfull error messages and add to error array
				$error = fileHandler::returnUploadError( $error );
				$errors[] = new imageUploadError($files["name"][$key], $error);
				continue;
			}
		}

		//Special error check to make sure the file was not introduced another way.
		if( !is_uploaded_file( $files["tmp_name"][$key] )) {
			$errors[] = new imageUploadError( $files["tmp_name"][$key], "not an uploaded file, potential malice detected!" );
			continue;
		}
		//Actually importing the image
		$e = fileUtils::importImage($files["tmp_name"][$key], $files["name"][$key], $gallery_id, $title[$key], $descr);
		if ( $e !== true )
			$errors[] = $e;

	}
	//Error handling if necessary
	if ( count( $errors ) == 0){
		mosRedirect( "index2.php?option=$option&rsgOption=$rsgOption", _RSGALLERY_ALERT_UPLOADOK );
	} else {
		//Show error message for each error encountered
		foreach( $errors as $e ) {
			echo $e->toString();
		}
		//If there were more files than errors, assure the user the rest went well
		if ( count( $errors ) < count( $files["error"] ) ) {
			echo "<br>"._RSGALLEY_ALERT_REST_UPLOADOK;
		}
	}		
}

/**
 * Resets hits to zero
 * @param array image id's
 * @todo Warn user with alert before actually deleting
 */
function resetHits ( &$cid ) {
	global $database;

	$total		= count( $cid );
	/*
	echo "Reset hits for $total images";
	echo "<pre>";
	print_r( $cid );
	echo "</pre>";
	*/
	//Reset hits
	$cids = implode( ',', $cid );

	$database->setQuery("UPDATE #__rsgallery2_files SET ".
			"hits = 0 ".
			"WHERE id IN ( $cids )");

	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	mosRedirect( "index2.php?option=com_rsgallery2&rsgOption=images", '*Hits reset to zero succesfull*' );
}

function saveOrder( &$cid ) {
	global $database;

	$total		= count( $cid );
	$order 		= josGetArrayInts( 'order' );

	$row 		= new rsgImagesItem( $database );
	
	$conditions = array();

	// update ordering values
	for ( $i=0; $i < $total; $i++ ) {
		$row->load( (int) $cid[$i] );
		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "gallery_id=" . (int) $row->gallery_id;
			$found = false;
			foreach ( $conditions as $cond )
				if ($cond[1]==$condition) {
					$found = true;
					break;
				} // if
			if (!$found) $conditions[] = array($row->id, $condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach ( $conditions as $cond ) {
		$row->load( $cond[0] );
		$row->updateOrder( $cond[1] );
	} // foreach

	// clean any existing cache files
	mosCache::cleanCache( 'com_rsgallery2' );

	$msg 	= 'New ordering saved';
	mosRedirect( 'index2.php?option=com_rsgallery2&rsgOption=images', $msg );
} // saveOrder
?>