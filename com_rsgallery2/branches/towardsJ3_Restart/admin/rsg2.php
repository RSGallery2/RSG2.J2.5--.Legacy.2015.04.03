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

// Include the JLog class.
jimport('joomla.log.log');

/// Get the date for log file name
$date = JFactory::getDate()->format('Y-m-d');

// Add the logger.
JLog::addLogger(
     // Pass an array of configuration options
    array(
            // Set the name of the log file
            //'text_file' => substr($application->scope, 4) . ".log.php",
            'text_file' => 'rsg2ctrl.log.'.$date.'.php',

            // (optional) you can change the directory
            'text_file_path' => 'logs'
     ),
	  JLog::ALL ^ JLog::DEBUG // leave out db messages
);

// start logging...
// identify active file
JLog::add('==> base.rsg2.php ');

// import joomla controller library
jimport('joomla.application.component.controller');
 
//Access check
$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsg2');
$canManage	= JFactory::getUser()->authorise('core.manage',	'com_rsg2');
if (!$canManage) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('Rsg2');
$input = JFactory::getApplication()->input; 
$task = $input->get('task');
//JLog::add('Rsg2.php Task :"' + $task + '"');
JLog::add('  base.rsg2.task: ', $task);

$controller->execute($task);
$controller->redirect();

/*
	function galleries()
	{
		JLog::add('==> root/rsg2.php/function galleries');
		$this->setRedirect('index.php?option=com_rsg2&view=galleries');
		$this->redirect();
	}	

	function ClearRsg2DbItems ()
	{
		JLog::add('==> root/rsg2.php/function ClearRsg2DbItems');
		$this->setRedirect('index.php?option=com_rsg2&view=galleries');
		$this->redirect();
	}

	function TestTask ()
	{
		JLog::add('==> root/rsg2.php/function TestTask');
		$this->setRedirect('index.php?option=com_rsg2&view=galleries');
		$this->redirect();
	}
*/



