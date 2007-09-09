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
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'overview';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_overview();

// set itemid
$rsgDisplay->setItemid();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/overview";

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//include page navigation
require_once(JPATH_ROOT.'/includes/pageNavigation.php');
?>

<link href="<?php echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" />

<div class="rsg2-overview">
	<?php $rsgDisplay->mainPage(); ?>
</div>