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
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'micromacro';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_microMacro();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/micromacro";

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();

$doc =& JFActory::getDocument();

$doc->addStyleSheet($template_dir."/css/lightbox.css","text/css");
$doc->addStyleSheet($template_dir."/css/template.css","text/css");

$doc->addScript( $template_dir."/js/prototype.js" );
$doc->addScript( $template_dir."/js/scriptaculous.js?load=effects" );
$doc->addScript( $template_dir."/js/lightbox.js" );

$specOverlib = <<<EOD
function showInfo(name, title, description, src) {
		var html=name;
		html = '<img src="'+src+'" class="popup2" />';
// 		return overlib(html, CAPTION, title);
		return overlib(html, FULLHTML);
}
EOD;

$doc->addScriptDeclaration( $specOverlib );
?>

<div id='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
</div>