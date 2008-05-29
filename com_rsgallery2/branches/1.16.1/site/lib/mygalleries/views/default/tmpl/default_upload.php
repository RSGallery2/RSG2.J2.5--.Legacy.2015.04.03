		global $rsgConfig;
		$my = JFactory::getUser();
		$editor = JFactory::getEditor();
		
		//Load frontend toolbar class
		require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
		?>
		<script  type="text/javascript">
		function submitbuttonImage(pressbutton) {
		var form = document.uploadForm;
		if (pressbutton == 'cancel') {
		form.reset();
		return;
		}
		<?php echo $editor->save('descr') ; ?>
		
		// do field validation
		if (form.i_cat.value == "-1") {
		alert( " <?php echo JText::_('You must select a gallery.'); ?>" );
		} else if (form.i_cat.value == "0") {
		alert( " <?php echo JText::_('You must select a gallery.'); ?>" );
		} else if (form.i_file.value == "") {
		alert( " <?php echo JText::_('You must provide a file to upload.'); ?>" );
		} else {
		form.submit();
		}
		}
		
		</script>
		<form name="uploadForm" id="uploadForm" method="post" action="
		<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveUploadedItem"); ?>" enctype="multipart/form-data">
		<div class="rsg2">
		<table border="0" width="100%">
		<tr>
		<td colspan="2"><h3>
		<?php echo JText::_('Add Image');?></h3></td>
		</tr>
		<tr>
		
		<td align="right">
		<div style="float: right;">
		<table cellpadding="0" cellspacing="3" border="0" id="toolbar">
		<tr height="60" valign="middle" align="center">
		<td>
		<a class="toolbar" href="javascript:submitbuttonImage('save');" >
		<img src="<?php echo JURI::root();?>/images/save_f2.png"  alt="Save" name="save" title="Save" align="middle" /></a>
		</td>
		<td>
		<a class="toolbar" href="javascript:submitbuttonImage('cancel');" >
		<img src="<?php echo JURI::root();?>/images/cancel_f2.png"  alt="Cancel" name="cancel" title="Cancel" align="middle" /></a>
		</td>
		</tr>
		</table>
		</div>
		</td>
		
		</tr>
		<tr>
		<td>
		<table class="adminlist" border="1">
		<tr>
		<th colspan="2"><?php echo JText::_('User Upload'); ?></th>
		</tr>
		<tr>
		<td><?php echo JText::_('Gallery'); ?></td>
		<td>
<?php 
/*echo galleryUtils::galleriesSelectList(null, 'i_cat', false);*/

if (!$rsgConfig->get('acl_enabled')) {
	galleryUtils::showCategories(NULL, $my->id, 'i_cat');
} else {
	galleryUtils::showUserGalSelectList('up_mod_img', 'i_cat');
}

		?>
		</td>
		</tr>
		<tr>
		<td><?php echo JText::_('Filename') ?></td>
		<td align="left"><input size="49" type="file" name="i_file" /></td>
		</tr>
		</tr>
		<td><?php echo JText::_('Title') ?>:</td>
		<td align="left"><input name="title" type="text" size="49" />
		</td>
		</tr>
		<tr>
		<td><?php echo JText::_('Description') ?></td>
		<td align="left">
		<?php echo $editor->display( 'descr',  '' , '100%', '200', '10', '20' ,false) ; ?>
		</td>
		</tr>
<?php
if ($rsgConfig->get('graphicsLib') == '')
		{ ?>
			<tr>
			<td><?php echo JText::_('Thumb:'); ?></td>
			<td align="left"><input type="file" name="i_thumb" /></td>
			</tr>
		<?php } ?>
		<tr>
		<td colspan="2">
		<input type="hidden" name="cat" value="9999" />
		<input type="hidden" name="uploader" value="<?php echo $my->id; ?>">
		</td>
		<tr>
		<th colspan="2">&nbsp;</th>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</form>
		</div>
		<?php