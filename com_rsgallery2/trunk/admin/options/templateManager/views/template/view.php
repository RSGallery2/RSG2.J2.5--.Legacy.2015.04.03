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
		JToolBarHelper::custom( 'editCSS', 'css.png', 'css_f2.png', 'Edit CSS', false, false );
		JToolBarHelper::custom( 'editHTML', 'html.png', 'html_f2.png', 'Edit HTML', false, false );
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
