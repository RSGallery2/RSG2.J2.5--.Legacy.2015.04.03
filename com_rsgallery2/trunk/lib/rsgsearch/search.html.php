<?php
/**
* This file contains the HTML for the search library.
* @version xxx
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class html_rsg2_search {
	function showResults($result, $searchtext) {
		global $rsgConfig, $mosConfig_live_site;
		
		//Format number of hits
		$count = count($result);
		$count = "<span style=\"font-weight:bold;\">".$count."</span>";
		?>
		<table width="100%">
		<tr>
			<td><div align="right"><a href="index.php?option=com_rsgallery2"><?php echo _RSGALLERY_MAIN_GALLERY_PAGE;?></a></div></td>
		</tr>
		<tr>
			<td><h3><?php echo _RSGALLERY_SEARCH_RESULTS_TITLE;?></h3></td>
		</tr>
		<tr>
			<td>
				<span style="font-style:italic;">
				<?php echo _RSGALLERY_SEARCH_RESULTS_NUMBER1 . $count . _RSGALLERY_SEARCH_RESULTS_NUMBER2;?>
					<span style="font-weight:bold;";><?php echo $searchtext;?></span>
				<span>
			</td>
		</tr>
		</table>
		<br />
		<br />
		<?php
		if ($result) {
			foreach ($result as $match) {
				?>
				<table width="100%" border="0">
				<tr>
					<td width="<?php echo $rsgConfig->get('thumb_width');?>">
					<div class="img-shadow">
						<a href="index.php?option=com_rsgallery2&page=inline&id=<?php echo $match->item_id;?>">
						<img border="0" src="<?php echo $mosConfig_live_site.$rsgConfig->get('imgPath_thumb') . "/" . imgUtils::getImgNameThumb($match->itemname);?>" alt="image" />
						</a>
					</div>
					</td>
					<td valign="top">
						<a href="index.php?option=com_rsgallery2&page=inline&id=<?php echo $match->item_id;?>">
							<span style="font-weight:bold;"><?php echo galleryUtils::highlight_keywords($match->title, $searchtext);?></span>
						</a>
						<p><?php echo galleryUtils::highlight_keywords($match->descr, $searchtext);?></p>
						<p>[Gallery name:<a href="#"><?php echo $match->name;?></a>][User:<a href=""><?php echo galleryUtils::genericGetUsername($match->userid);?></a>]</p>
					</td>
				</tr>
				</table>
				<br />
				<?php
			}
		}
	}
}
?>