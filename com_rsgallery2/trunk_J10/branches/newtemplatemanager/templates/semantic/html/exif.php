<?php defined('_JEXEC') or die('Restricted access'); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="imageExif">
	<tr>
		<th>Section</th>
		<th>Name</th>
		<th>Value</th>
	</tr>
<?php
		foreach ($this->exif as $key => $section):
			foreach ($section as $name => $val):
?>
	<tr>
		<td class="exifKey"><?php echo $key;?></td>
		<td class="exifName"><?php echo $name;?></td>
		<td class="exifVal"><?php echo $val;?></td>
	</tr>
<?php
			endforeach;
		endforeach;
?>
</table>
