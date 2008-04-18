<?php
/**
* @version		$Id: admin.installer.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @subpackage	Installer
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$ext	= JRequest::getWord('type');

$subMenus = array(
	'Templates' => 'templates'
	);

JSubMenuHelper::addEntry(JText::_( 'RSG2 Control Panel'), 'index2.php?option=com_rsgallery2', false);
JSubMenuHelper::addEntry(JText::_( 'Install' ), '#" onclick="javascript:document.adminForm.type.value=\'\';submitbutton(\'installer\');', !in_array( $ext, $subMenus));
foreach ($subMenus as $name => $extension) {
	JSubMenuHelper::addEntry(JText::_( $name ), '#" onclick="javascript:document.adminForm.type.value=\''.$extension.'\';submitbutton(\'manage\');', ($extension == $ext));
}

require_once(rsgOptions_installer_path . DS . 'helpers' . DS . 'template.php');
require_once(rsgOptions_installer_path . DS . 'helpers' . DS . 'toolbar.php');
require_once( rsgOptions_installer_path . DS . 'controller.php' );

$controller = new InstallerController( array(
	'default_task' => 'installform',
	'model_path'=>rsgOptions_installer_path.DS.'models',
	'view_path'=>rsgOptions_installer_path.DS.'views'
	));

$controller->execute( JRequest::getCmd('task') );
$controller->redirect();
