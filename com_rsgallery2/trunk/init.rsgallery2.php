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
    	// indicate we are emulating Joomla 1.5
	define( 'JEMU15', 1 );
	
	// setup a Joomla 1.5 environment
	define( '_JEXEC', 1 );
	define ('DS', "/");
	define( 'JPATH_BASE', $mosConfig_absolute_path );
	define( 'JPATH_ROOT', $mosConfig_absolute_path );
	define( 'JPATH_LIBRARIES', JPATH_BASE . '/components/com_rsgallery2/lib/joomla_1.5/libraries' );
	define( 'JPATH_THEMES', JPATH_BASE.DS.'templates' );
}

// initialize the rsg config file
require_once(JPATH_ROOT.'/administrator/components/com_rsgallery2/includes/config.class.php');
$rsgConfig = new rsgConfig();

// report all errors if in debug mode
if ($rsgConfig->get('debug'))
    error_reporting(E_ALL);

//Set path globals for RSGallery2
define('JPATH_RSGALLERY2_SITE', JPATH_ROOT.'/components/com_rsgallery2');
define('JPATH_RSGALLERY2_ADMIN', JPATH_ROOT.'/administrator/components/com_rsgallery2');
define('JPATH_RSGALLERY2_LIBS',JPATH_ROOT.'/components/com_rsgallery2/lib');
define('JPATH_ORIGINAL', JPATH_ROOT.$rsgConfig->get('imgPath_original') );
define('JPATH_DISPLAY', JPATH_ROOT.$rsgConfig->get('imgPath_display') );
define('JPATH_THUMB', JPATH_ROOT.$rsgConfig->get('imgPath_thumb') );

$rsgOptions_path = JPATH_RSGALLERY2_ADMIN.'/options/';
$rsgClasses_path = JPATH_RSGALLERY2_ADMIN.'/includes/';

// joomla 1.5 lib imports
if( defined( 'JEMU15' )){
	require_once(JPATH_RSGALLERY2_SITE."/lib/joomla_1.5/libraries/loader.php");
	jimport( 'joomla.base.object' );
	jimport( 'joomla.environment.request' );
	
	// Include object abstract class
	jimport( 'joomla.utilities.compat.compat' );
	
	// Joomla! library imports
	jimport( 'joomla.environment.response'   );
	jimport( 'joomla.application.application' );
	jimport( 'joomla.application.helper' );
// 	jimport( 'joomla.application.event' );
	jimport( 'joomla.application.menu' );
	jimport( 'joomla.database.table' );
	jimport( 'joomla.user.user');
	jimport( 'joomla.environment.uri' );
	jimport( 'joomla.factory' );
	jimport( 'joomla.html.html' );
	jimport( 'joomla.html.parameter' );
	jimport( 'joomla.utilities.array' );
	jimport( 'joomla.utilities.error' );
// 	jimport( 'joomla.utilities.functions' );
	jimport( 'joomla.utilities.utility' );
	jimport( 'joomla.utilities.string' );
	jimport( 'joomla.version' );
}

require_once(JPATH_RSGALLERY2_ADMIN.'/includes/version.rsgallery2.php');
$rsgVersion = new rsgalleryVersion();

//include ACL class
require_once(JPATH_RSGALLERY2_ADMIN.'/includes/access.class.php');
$rsgAccess = new rsgAccess();

// include rsgInstance
require_once(JPATH_RSGALLERY2_ADMIN.'/includes/instance.class.php');

// require file utilities
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/file.utils.php' );
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/img.utils.php' );
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/audio.utils.php' );
require_once( JPATH_RSGALLERY2_ADMIN.'/includes/items/item.php' );

// contains misc. utility functions
require_once(JPATH_RSGALLERY2_ADMIN.'/config.rsgallery2.php');
require_once(JPATH_RSGALLERY2_ADMIN.'/includes/gallery.class.php');
require_once(JPATH_RSGALLERY2_SITE.'/lib/rsgcomments/rsgcomments.class.php');

//Check for language files, if not found, default to english
if (file_exists(JPATH_RSGALLERY2_ADMIN.'/language/'.$mosConfig_lang.'.php')){
    include_once(JPATH_RSGALLERY2_ADMIN.'/language/'.$mosConfig_lang.'.php');
} else {
    include_once(JPATH_RSGALLERY2_ADMIN.'/language/english.php');
}