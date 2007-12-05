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

function com_uninstall()
	{
	
	//require_once("../configuration.php");
	//$database =& JFactory::getDBO();
	echo _RSGALLERY_UNINSTALL_OK;
	}
?>
