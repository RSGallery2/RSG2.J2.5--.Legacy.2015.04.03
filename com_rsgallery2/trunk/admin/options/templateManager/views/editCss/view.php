<?php
/**
 * @version		$Id: view.php $
 * @package		RSGallery2
 * @subpackage	Template installer
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * RSGallery2 Template Manager Templates View
 *
 * @package		RSGallery2
 * @subpackage	Template installer
 * @since		1.5
 */

include_once(dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php');

class InstallerViewEditCss extends InstallerViewDefault
{
	function display($tpl=null)
	{
		/*
		 * Set toolbar items for the page
		 */
		JToolBarHelper::save( 'saveCSS', 'Save' );
		JToolBarHelper::apply( 'applyCSS', 'Apply');
		JToolBarHelper::cancel( 'cancelCSS', 'Cancel' );
		JToolBarHelper::help( 'screen.installerSelectCss' );
		
		JRequest::setVar( 'hidemainmenu', 1 );

		// Get data from the model
		$item = &$this->get('Item');
		$this->assignRef('item', $item);
		
		parent::showTemplateHeader();
		parent::display($tpl);
	}
	
}
