	<table border="0" width="200">
	<tr>
		<td><?php echo _RSGALLERY_VOTING_RATING;?>:</td>
		<td><?php echo rsgVoting::calculateAverage($id);?>&nbsp;/&nbsp;<?php echo rsgVoting::getVoteCount($id);?><?php echo _RSGALLERY_VOTING_VOTES;?></td>
   	</tr>
	</table>
