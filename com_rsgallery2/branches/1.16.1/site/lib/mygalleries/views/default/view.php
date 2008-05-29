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

class ViewDefault extends JView
{
	function display($tpl=null)
	{
		global $rsgConfig;
		
		$items		= &$this->get('Items');
		$pagination	= &$this->get('Pagination');

		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		
		jimport("joomla.html.pane");
		$tabs =& JPane::getInstance("Tabs");

		$this->assignRef('tabs', $tabs);
		$this->assignRef('canUploadImages', $rsgConfig->get('uu...') );
		
		parent::display($tpl);
	}
	
}
