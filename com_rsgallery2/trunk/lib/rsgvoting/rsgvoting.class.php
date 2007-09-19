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
    	$id 		= rsgInstance::getInt( 'id'  , '');
    	?>
		<script language="javascript" type="text/javascript">
		function saveVote(id, value) {
			var form = document.vote;
			var saveVote = confirm('Are you sure you want to cast a vote? (' + value + ')');
			
		if (saveVote) {
			form.rating.value = value;
			form.submit();
			}
		}
		</script>

    	<form name="vote" method="post" action="<?php sefRelToAbs("index.php&amp;option=com_rsgallery2");?>">
    	<table border="0" width="200">
    	<tr>
    		<td>Rating:</td>
    		<td><?php echo rsgVoting::calculateAverage($id);?></td>
       	</tr>
    	<tr>
    		<td>Vote:</td>
	    	<td>
	    	<ul class="star-rating">
	   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 1);" title='Rate this 1 star out of 5' class="one-star">1</a></li>
	   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 2);" title='Rate this 2 stars out of 5' class="two-stars">2</a></li>
	   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 3);" title='Rate this 3 stars out of 5' class="three-stars">3</a></li>
	   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 4);" title='Rate this 4 stars out of 5' class="four-stars">4</a></li>
	   			<li><a href='#' onclick="saveVote(<?php echo $id;?>, 5);" title='Rate this 5 stars out of 5' class="five-stars">5</a></li>
	   		</ul>
	   		</td>
   		</table>
   		<input type="hidden" name="rsgOption" value="rsgVoting" />
   		<input type="hidden" name="task" value="save" />
   		<input type="hidden" name="rating" value="" />
   		<input type="hidden" name="id" value="<?php echo $id;?>" />
   		</form>
    	<?php
    	//Show voting
    	
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
		$avg = rsgVoting::getTotal($id) / rsgVoting::getVoteCount($id);
   		$value = round(($avg*2), 0)/2;
   		return $value;
	}
	
	function showScore() {
		
	}
}
?>