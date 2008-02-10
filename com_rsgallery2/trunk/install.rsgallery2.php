<?php
/**
* This file contains the install routine for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install(){
	$lang =& JFactory::getLanguage();
	$database =& JFactory::getDBO();
	
	require_once( JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php' );
	include JPATH_SITE.'/administrator/components/com_rsgallery2/language/' . strtolower($lang->get("backwardlang", "english")) . '.php';
	
	//Initialize install
	$rsgInstall = new rsgInstall();
	
	//Change the menu icon
	$rsgInstall->changeMenuIcon();
	
	//Initialize rsgallery migration
	$migrate_com_rsgallery = new migrate_com_rsgallery();
	
	//If previous version detected
	if( $migrate_com_rsgallery->detect() ){
		// now that we know a previous rsg2 was installed, we need to reload it's config
		global $rsgConfig;
		$rsgConfig = new rsgConfig();

		$rsgInstall->writeInstallMsg( _RSGALLERY_MIGRATING_FROM . $rsgConfig->get( 'version' ), 'ok');
		//Migrate from earlier version
		$result = $migrate_com_rsgallery->migrate();
		
		if( $result === true ){
			$rsgInstall->writeInstallMsg( _RSGALLERY_INSTALL_SUCCESS . $rsgConfig->get( 'version' ), 'ok');
		}
		else{
			$result = print_r( $result, true );
			$rsgInstall->writeInstallMsg( _RSGALLERY_INSTALL_FAIL."\n<br><pre>$result\n</pre>", 'error');
		}
	}
	else{
		//No earlier version detected, do a fresh install
		$rsgInstall->freshInstall();
	}
}