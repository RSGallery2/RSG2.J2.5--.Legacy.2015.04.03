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
switch( $task ){
    case 'save':
        saveVote( $option );
        break;
}

function saveVote( $option ) {
	global $database;
	$rating 	= rsgInstance::getInt('rating', '');
	$id 		= rsgInstance::getInt('id', '');
	$vote 		= new rsgVoting();
	$Itemid		= rsgInstance::getInt('Itemid', '');
	
	//Add voting details
	$total 		= $vote->getTotal( $id ) + $rating;
	$votecount 	= $vote->getVoteCount( $id ) + 1;
	
	echo "And the vote has been saved sucesfully with rating: $rating!";
	$sql = "UPDATE #__rsgallery2_files SET rating = '$total', votes = '$votecount' WHERE id = '$id'";
	$database->setQuery( $sql );
	if ( !$database->query() ) {
		$msg = "Vote could not be added to the database";
	} else {
		$msg = "Vote added to database!";
	}
	mosRedirect("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=$id", $msg);
}
?>