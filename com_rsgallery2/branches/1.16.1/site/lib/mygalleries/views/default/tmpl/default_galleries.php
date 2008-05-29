		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		//Set variables
		$count = count($rows);
		?>
		<div class="rsg2">
		<table class="adminform" width="100%" border="1">
		<tr>
		<td colspan="5"><h3><?php echo JText::_('My galleries');?></h3></td>
		</tr>
		<tr>
		<th><div align="center"><?php echo JText::_('Gallery'); ?></div></th>
		<th width="75"><div align="center"><?php echo JText::_('Published'); ?></div></th>
		<th width="75"><div align="center"><?php echo JText::_('Delete'); ?></div></th>
		<th width="75"><div align="center"><?php echo JText::_('Edit'); ?></div></th>
		<th width="75"><div align="center"><?php echo JText::_('Permissions'); ?></div></th>
		</tr>
<?php
if ($count == 0) {
			?>
			<tr><td colspan="5"><?php echo JText::_('No User Galleries created'); ?></td></tr>
	<?php
} else {
	//echo "This is the overview screen";
	foreach ($rows as $row) {
				?>
				<script type="text/javascript">
				//<![CDATA[
				function deletePres(catid) {
				var yesno = confirm (" <?php echo JText::_('DELCAT_TEXT');?>");
				if (yesno == true) {
				location = " <?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=deleteCat&catid=", false);?>"+catid;
				}
				}
				//]]>
				</script>
				<tr>
				<td>
				<a href=" <?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row->id);?>">
				<?php echo $row->name;?>
				</a>
				</td>
		<?php
		if ($row->published == 1)
			$img = "publish_g.png";
		else
					$img = "publish_r.png";?>
				
				<td><div align="center"><img src="<?php echo JURI_SITE;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" ></div></td>
				<td>
				<a href="javascript:deletePres(<?php echo $row->id;?>);">
				<div align="center">
				<img src="<?php echo JURI_SITE;?>/administrator/images/publish_x.png" alt="" width="12" height="12" >
				</div>
				</a>
				</td>
				<td>
				<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row->id);?>">
				<div align="center">
				<img src="<?php echo JURI_SITE;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" >
				</div>
				</a>
				</td>
				<td><a href="#" onclick="alert('Feature not implemented yet')"><div align="center"><img src="<?php echo JURI_SITE;?>/administrator/images/users.png" alt="" width="22" height="22"></div></td>
				</tr>
		<?php
		$sql2 = "SELECT * FROM #__rsgallery2_galleries WHERE parent = $row->id ORDER BY ordering ASC";
		$database->setQuery($sql2);
		$rows2 = $database->loadObjectList();
		foreach ($rows2 as $row2) {
			if ($row2->published == 1)
				$img = "publish_g.png";
			else
						$img = "publish_r.png";?>
					<tr>
					<td>
					<img src="<?php echo JURI_SITE;?>/administrator/components/com_rsgallery2/images/sub_arrow.png" alt="" width="12" height="12" >
					&nbsp;
					<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row2->id);?>">
					<?php echo $row2->name;?>
					</a>
					</td>
					<td>
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" >
					</div>
					</td>
					<td>
					<a href="javascript:deletePres(<?php echo $row2->id;?>);">
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/administrator/images/publish_x.png" alt="" width="12" height="12" >
					</div>
					</a>
					</td>
					<td>
					<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row2->id);?>">
					<div align="center">
					<img src="<?php echo JURI_SITE;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" >
					</div>
					</a>
					</td>
					<td>
					<a href="#" onclick="alert('<?php echo JText::_('Feature not implemented yet')?>')">
					<div align="center">
					<img src="<?php echo JURI_SITE; ?>/administrator/images/users.png" alt="" width="22" height="22" >
					</div>
					</a>
					</td>
					</tr>
			<?php
		}
	}
}
		?>
		<tr>
		<th colspan="5">&nbsp;</th>
		</tr>
		</table>
		</div>
		<?php
