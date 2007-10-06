<?php
/**
* This file contains the main template file for RSGallery2.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

/**
*ATTENTION!
*This is built to imitate the Joomla 1.0.* style of templating.  Hopefully that is enlightening.
**/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'semantic' . DS . 'display.class.php');
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'xspf_table';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_xspf_table();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/". $rsgConfig->get('template');

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();


//include page navigation
require_once(JPATH_ROOT.'/includes/pageNavigation.php');


$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();
?>

<!-- <link href="<?php //echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" /> -->
<style type="text/css" media="screen">
	.xspf_semantic {
	}
		.xspf_semantic .xspf_semantic_gallery_title {
		}
		.xspf_semantic .xspf_semantic_gallery_desc {
		}
		.xspf_semantic ul.xspf_semantic_item {
			list-style-type: none;
		}
			.xspf_semantic .xspf_semantic_item li.xspf_semantic_item_title {
				background: none;
			}
			.xspf_semantic .xspf_semantic_item li.xspf_semantic_item_desc {
				background: none;
			}
			.xspf_semantic .xspf_semantic_item li.xspf_semantic_item_player {
				background: none;
			}
			.xspf_semantic .xspf_semantic_item li.xspf_semantic_item_download {
				background: none;
				border: none;
			}
				.xspf_semantic .xspf_semantic_item li.xspf_semantic_item_download img {
					border: none;
				}
</style>
<div class="xspf_semantic">
<?php   
	// show the main page being requested
	$rsgDisplay->mainPage();
?>
</div>
