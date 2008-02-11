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

require_once( JPATH_RSGALLERY2_SITE . DS . 'lib' . DS . 'rsgvoting' . DS . 'rsgvoting.class.php' );

$cid    = rsgInstance::getInt('cid', array(0) );
$task    = rsgInstance::getVar('task', '' );
$id    = rsgInstance::getInt('id','' );
switch( $task ){
    case 'save':
        saveVote( $option );
        break;
}

function test( $id ) {
		echo "<pre>";
		print_r($_COOKIE);
		echo "</pre>";
		$cookie_prefix = strval("rsgvoting_".$id);
		echo $cookie_prefix;
	if (!isset($_COOKIE[$cookie_prefix])) {
		//Cookie valid for 1 year!
		setcookie($cookie_prefix ,$id ,time()+60*60*24*365, "/");
	}

}
function saveVote( $option ) {
	global $my, $rsgConfig, $database;
	
	if ( $rsgConfig->get('voting') < 1 ) {
		mosRedirect("index.php?option=com_rsgallery2", _RSGALLERY_VOTING_DISABLED);
	} else {
		$rating 	= rsgInstance::getInt('rating', '');
		$id 		= rsgInstance::getInt('id', '');
		$vote 		= new rsgVoting();
		$Itemid		= rsgInstance::getInt('Itemid', '');
		//Check if user can vote
		if (!$vote->voteAllowed() ) {
			mosRedirect("index.php?option=com_rsgallery2&Itemid=$Itemid&page=inline&id=$id", _RSGALLERY_VOTING_NOT_AUTH);
		}
		
		//Check if user has already voted for this image
		if ($vote->alreadyVoted($id)) {
		 	mosRedirect("index.php?option=com_rsgallery2&Itemid=$Itemid&page=inline&id=$id",_RSGALLERY_VOTING_ALREADY_VOTED);
		}
		
		//All checks OK, store vote in DB
		$total 		= $vote->getTotal( $id ) + $rating;
		$votecount 	= $vote->getVoteCount( $id ) + 1;
		
		$sql = "UPDATE #__rsgallery2_files SET rating = '$total', votes = '$votecount' WHERE id = '$id'";
		$database->setQuery( $sql );
		if ( !$database->query() ) {
			$msg = _RSGALLERY_VOTING_NOT_ADDED;
		} else {
			$msg = _RSGALLERY_VOTING_ADDED;
			//Store cookie on system
			setcookie($rsgConfig->get('cookie_prefix').$id, $my->id, time()+60*60*24*365, "/");
		}
		mosRedirect("index.php?option=com_rsgallery2&Itemid=$Itemid&page=inline&id=$id", $msg);
	}
}
?>