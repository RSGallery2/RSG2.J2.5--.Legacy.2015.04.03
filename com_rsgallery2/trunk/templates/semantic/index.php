<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

/**

ATTENTION!

This is built to imitate the Joomla 1.5.* style of templating.  Hopefully that is enlightening.

**/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'semantic';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_semantic();

// set itemid
$rsgDisplay->setItemid();

global $mosConfig_live_site;
$template_dir = $mosConfig_live_site . DS ."components" . DS . "com_rsgallery2". DS . "templates" . DS . $rsgConfig->get('template');

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();

//include page navigation
require_once(JPATH_ROOT.'/includes/pageNavigation.php');
?>

<link href="<?php echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" />

<div id="rsg2">
	<?php $rsgDisplay->mainPage(); ?>
</div>