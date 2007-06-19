<?php
/**
* This file contains xxxxxxxxxxxxxxxxxxxxxxxxxxx.
* @version xxx
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( JPATH_RSGALLERY2_SITE . DS . 'lib' . DS . 'rsgcomments' . DS . 'rsgcomments.class.php' );

$cid    = mosGetParam( $_REQUEST, 'cid', array(0) );
$task    = mosGetParam( $_REQUEST, 'task', '' );
switch( $task ){
    case 'save':
    	//test( $option );
        saveComment( $option );
        break;
    case 'delete':
    	deleteComments( $option );
    	//test( $option );
    	break;
}

/**
 * Test function FOR DEVELOPMENT ONLY!
 * @param string The current url option
 */
function test( $option ) {
	global $Itemid, $mainframe, $database, $mosConfig_live_site, $mosConfig_absolute_path;
	$id	= mosGetParam ( $_REQUEST, 'id'  , '');
	$item_id 	= mosGetParam ( $_REQUEST, 'item_id'  , '');
	$catid 		= mosGetParam ( $_REQUEST, 'catid'  , '');
	$redirect_url = "index.php?option=$option&amp;Itemid=$Itemid&amp;page=inline&amp;id=$item_id&amp;catid=$catid";
	echo "Here we will delete comment number ".$id."\\n and redirect to ".$redirect_url;
}

/**
 * Saves a comment to the database
 * @param option from URL
 * @todo Implement system to allow only one comment per user.
 */
function saveComment( $option ) {
	global $Itemid, $database, $my, $rsgConfig, $Itemid, $mosConfig_absolute_path;
	
	
	//Retrieve parameters
	$user_ip	= $_SERVER['REMOTE_ADDR'];
	$rsgOption	= mosGetParam ( $_REQUEST, 'rsgOption'  , '');
	$subject 	= mosGetParam ( $_REQUEST, 'ttitle'  , '');
	$user_name	= mosGetParam ( $_REQUEST, 'tname', '');
	$comment 	= mosGetParam ( $_REQUEST, 'tcomment'  , '');
	$item_id 	= mosGetParam ( $_REQUEST, 'item_id'  , '');
	$catid 		= mosGetParam ( $_REQUEST, 'catid'  , '');
	//Check if commenting is enabled
	$redirect_url = "index.php?option=$option&amp;Itemid=$Itemid&amp;page=inline&amp;id=$item_id&amp;catid=$catid";
	if ($rsgConfig->get('comment') == 0) {
		mosRedirect( $redirect_url, "** Commenting disabled for this gallery **" );
		exit();
	}
	
	//Check if user is logged in
	if ($my->id) {
		$user_id = $my->id;
		//Check if only one comment is allowed
		if ($rsgConfig->get('comment_once') == 1) {
			//Check how many comments the user already made on this item
			$sql = "SELECT COUNT(1) FROM #__rsgallery2_comments WHERE user_id = '$user_id'";
			$database->setQuery( $sql );
			$result = loadResult();
			if ($result > 0 ) {
				//No further comments allowed, redirect
				mosRedirect($redirect_url, "** You already commented on this item! **");
			} else {
				continue;
			}
		}
	} else {
		$user_id = 0;
		//Check for unique IP-address and see if only one comment from this IP=address is allowed
	}
	
	if ($rsgConfig->get('comment_security') == 1) {
		if ( file_exists(JPATH_ROOT.'/administrator/components/com_securityimages/server.php') ) {
			include (JPATH_ROOT.'/administrator/components/com_securityimages/server.php');
			//Get parameters
			$security_refid		= mosGetParam( $_POST, 'security_rsgallery2_refid', '' );
			$security_try    	= mosGetParam( $_POST, 'security_rsgallery2_try', '' );
			$security_reload	= mosGetParam( $_POST, 'security_rsgallery2_reload', '' ); 
			$checkSecurity 		= checkSecurityImage($security_refid, $security_try);
		}
		//Check if security check was OK
		if ($checkSecurity == false ) {
			mosRedirect( $redirect_url, "** Incorrect CAPTCHA check. Comment not saved! **" );
			exit();
		}	
	}
	
	//If we are here, start database thing
	$sql = "INSERT INTO #__rsgallery2_comments (id, user_id, user_name, user_ip, parent_id, item_id, item_table, datetime, subject, comment, published, checked_out, checked_out_time, ordering, params, hits)" .
			" VALUES (" .
			"''," . 				//Autoincrement id
			"'$user_id'," .			//User id
			"'$user_name'," .		//User name
			"'$user_ip'," .			//User IP address
			"''," .					//Parent id, defaults to zero.
			"'$item_id'," .			//Item id
			"'com_rsgallery2'," .	//Item table, if rsgallery2 commenting, field is empty
			"now()," .				//Datetime 
			"'$subject'," .			//Subject
			"'$comment'," .			//Comment text
			"1," .					//Published, defaults to 1
			"''," .					//Checked out
			"''," .					//Checked_out_time
			"''," .					//Ordering
			"''," .					//Params
			"''" .					//Hits
			")";
	$database->setQuery( $sql );
	if ( $database->query() ) {
		mosRedirect( $redirect_url, "** Comment added succesfully **" );
	} else {
		mosRedirect( $redirect_url, "** Comment could not be added! **" );
		//echo $sql;
	}
	
}

/**
* Deletes a comment
* @param array An array of unique comment id numbers
* @param string The current url option
*/
function deleteComments( $option ) {
	global $database;
	
	//Get parameters
	$id			= mosGetParam( $_REQUEST, 'id', '' );
	$item_id 	= mosGetParam ( $_REQUEST, 'item_id'  , '');
	$catid 		= mosGetParam ( $_REQUEST, 'catid'  , '');
	
	if ( !empty($id) ) {
		$query = "DELETE FROM #__rsgallery2_comments WHERE id = '$id'";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect( "index.php?option=$option&amp;page=inline&amp;id=$item_id&amp;catid=$catid", "** Comment deleted succesfully **" );
}
?>