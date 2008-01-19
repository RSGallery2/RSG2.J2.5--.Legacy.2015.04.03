<?php
/**
* This file contains the template for the RSGallery2 display view.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
global $mainframe;
?>
<link rel="stylesheet" href="<?php echo JURI_SITE; ?>/components/com_rsgallery2/tpl/<?php echo $rsgConfig->get('template'); ?>/css/template_css.css" type="text/css" />
<table width="100%" border="1">
<tr>
	<td>
	<?php echo $block->status; ?>
	</td>
</tr>
<tr>
	<td>
	<?php echo $block->galleryName;?>
	</td>
</tr>
<tr>
	<td>
	<?php echo $block->thumbHTML; ?>
	</td>
</tr>
<tr>
	<td>
	<?php echo $block->description; ?>
	</td>
</tr>
<tr>
	<td>
	<?php echo $block->galleryDetails; ?>
	</td>
</tr>
</table>