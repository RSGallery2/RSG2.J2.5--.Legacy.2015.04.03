<?php
/**
* This file contains the non-presentation processing for the Admin section of RSGallery.
* @version $Id: admin.rsgallery2.php 1085 2012-06-24 13:44:29Z mirjam $
* @package RSGallery2
* @copyright (C) 2003 - 2014 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined('_JEXEC') or die;

//Access check
$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
$canManage	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
if (!$canManage) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('Rsgallery2');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
