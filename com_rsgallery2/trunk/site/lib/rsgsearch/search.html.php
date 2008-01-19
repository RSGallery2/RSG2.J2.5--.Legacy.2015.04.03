<?php
/**
* This file contains the HTML for the search library.
* @version xxx
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class html_rsg2_search {
	function showResults($result, $searchtext) {
		global $Itemid, $rsgConfig, $mosConfig_live_site;
		
		//Format number of hits
		$count = count($result);
		$count = "<span style=\"font-weight:bold;\">".$count."</span>";
		?>
		<table width="100%" style="border-bottom: thin solid #CCCCCC;">
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
		<?php
		if ($result) {
			foreach ($result as $match) {
				?>
				<table width="100%" border="0" style="border-bottom: thin solid #CCCCCC;">
				<tr>
					<td width="<?php echo $rsgConfig->get('thumb_width');?>">
					<div class="img-shadow">
						<a href="index.php?option=com_rsgallery2&page=inline&id=<?php echo $match->item_id;?>">
						<img border="0" src="<?php echo JURI_SITE.$rsgConfig->get('imgPath_thumb') . "/" . imgUtils::getImgNameThumb($match->itemname);?>" alt="image" />
						</a>
					</div>
					</td>
					<td valign="top">
						<a href="index.php?option=com_rsgallery2&page=inline&id=<?php echo $match->item_id;?>">
							<span style="font-weight:bold;"><?php echo galleryUtils::highlight_keywords($match->title, $searchtext);?></span>
						</a>
						<p><?php echo galleryUtils::highlight_keywords($match->descr, $searchtext);?></p>
						<p style="color: #999999;font-size:10px;">
							[<?php echo _RSGALLERY_USERCAT_NAME;?>:<a href="<?php echo JURI_SITE."/index.php?option=com_rsgallery2&amp;gid=".$match->gallery_id."&amp;Itemid=".$Itemid;?>"><?php echo $match->name;?></a>]
							<?php
							if ($match->userid > 0) {
								echo "["._RSGALLERY_GAL_OWNER.":&nbsp;".galleryUtils::genericGetUsername($match->userid)."]";
							}
						?>
						</p>
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