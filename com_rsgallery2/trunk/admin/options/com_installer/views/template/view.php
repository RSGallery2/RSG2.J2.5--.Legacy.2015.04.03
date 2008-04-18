<?php
/**
 * @version		$Id: view.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * RSGallery2 Template Manager Templates View
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */

include_once(dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php');

class InstallerViewTemplate extends InstallerViewDefault
{
	function display($tpl=null)
	{
		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::save('saveTemplate','Save');
		JToolBarHelper::apply('applyTemplate', 'Apply');
		JToolBarHelper::spacer();
		JToolBarHelperRSG::showCss( 'editCSS', 'Edit CSS' );
		JToolBarHelperRSG::showHtml( 'editHTML', 'Edit HTML' );
		JToolBarHelper::cancel( 'cancelTemplate', 'Cancel' );
		JToolBarHelper::help( 'screen.installer2' );
		
		// Get data from the model
		$item = &$this->get('Item');
		
		$this->assignRef('item', $item);
		
		parent::display($tpl);
	}
	
	function isParamWriteable(){
		
		$templatefile = JPATH_RSGALLERY2_SITE .DS. 'templates' .DS. $this->item->template .DS. 'params.ini';
		return is_writable($templatefile) ? JText::_( 'Writable' ):JText::_( 'Unwritable' );
				
	}
}
