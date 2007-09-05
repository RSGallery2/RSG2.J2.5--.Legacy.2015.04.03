<?php
/**
* This file contains the display file for RSGallery2 highslideJS template.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
* Template created by Daniël Tulp, DT^2 (http://design.danieltulp.nl)
* On basis by piran rsgallery2@dreckly.org; And Jonah, leaddeveloper RSGallery2 project
* Version 0.2.2
*/
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

//ToDo: add switch/ contional for extending tables or semantic
class rsgDisplay_superCleanHS extends rsgDisplay_semantic{
	function addVote() {
		global $database, $Itemid;
		if (isset($_REQUEST['picid']))      $picid = mosGetParam ( $_REQUEST, 'picid'  , '');
		if (isset($_REQUEST['vote']))       $vote = mosGetParam ( $_REQUEST, 'vote'  , '');
		if ($vote)
			{
			//Retrieve values
			$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$picid'");
			$rows = $database->loadObjectList();
			foreach ($rows as $row)
				{
				$votes = $row->votes + 1;
				$rating = $row->rating + $vote;
				//This isn't doing anything right? 
				//$ordering = $row->ordering - 1;
				//Store new values
				$database->setQuery("UPDATE #__rsgallery2_files SET votes = '$votes', rating = '$rating' WHERE id = '$row->id'");
				if ($database->query())
					{
					?>
					<script type="text/javascript">
						alert("<?php echo _RSGALLERY_THANK_VOTING; ?>");
						location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&gid=".$row->gallery_id); ?>';
					</script>
					<?php
					}
				else
					{
					?>
					<script type="text/javascript">
						alert("<?php echo _RSGALLERY_VOTING_FAILED; ?>");
						location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&gid=".$row->gallery_id); ?>';
					</script>
					<?php
					}
				}
			}
		else
			{echo"No vote value entered";
			//very strange that the alert message does not work when $vote==0
			?>
			<script type="text/javascript">
				alert("<?php echo _RSGALLERY_VOTING_FAILED; ?>");
				location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&gid=".$row->gallery_id); ?>';
			</script>
			<?php
			}
		}
	
	function showThumbs(){
		global $database, $rsgConfig, $rsgImagesItem, $Itemid, $mosConfig_live_site;
		if(isset($_REQUEST['catid'])||isset($_REQUEST['gid'])){
		echo"<p>".SCHS_NAV_TXT."</p>";
		}
		foreach( $this->gallery->itemRows() as $img ){
			$thumb = imgUtils::getImgThumb( $img['name'] );
			$original = imgUtils::getImgOriginal( $img['name'] );
			$name = $img['name'];
            $title = $img['title'];
			$descr = $img['descr'];
			$picid = $img['id'];
			$galid = $img['gallery_id'];
			$url = sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=vote");
			$votes = $img['votes'];
			if ($votes > 0){
			    $rating = galleryUtils::showRating( $picid );
			}else{
			    $rating = _RSGALLERY_NO_RATINGS;
			}
			$ratingstar = "<img src='".$mosConfig_live_site."/images/M_images/rating_star.png' alt='Rating star'/>";
			echo "
<a id=\"$picid\" href=\"$original\" alt=\"$name\" title=\"$title\" class=\"highslide\" onclick=\"return hs.expand(this, {captionId: 'caption-$picid'})\" hs.targetY=\"my-element -20px\">		
	<img src=\"$thumb\" alt=\"$title\" title=\"$title\"/>
</a>
<div class='highslide-caption' id='caption-$picid'>
	<div class='highslide_prev'>
		<a class='button' href='#' 
		   onclick='return hs.previous(this)' 
		   title='".SCHS_PREV."'>".SCHS_PREV_ARROW."</a>
	</div>
	<div class='highslide_next'>
		<a class='button' href='#' 
		   onclick='return hs.next(this)' 
		   title='".SCHS_NEXT."'>".SCHS_NEXT_ARROW."</a>  
	</div>
	<div class='description'>
		<div class='imgname'>$title</div>
		$descr
	</div>
	<div class='votes'>
		<b>".SCHS_VOTE."</b><br />
		<form method='post' action='$url'>
			<div>
				"._RSGALLERY_VOTES_NR.": $votes <br />
				"._RSGALLERY_VOTES_AVG.": $rating
			</div>
			<div class='hisghslide_voting'>
				<input type='hidden' name='picid' value='$picid' />
				<select name='vote'>
					<option value='0'>-Vote-</option>
					<option value='1'>Very bad</option>
					<option value='2'>Bad</option>
					<option value='3'>OK</option>
					<option value='4'>Good</option>
					<option value='5'>Very good</option>
				</select>
				<input class='button' type='submit' name='submit' value='"._RSGALLERY_VOTE."' />
			</div>
		</form>
	</div>
	<div style=\"clear:both\"></div>
</div>
";
		}
	}
}
