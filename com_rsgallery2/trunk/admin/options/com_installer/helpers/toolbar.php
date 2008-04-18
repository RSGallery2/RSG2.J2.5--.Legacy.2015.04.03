<?php
/**
* @package		RSGallery2
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.toolbar');

/**
* Utility class for the button bar
*
* @package		RSGallery2
*/

class JToolBarHelperRSG{

	/**
	* Writes a common 'edit' button for a template css
	* @param string An override for the task
	* @param string An override for the alt text
	* @since 1.0
	*/
	function showCss($task = 'edit_cssList', $alt = 'Edit CSS')
	{
		$bar = & JToolBar::getInstance('toolbar');
		// Add an edit css button (hide)
		$bar->appendButton( 'Standard', 'editcss', $alt, $task, false, false );
	}
	/**
	* Writes a common 'edit' button for a template html
	* @param string An override for the task
	* @param string An override for the alt text
	* @since 1.0
	*/
	function showHtml($task = 'edit_htmlList', $alt = 'Edit HTML')
	{
		$bar = & JToolBar::getInstance('toolbar');
		// Add an edit css button (hide)
		$bar->appendButton( 'Standard', 'edithtml', $alt, $task, false, false );
	}
}
?>