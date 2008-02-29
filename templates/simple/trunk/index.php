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
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'simple';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_simple();

global $mosConfig_live_site;
$template_url = "$mosConfig_live_site/components/com_rsgallery2/templates/simple";

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();

$doc =& JFActory::getDocument();

$doc->addStyleSheet($template_url."/css/template.css","text/css");
$doc->addStyleSheet($template_url."/css/lightbox.css","text/css");

$doc->addScript( $template_url.'/js/prototype.js' );
$doc->addScript( $template_url.'/js/scriptaculous.js' );
$doc->addScript( $template_url.'/js/lightbox.js' );
$doc->addScript( $template_url.'/js/effects.js' );
?>

<div class='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
	<div style="clear: left;"></div>
</div>
