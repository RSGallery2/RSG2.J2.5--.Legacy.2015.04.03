<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
global $mainframe;
// add link to css
?>
<link href="<?php echo JURI_SITE; ?>/components/com_rsgallery2/tpl/<?php echo $rsgConfig->get('template'); ?>/css/template_css.css" rel="stylesheet" type="text/css" />

<table border="0" width="100%">
<tr>
    <td>
    <?php
    /* Top bar, containing menu items et */
    $tpl->showRSTopBar();
    ?>
    </td>
</tr>
<tr>
	<td>
	<?php $tpl->showIntroText();?>
	</td>
</tr>
<tr>
    <td valign="top">
    <?php
    /** Main gallerypage, displaying main galleries. This one should be with a few options, like:
     *  $type:
     *  	single	: Single row, like the standard  RSGallery2
     *  	double	: Double row, meaning two items per row
     *  	box 	: Box style, like the thumbnail page, 3 boxes per row
     *  	custom	: Customized gallery block, using template file(Testing only!)
     *  $subgalleries: show subgalleries (true), or not (false)
     */
    $tpl->showMainGalleries('single', 3, true);
    ?>
    </td>
</tr>
<tr>
    <td>
        <?php
        /** 
         * Show random images
         * $style: vert for vertical, hor for horizontal(default setting)
         */
        $tpl->showRandom();
        ?>
    </td>
</tr>
<tr>
    <td>
        <?php
        /** 
         * Show latest images
         * $style: vert for vertical, hor for horizontal(default setting)
         */
        $tpl->showLatest();
        ?>
    </td>
</tr>
</table>