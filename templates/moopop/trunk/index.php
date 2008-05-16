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
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'moopop';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_moopop();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/moopop";

//$rsgDisplay->metadata();
// append to Joomla's pathway
//$rsgDisplay->showRSPathWay();

$doc =& JFActory::getDocument();

$doc->addStyleSheet($template_dir."/css/template.css","text/css");

$doc->addScript( "$template_dir/tips.js" );
?>

<div class='rsg2-moopop'>
	<?php echo $rsgDisplay->mainPage(); ?>
</div>
