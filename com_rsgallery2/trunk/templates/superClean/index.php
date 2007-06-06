<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'superClean';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_superClean();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/superClean";
$lightbox_dir = "$mosConfig_live_site/components/com_rsgallery2/lib/lightbox_plus";

// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();
?>
<script language="Javascript">
function showInfo(name, title, description, src) {
		var html=name;
		html = '<div class="popup"><img src="'+src+'" /><div>'+title+'</div></div>';
// 		return overlib(html, CAPTION, title);
		return overlib(html, FULLHTML);
}
</script>

<?php // lightbox_plus popup image display ?>
<link href="<?php echo $lightbox_dir; ?>/lightbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $lightbox_dir; ?>/spica.js"></script>
<script type="text/javascript" src="<?php echo $lightbox_dir; ?>/lightbox_plus.js"></script>

<link href="<?php echo $template_dir; ?>/css/template.css" rel="stylesheet" type="text/css" />

<div id='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
</div>