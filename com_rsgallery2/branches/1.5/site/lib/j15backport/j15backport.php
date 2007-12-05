<?php
/**
* This file contains all emulation for Joomla 1.5.
* If the component is migrated to Joomla 1.5, we won't need this anymore
* @version 0.1
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//Prepare for Joomla 1.5
if( !defined( '_JEXEC' )){
	// indicate we are emulating Joomla 1.5
	define( 'J15B_EXEC', 1 );
	define( 'J15B_PATH', dirname( __FILE__ ) );
	
	// setup a Joomla 1.5 environment
	define( '_JEXEC', 1 );
	define ('DS', DIRECTORY_SEPARATOR);
	define( 'JPATH_BASE', $mosConfig_absolute_path );
	define( 'JPATH_ROOT', JPATH_BASE );
	define( 'JPATH_SITE', JPATH_BASE );
	
	// these next two are not implemented, but the constants are expected, so we'll define them
	define( 'JPATH_INSTALLATION', JPATH_BASE );
	define( 'JPATH_XMLRPC', JPATH_BASE );
	
	define( 'JPATH_ADMINISTRATOR', JPATH_SITE .DS. 'administrator' );
	define( 'JPATH_LIBRARIES', J15B_PATH . DS . 'libraries' );
	define( 'JPATH_THEMES', JPATH_ROOT . DS . 'templates' );
}

// joomla 1.5 lib imports
if( defined( 'J15B_EXEC' )){
	if ( file_exists(JPATH_LIBRARIES . DS . 'loader.php') ) {
		require_once(JPATH_LIBRARIES . DS . 'loader.php');
	}

	jimport( 'joomla.methods' );
	jimport( 'joomla.base.object' );
	jimport( 'joomla.environment.request' );
	// Include object abstract class
	jimport( 'joomla.utilities.compat.compat' );

	// Joomla! library imports
	jimport( 'joomla.environment.response'   );
	jimport( 'joomla.application.application' );
	jimport( 'joomla.application.helper' );
	jimport( 'joomla.application.menu' );
	jimport( 'joomla.database.table' );
	jimport( 'joomla.user.user');
	jimport( 'joomla.environment.uri' );
	jimport( 'joomla.factory' );
	jimport( 'joomla.html.html' );
	jimport( 'joomla.html.parameter' );
	jimport( 'joomla.utilities.array' );
	jimport( 'joomla.utilities.error' );
	jimport( 'joomla.utilities.utility' );
	jimport( 'joomla.utilities.string' );
	jimport( 'joomla.version' );
	
	/* New in Joomla 1.5 RC-3 */
	jimport( 'joomla.filter.output' );
	jimport( 'joomla.event.*');
}