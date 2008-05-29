<?php
/**
 * @version		$Id: controller.php john caprez $
 * @package		RSGallery
 * @subpackage	MyGallery
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
jimport('joomla.client.helper');

/**
 * Installer Controller
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class MyGalleryController extends JController
{

	function deault()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
	
		$model	= &$this->getModel( 'default' );
		$view	= &$this->getView( 'default' );
		
		$view->addTemplatePath(RSGALLERY template path . template . mygalleries .ds. default);
		
		 
		$view->setModel( $model, true );
		$view->display();
		
	}
	
	
} 
?>
