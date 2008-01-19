<?php
/**
* This file handles the uninstall processing for RSGallery.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

function com_uninstall(){
	
	$lang =& JFactory::getLanguage();
	//Check for language files, if not found, default to english
	if (file_exists(JPATH_RSGALLERY2_ADMIN.'/language/'.$lang->get("backwardlang","english").'.php')){
		include_once(JPATH_RSGALLERY2_ADMIN.'/language/'.$lang->get("backwardlang","english").'.php');
	} else {
		include_once(JPATH_RSGALLERY2_ADMIN.'/language/english.php');
	}	

	echo _RSGALLERY_UNINSTALL_OK;

	}
?>
