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
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'superclean';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_superclean();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/superclean";
$lightbox_dir = "$mosConfig_live_site/components/com_rsgallery2/lib/lightbox_plus";

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();

$doc =& JFActory::getDocument();

$doc->addStyleSheet($template_dir."/css/template.css","text/css");
$doc->addStyleSheet($lightbox_dir."/lightbox.css","text/css");

$doc->addScript( $lightbox_dir."/spica.js" );
$doc->addScript( $lightbox_dir."/lightbox_plus.js" );

$specOverlib = <<<EOD
function showInfo(name, title, description, src) {
		var html=name;
		html = '<div class="popup"><img src="'+src+'" /><div>'+title+'</div></div>';
// 		return overlib(html, CAPTION, title);
		return overlib(html, FULLHTML);
}
EOD;

$doc->addScriptDeclaration( $specOverlib );
?>

<div class='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
	<div style="clear: left;"></div>
</div>