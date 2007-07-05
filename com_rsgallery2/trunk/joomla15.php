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
	define( 'JEMU15', 1 );
	
	// setup a Joomla 1.5 environment
	define( '_JEXEC', 1 );
	define ('DS', "/");
	define( 'JPATH_BASE', $mosConfig_absolute_path );
	define( 'JPATH_ROOT', $mosConfig_absolute_path );
	define( 'JPATH_LIBRARIES', JPATH_BASE . '/components/com_rsgallery2/lib/joomla_1.5/libraries' );
}

// joomla 1.5 lib imports
if( defined( 'JEMU15' )){
	require_once(JPATH_LIBRARIES . DS . 'loader.php');
	/** 
	 * No need to include these now.
	 * Call jimport when needed for a specific library
	 */
	 /*
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
	*/
}
?>