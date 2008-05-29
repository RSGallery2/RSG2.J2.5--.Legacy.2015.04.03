		global $rsgConfig;
		$my = JFactory::getUser();
		$editor =& JFactory::getEditor();
		
		//Load frontend toolbar class
		require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
		?>
		<script type="text/javascript">
		function submitbutton(pressbutton) {
		var form = document.form1;
		if (pressbutton == 'cancel') {
		form.reset();
		return;
		}
		
		<?php echo $editor->save('description') ; ?>
		
		// do field validation
		if (form.parent.value == "-1") {
		alert( "
		<?php echo JText::_('** You need to select a parent gallery **'); ?>" );
		} else if (form.catname1.value == "") {
		alert( " <?php echo JText::_('You must provide a gallery name.'); ?>" );
		}
		else if (form.description.value == ""){
		alert( " <?php echo JText::_('You must provide a description.'); ?>" );
		}
		else{
		form.submit();
		}
		}
		</script>
<?php
if ($rows) {
	foreach ($rows as $row){
		$catname        = $row->name;
		$description    = $row->description;
		$ordering       = $row->ordering;
		$uid            = $row->uid;
		$catid          = $row->id;
		$published      = $row->published;
		$user           = $row->user;
		$parent         = $row->parent;
	}
}
else{
	$catname        = "";
	$description    = "";
	$ordering       = "";
	$uid            = "";
	$catid          = "";
	$published      = "";
	$user           = "";
	$parent         = 0;
}
		?>
		<form name="form1" id="form1" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveCat"); ?>">
		<table width="100%">
		<tr>
		<td colspan="2"><h3><?php echo JText::_('Create Gallery'); ?></h3></td>
		</tr>
		<tr>
		
		<td align="right">
		<div style="float: right;">
<?php
// Toolbar
mosToolBar::startTable();
mosToolBar::save();
mosToolBar::cancel();
mosToolBar::endtable();
		?>
		</div>
		</td>
		
		</tr>
		</table>
		<input type="hidden" name="catid" value="<?php echo $catid; ?>" />
		<input type="hidden" name="ordering" value="<?php echo $ordering; ?>" />
		<table class="adminlist" border="1">
		<tr>
		<th colspan="2"><?php echo JText::_('Create Gallery'); ?></th>
		</tr>
		<tr>
		<td><?php echo JText::_('Top gallery');?></td>
		<td>
<?php
if (!$rsgConfig->get('acl_enabled')) {
	galleryUtils::showCategories(NULL, $my->id, 'parent');
} else {
	galleryUtils::showUserGalSelectList('up_mod_img', 'parent');
}

		?>
		</td>
		</tr>
		<tr>
		<td><?php echo JText::_('Gallery name'); ?></td>
		<td align="left"><input type="text" name="catname1" size="30" value="<?php echo $catname; ?>" /></td>
		</tr>
		<tr>
		<td><?php echo JText::_('Description'); ?></td>
		<td align="left">
		<?php echo $editor->display( 'description',  $description , '100%', '200', '10', '20' ,false) ; ?>
		</td>
		</tr>
		<tr>
		<td><?php echo JText::_('Published'); ?></td>
		<td align="left"><input type="checkbox" name="published" value="1" <?php if ($published==1) echo "checked"; ?> /></td>
		</tr>
		</table>
		</form>
		<?php