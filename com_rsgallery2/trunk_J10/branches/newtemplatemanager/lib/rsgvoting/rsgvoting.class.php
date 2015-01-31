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

class rsgVoting {

    function rsgVoting() {
    }
    
    function showVoting( $option = "com_rsgallery2") {
    	global $rsgConfig;
    	if ($rsgConfig->get('voting')) {
	    	$id 		= rsgInstance::getInt( 'id'  , '');
	    	
			?>
			<script  type="text/javascript">
			function saveVote(id, value) {
				var form = document.rsgvoteform;
				var saveVote = confirm('<?php echo _RSGALLERY_VOTING_ARE_YOU_SURE;?>');
				
			if (saveVote) {
				form.rating.value = value;
				form.submit();
				}
			}
			</script>
	
	    	<form name="rsgvoteform" method="post" action="<?php echo JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id);?>">
	    	<table border="0" width="200">
	    	<tr>
	    		<td><?php echo _RSGALLERY_VOTING_VOTE;?>:</td>
		    	<td>
		    	<ul class="star-rating">
		   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 1);" title='<?php echo _RSGALLERY_VOTING_RATE_1;?>' class="one-star">1</a></li>
		   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 2);" title='<?php echo _RSGALLERY_VOTING_RATE_2;?>' class="two-stars">2</a></li>
		   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 3);" title='<?php echo _RSGALLERY_VOTING_RATE_3;?>' class="three-stars">3</a></li>
		   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 4);" title='<?php echo _RSGALLERY_VOTING_RATE_4;?>' class="four-stars">4</a></li>
		   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 5);" title='<?php echo _RSGALLERY_VOTING_RATE_5;?>' class="five-stars">5</a></li>
		   		</ul>
		   		</td>
			</tr>
	   		</table>
	   		<input type="hidden" name="rsgOption" value="rsgVoting" />
	   		<input type="hidden" name="task" value="save" />
	   		<input type="hidden" name="rating" value="" />
	   		<input type="hidden" name="id" value="<?php echo $id;?>" />
	   		</form>
	    	<?php
	    	//Show voting
	    }
    }

	
	function getTotal( $id ) {
		global $database;
		$sql = "SELECT rating FROM #__rsgallery2_files WHERE id = '$id'";
		$database->setQuery($sql);
		$total = $database->loadResult();
		
		return $total;
	}
	
	function getVoteCount( $id ) {
		global $database;
		$sql = "SELECT votes FROM #__rsgallery2_files WHERE id = '$id'";
		$database->setQuery($sql);
		$votes = $database->loadResult();
		
		return $votes;
	}
	
	function calculateAverage( $id ) {
		if (rsgVoting::getVoteCount($id) > 0) {
			$avg = rsgVoting::getTotal($id) / rsgVoting::getVoteCount($id);
   			$value = round(($avg*2), 0)/2;
   			return $value;
		} else {
			return 0;
		}
	}
	
	function showScore() {
	    	$id 		= rsgInstance::getInt( 'id'  , '');
		?>		
	    	<table border="0" width="200">
	    	<tr>
	    		<td><?php echo _RSGALLERY_VOTING_RATING;?>:</td>
	    		<td><?php echo rsgVoting::calculateAverage($id);?>&nbsp;/&nbsp;<?php echo rsgVoting::getVoteCount($id);?><?php echo _RSGALLERY_VOTING_VOTES;?></td>
	       	</tr>
			</table>
		<?php
	}
	/**
	 * Check if the user already voted for this item
	 * @param int ID of item to vote on
	 * @return True or False
	 */
	function alreadyVoted( $id ) {
		global $rsgConfig;
		//Check if cookie rsgvoting was set for this image!
		$cookie_name = $rsgConfig->get('cookie_prefix').$id;
		if (isset($_COOKIE[$cookie_name])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Checks if it is allowed to vote in this gallery
	 * @return True or False
	 */
	function voteAllowed() {
		global $rsgConfig, $rsgAccess;
		
		//Check if voting is enabled
		if ($rsgConfig->get('voting') < 1)
			return false;
		else {
			$gallery = rsgGalleryManager::get();
			return $rsgAccess->checkGallery("vote_vote", $gallery->id);
		}
			
	}
}
?>
