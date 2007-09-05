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

This is built to imitate the Joomla 1.0.* style of templating.  Hopefully that is enlightening.

**/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'tables' . DS . 'display.class.php');
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'tables_wKirupa';
require_once( $templatePath . DS . 'display.class.php');

$rsgDisplay = new rsgDisplay_tables_wKirupa();

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/". $rsgConfig->get('template');

//Load overlib routine for Tooltips
mosCommonHTML::loadOverlib();

//include page navigation
require_once(JPATH_ROOT.'/includes/pageNavigation.php');

// why are we doing this?
if ( !$rsgConfig->get('debug') ) {
    ?>  
    <!--  Hide status bar information -->
    <script type="text/javascript">
        function hidestatus(){
            window.status='';
            return true;
            }
        if (document.layers)
            document.captureEvents(Event.MOUSEOVER | Event.MOUSEOUT);
            document.onmouseover=hidestatus;
            document.onmouseout=hidestatus;
    </script>
    <?php
}

$rsgDisplay->metadata();
// append to Joomla's pathway
$rsgDisplay->showRSPathWay();
?>

<link href="<?php echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" />

<table border="0" width="100%">
<tr>
    <td>
    <?php
    /* Top bar, containing menu items et */
    $rsgDisplay->showRSTopBar();
    ?>
    </td>
</tr>
<tr>
    <td>
    <?php $rsgDisplay->showIntroText();?>
    </td>
</tr>
<tr>
    <td valign="top">
    
    <?php
    /** Main gallerypage, displaying main galleries. This one should be with a few options, like:
     *  $type:
     *      single  : Single row, like the standard  RSGallery2
     *      double  : Double row, meaning two items per row
     *      box     : Box style, like the thumbnail page, 3 boxes per row
     *      custom  : Customized gallery block, using template file(Testing only!)
     *  $subgalleries: show subgalleries (true), or not (false)
     $rsgDisplay->showMainGalleries('single', 3, true);
     */
     
     // show the main page being requested
     $rsgDisplay->mainPage();
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
        $rsgDisplay->showRandom();
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
        $rsgDisplay->showLatest();
        ?>
    </td>
</tr>
</table>