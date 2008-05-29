		?>
		<table width="100%" class="adminlist" border="1">
		<tr>
		<td colspan="4"><h3><?php echo JText::_('My Images'); ?></h3></td>
		</tr>
		<tr>
		<th colspan="4"><div align="right"><?php  echo $pageNav->getLimitBox(); ?></div></th>
		</tr>
		<tr>
		<th><?php echo JText::_('Name'); ?></th>
		<th><?php echo JText::_('Gallery'); ?></th>
		<th width="75"><?php echo JText::_('Delete'); ?></th>
		<th width="75"><?php echo JText::_('Edit'); ?></th>
		</tr>
		
<?php
if (count($images) > 0) {
			?>
			<script type="text/javascript">
			//<![CDATA[
			function deleteImage(id)
			{
			var yesno = confirm (' <?php echo JText::_('Are you sure you want to delete this image?');?>');
			if (yesno == true) {
			location = " <?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=deleteItem&id=", false);?>"+id;
			}
			}
			//]]>
			</script>
	<?php
	foreach ($images as $image)
	{
		global $rsgConfig;
				?>
				<tr>
				<td>
		<?php
		if (!$rsgAccess->checkGallery('up_mod_img', $image->gallery_id)) {
			echo $image->name;
		} else {
			echo JHTML::tooltip('<img src="'.JURI::root().$rsgConfig->get('imgPath_thumb').'/'.$image->name.'.jpg" alt="'.$image->name.'" />',
					JText::_('Edit image'),
					$image->name,
					$image->title.'&nbsp;('.$image->name.')',
					"index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editItem&id=".$image->id,
					1);
		}
				?>
				</td>
				<td><?php echo galleryUtils::getCatnameFromId($image->gallery_id)?></td>
				<td>
		<?php
		if (!$rsgAccess->checkGallery('del_img', $image->gallery_id)) {
					?>
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_delete.png" alt="" width="12" height="12" >
					</div>
			<?php
		} else {
					?>
					<a href="javascript:deleteImage(<?php echo $image->id;?>);">
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/delete.png" alt="" width="12" height="12" >
					</div>
					</a>
			<?php
		}
				?>
				</td>
				<td>
		<?php
		if ( !$rsgAccess->checkGallery('up_mod_img', $image->gallery_id) ) {
					?>
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_edit.png" alt="" width="15" height="15" >
					</div>
			<?php
		} else {
					?>
					<a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editItem&id=$image->id");?>">
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/edit.png" alt="" width="15" height="15" >
					</div>
					</a>
			<?php
		}
				?>
				</td>
				</tr>
		<?php
	}
}
else
{
			?>
			<tr><td colspan="4"><?php echo JText::_('No images in user galleries'); ?></td></tr>
	<?php
}
		?>
		<tr>
		<th colspan="4">
		<div align="center">
<?php 
echo $pageNav->getPagesLinks();
echo "<br>".$pageNav->getPagesCounter();
		?>
		</div>
		</th>
		</tr>
		</table>
		<?php
