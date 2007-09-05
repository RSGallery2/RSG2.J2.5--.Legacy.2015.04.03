<?php
/**
* This file contains the template file for RSGallery2 highslideJS template.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
* Template created by Daniël Tulp, DT^2 (http://design.danieltulp.nl)
* On basis by piran rsgallery2@dreckly.org; And Jonah, leaddeveloper RSGallery2 project
* Version 0.2.2
*/
defined( '_VALID_MOS' ) or die( 'Restricted Access' );
//set directories
global $mosConfig_live_site;
$template_dir = JPATH_RSGALLERY2_SITE."/templates/supercleanhs";
$template_dir_live = "$mosConfig_live_site/components/com_rsgallery2/templates/supercleanhs";

//include required files
require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'semantic' . DS . 'display.class.php');
require_once( $template_dir.'/display.class.php');

//new rsgDisplay
$rsgDisplay = new rsgDisplay_superCleanHS();

//set pathway
$rsgDisplay->showRSPathWay();

//link stylesheets and js file
?>
<link rel="stylesheet" href="<?php echo $template_dir_live ?>/js_highslide/highslide.css" type="text/css" />
<script type="text/javascript" src="<?php echo $template_dir_live?>/js_highslide/highslide.js"></script>

<link href="<?php echo $template_dir_live?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mosConfig_live_site?>/components/com_rsgallery2/templates/semantic/css/template.css" rel="stylesheet" type="text/css" />

<?php 
//set language
global $mosConfig_lang;
if (file_exists($template_dir.'/language/'.$mosConfig_lang.'.php')){
    include_once($template_dir.'/language/'.$mosConfig_lang.'.php');
} else {
    include_once($template_dir.'/language/english.php');
}

//set parameters here later variable
?>
  
<script type="text/javascript">
    hs.expandSteps = 50; // number of steps in zoom. Each step lasts for duration/step milliseconds.
    hs.expandDuration = 500; // milliseconds
    hs.restoreSteps = 50;
    hs.restoreDuration = 500;
    hs.allowMultipleInstances = true;
    hs.hideThumbOnExpand = true;
    hs.captionSlideSpeed = 1; // set to 0 to disable slide in effect
    hs.outlineWhileAnimating = false; // not recommended for image popups, animation gets jerky on slow systems.
    hs.outlineStartOffset = 3; // was 3, ends at 10
    hs.marginLeft = 10;
    hs.marginRight = 35; // leave room for scrollbars + outline
    hs.marginTop = 35;
    hs.marginBottom = 35; // leave room for scrollbars + outline
    hs.zIndexCounter = 1001; // adjust to other absolutely positioned elements   
    // internationalization:
    hs.fullExpandTitle = '<?php echo SCHS_EXPAND ?>';
    hs.restoreTitle = '<?php echo SCHS_CLICK_DRAG ?>';
    hs.focusTitle = '<?php echo SCHS_CLICK_FRONT ?>';
    hs.loadingText = '<?php echo SCHS_LOADING ?>';
	hs.graphicsDir = '<?php echo $template_dir_live; ?>/js_highslide/graphics/'; 
	hs.outlineType = 'drop-shadow';
	hs.showCredits = true; // you can set this to false if you want
	hs.creditsText = 'Powered by <i>Highslide JS</i>';
	hs.creditsHref = 'http://vikjavev.no/highslide/?user=1';
	hs.creditsTitle = 'Go to the Highslide JS homepage';
	hs.creditsText2 = 'RSG2 Template by <i>DT^2</i>';
	hs.creditsHref2 = 'http://design.danieltulp.nl';
	hs.creditsTitle2 = 'Go to DT^2 website';
	// These settings can also be overridden inline for each image
	hs.anchor = 'auto'; // auto, where the image expands from
	hs.align = 'center'; // auto, or center, position in the client (overrides anchor)
	hs.targetX = null; // the id of a target element
	hs.targetY = null;
	hs.captionId = null;
	hs.captionTemplateId = null;
	hs.slideshowGroup = null; // defines groups for next/previous links and keystrokes
	hs.spaceForCaption = 30; // leaves space below images with captions
	hs.minWidth = 200;
	hs.minHeight = 200;
	hs.allowSizeReduction = true; // allow the image to reduce to fit client size. If false, this overrides minWidth and minHeight
	hs.wrapperClassName = null; // for enhanced css-control
	hs.enableKeyListener = true;
  	hs.registerOverlay(
    	{
      	thumbnailId: null,
      	overlayId: 'controlbar',
      	position: 'top center',
     	hideOnMouseOut: true,
      	opacity: 0.50
    	}
  	);
</script>

<div id='rsg2'>
	<?php $rsgDisplay->mainPage(); ?>
</div>