<?php
/**
* This file contains Comments logic
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 20011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

require_once( JPATH_RSGALLERY2_SITE . DS . 'lib' . DS . 'rsgcomments' . DS . 'rsgcomments.class.php' );

$cid    = JRequest::getInt('cid', array(0) );
$task    = JRequest::getVar('task', '' );
$option    = JRequest::getVar('option', '' );

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
	$id	= JRequest::getInt('id'  , '');
	$item_id 	= JRequest::getInt('item_id'  , '');
	$catid 		= JRequest::getInt('catid'  , '');
	$redirect_url = JRoute::_("index.php?option=".$option."&page=inline&id=".$item_id."&catid=".$catid);
	echo "Here we will delete comment number ".$id."\\n and redirect to ".$redirect_url;
}

/**
 * Saves a comment to the database
 * @param option from URL
 * @todo Implement system to allow only one comment per user.
 */
function saveComment( $option ) {
	global $rsgConfig;
	$mainframe =& JFactory::getApplication();
	$my = JFactory::getUser();
	$database = JFactory::getDBO();
	
	//Retrieve parameters
	$user_ip	= $_SERVER['REMOTE_ADDR'];
	$rsgOption	= JRequest::getVar('rsgOption'  , '');
	$subject 	= JRequest::getVar('ttitle'  , '');
	$user_name	= JRequest::getVar( 'tname', '');
	$comment 	= get_magic_quotes_gpc() ? JRequest::getVar( 'tcomment'  , '') : addslashes(JRequest::getVar( 'tcomment'  , ''));
	$item_id 	= JRequest::getInt( 'item_id'  , '');
	$catid 		= JRequest::getInt( 'catid'  , '');
	$Itemid 	= JRequest::getInt( 'Itemid'  , '');

	$redirect_url = JRoute::_("index.php?option=".$option."&Itemid=$Itemid&page=inline&id=".$item_id, false);
	
	//Check if commenting is enabled
	if ($rsgConfig->get('comment') == 0) {
		$mainframe->redirect($redirect_url, JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED') );
		exit();
	}
	
	//Check if user is logged in
	if ($my->id) {
		$user_id = $my->id;
		//Check if only one comment is allowed
		if ($rsgConfig->get('comment_once') == 1) {
			//Check how many comments the user already made on this item
			$sql = "SELECT COUNT(1) FROM #__rsgallery2_comments WHERE user_id = '$user_id' AND item_id='$item_id'";
			$database->setQuery( $sql );
			$result = $database->loadResult();
			if ($result > 0 ) {
				//No further comments allowed, redirect
				$mainframe->redirect($redirect_url, JText::_('COM_RSGALLERY2_USER_CAN_ONLY_COMMENT_ONCE'));
			}
		}
	} else {
		if( ! $rsgConfig->get( 'comment_allowed_public' )){
			$mainframe->redirect($redirect_url, JText::_('COM_RSGALLERY2_YOU_MUST_LOGIN_TO_COMMENT'));
		}
		$user_id = 0;
		//Check for unique IP-address and see if only one comment from this IP=address is allowed
	}
	
	if ($rsgConfig->get('comment_security') == 1) {
		
		$checkSecurity = null;
		$userEntry = JRequest::getVar('securityImageRSGallery2', false, '', 'CMD'); 
		$mainframe->triggerEvent('onSecurityImagesCheck', array($userEntry, &$checkSecurity));
		
		//Check if security check was OK
		if ($checkSecurity == false ) 
			$mainframe->redirect( $redirect_url, JText::_('COM_RSGALLERY2_INCORRECT_CAPTCHA_CHECK_COMMENT_IS_NOT_SAVED'));
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
		$mainframe->redirect( $redirect_url, JText::_('COM_RSGALLERY2_COMMENT_ADDED_SUCCESFULLY') );
	} else {
		$mainframe->redirect( $redirect_url, JText::_('COM_RSGALLERY2_COMMENT_COULD_NOT_BE_ADDED') );
		//echo $sql;
	}
	
}

/**
* Deletes a comment
* @param array An array of unique comment id numbers
* @param string The current url option
*/
function deleteComments( $option ) {
	$mainframe =& JFactory::getApplication();
	$database =& JFactory::getDBO();
	
	// Get the current JUser object
	$user = &JFactory::getUser();

	//Check permission to delete (only for users with core.admin on RSG2)
	if (!JFactory::getUser()->authorise('core.admin','com_rsgallery2'))
		die('Only admins can delete comments.');

	//Get parameters
	$id			= JRequest::getInt( 'id', '' );
	$item_id 	= JRequest::getInt( 'item_id'  , '');
	$catid 		= JRequest::getInt( 'catid'  , '');
	$Itemid 	= JRequest::getInt( 'Itemid'  , '');

	if ( !empty($id) ) {
		$query = "DELETE FROM #__rsgallery2_comments WHERE id = '$id'";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	$mainframe->redirect(JRoute::_("index.php?option=".$option."&Itemid=$Itemid&page=inline&id=".$item_id."&catid=".$catid, false), JText::_('COM_RSGALLERY2_COMMENT_DELETED_SUCCESFULLY') );
}
