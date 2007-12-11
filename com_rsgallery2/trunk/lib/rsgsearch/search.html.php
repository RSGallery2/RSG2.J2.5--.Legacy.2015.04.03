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

class html_rsg2_search {
	function showResults($result, $searchtext) {
		global $rsgConfig, $mosConfig_live_site;
		$count = count($result);
		?>
		<table width="100%">
		<tr>
			<td><div align="right"><a href="index.php?option=com_rsgallery2">Back to Main gallery page</a></div></td>
		</tr>
		<tr>
			<td><h3>** RSGallery2 Search Results **</h3></td>
		</tr>
		<tr>
			<td><span style="font-style:italic;">** There are <?php echo $count;?> results for "<span style="font-weight:bold;";><?php echo $searchtext;?></span>" **<span></td>
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
						<img src="<?php echo $mosConfig_live_site.$rsgConfig->get('imgPath_thumb') . "/" . imgUtils::getImgNameThumb($match->itemname);?>" alt="image" />
					</td>
					<td valign="top">
						<a href="index.php?option=com_rsgallery2&page=inline&id=<?php echo $match->item_id;?>">
							<?php echo galleryUtils::highlight_keywords($match->title, $searchtext);?>
						</a>
						<p><?php echo galleryUtils::highlight_keywords($match->descr, $searchtext);?></p>
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