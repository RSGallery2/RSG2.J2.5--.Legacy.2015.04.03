<?php
/**
* Must have debug enabled to use this template.  Lists all galleries and items.
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'debug_listeverything';
require_once( $templatePath . DS . 'display.class.php');

global $mosConfig_live_site;
$template_dir = "$mosConfig_live_site/components/com_rsgallery2/templates/debug_listeverything";
?>
<link href="<?php echo $template_dir ?>/css/template.css" rel="stylesheet" type="text/css" />
<?php

$gid = rsgInstance::getInt('gid', 0); 

echo "Listing contents of Gallery #$gid";

listEverything( $gid );


?>