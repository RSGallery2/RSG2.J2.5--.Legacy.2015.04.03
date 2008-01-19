<?php
/**
* This file contains the template for the RSGallery2 thumbs view.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
global $mainframe;
?>

<link href="<?php echo JURI_SITE; ?>/components/com_rsgallery2/tpl/<?php echo $rsgConfig->get('template'); ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0">
<tr>
	<td>
		<?php
		/**
		 * This will show any subgalleries in this gallery.
		 */
		$tpl->showMainGalleries('single', $subgalleries = true);
		?>
	</td>
</tr>
<tr>
	<td>
		<?php
		/**
		 * This will show the thumbs from the current gallery
		 */
		$tpl->showThumbs();?></td>
</tr>
</table>