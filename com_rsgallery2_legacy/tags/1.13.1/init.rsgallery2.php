<?php
/**
* This file handles the initialization required for core functionality.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Access Denied.' );

// create global variables in case we are not in the global scope.
global $rsgConfig, $rsgAccess, $rsgVersion, $rsgOption;

// check if this file has been included yet.
if( isset( $rsgConfig )) return;

//Prepare for Joomla 1.5
if( !defined( '_JEXEC' )){
    DEFINE('JPATH_ROOT', $mosConfig_absolute_path);
}

// initialize the rsg config file
require_once(JPATH_ROOT.'/administrator/components/com_rsgallery2/includes/config.class.php');
$rsgConfig = new rsgConfig();

//Set path globals for RSGallery2
DEFINE('JPATH_RSGALLERY2_SITE', JPATH_ROOT.'/components/com_rsgallery2');
DEFINE('JPATH_RSGALLERY2_ADMIN', JPATH_ROOT.'/administrator/components/com_rsgallery2');
DEFINE('JPATH_RSGALLERY2_LIBS',JPATH_ROOT.'/components/com_rsgallery2/lib');
DEFINE('JPATH_ORIGINAL', JPATH_ROOT.$rsgConfig->get('imgPath_original') );
DEFINE('JPATH_DISPLAY', JPATH_ROOT.$rsgConfig->get('imgPath_display') );
DEFINE('JPATH_THUMB', JPATH_ROOT.$rsgConfig->get('imgPath_thumb') );

DEFINE ('DS', "/");

$rsgOptions_path = JPATH_RSGALLERY2_ADMIN.'/options/';
$rsgClasses_path = JPATH_RSGALLERY2_ADMIN.'/includes/';

require_once(JPATH_RSGALLERY2_ADMIN.'/includes/version.rsgallery2.php');
$rsgVersion = new rsgalleryVersion();

// report all errors if in debug mode
if ($rsgConfig->get('debug'))
    error_reporting(E_ALL);

//include ACL class
require_once(JPATH_RSGALLERY2_ADMIN.'/includes/access.class.php');
$rsgAccess = new rsgAccess();

// require file utilities
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/file.utils.php' );
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/img.utils.php' );
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/video.utils.php' );

// contains misc. utility functions
require_once(JPATH_RSGALLERY2_ADMIN.'/config.rsgallery2.php');
require_once(JPATH_RSGALLERY2_ADMIN.'/includes/gallery.class.php');

//Check for language files, if not found, default to english
if (file_exists(JPATH_RSGALLERY2_ADMIN.'/language/'.$mosConfig_lang.'.php')){
    include_once(JPATH_RSGALLERY2_ADMIN.'/language/'.$mosConfig_lang.'.php');
} else {
    include_once(JPATH_RSGALLERY2_ADMIN.'/language/english.php');
}

?>