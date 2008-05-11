<script  type="text/javascript">
function saveVote(id, value) {
	var form = document.rsgvoteform;
	var saveVote = confirm(' <?php echo _RSGALLERY_VOTING_ARE_YOU_SURE;?> ');
	
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
