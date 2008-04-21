<?php
/**
 * @version		$Id: controller.php 9812 2008-01-03 00:42:44Z eddieajau $
 * @package		Joomla
 * @subpackage	Installer
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
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
class InstallerController extends JController
{
	/**
	 * Display the extension installer form
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function installform()
	{
		$model	= &$this->getModel( 'Install' );
		$view	= &$this->getView( 'Install', '', '', array( 'base_path'=>rsgOptions_installer_path ) );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		$view->setModel( $model, true );
		$view->display();
	}

	/**
	 * Install an extension
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function doInstall()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$model	= &$this->getModel( 'Install' );
		$view	= &$this->getView( 'Install' , '', '', array( 'base_path'=>rsgOptions_installer_path ) );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		if ($model->install()) {
			$cache = &JFactory::getCache('mod_menu');
			$cache->clean();
		}

		$view->setModel( $model, true );
		$view->display();
	}

	/**
	 * List all templates
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function manage()
	{
		$model	= &$this->getModel( 'templates' );
		$view	= &$this->getView( 'templates' , '', '', array( 'base_path'=>rsgOptions_installer_path ) );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		$view->setModel( $model, true );
		$view->display();
	}

	/**
	 * Set template as default
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function setDefault()
	{
		
		global $rsgConfig;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or die( 'Invalid Token' );

		$template = JRequest::getVar( 'templateName' );
		$rsgConfig->set('template', $template);
		$this->manage();

	}
	
	
	/**
	 * Remove an extension (Uninstall)
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function remove()
	{
		global $rsgConfig;
		
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$template = JRequest::getVar( 'templateName' );

		if($rsgConfig->template == $template) {
			JError::raiseWarning( 500, 'Can not delete default template.', "Select an other template and then delete this one." );
		}
		else{
			JFolder::delete(JPATH_RSGALLERY2_SITE . DS . "templates" . DS . $template);
		}

		$this->manage();		

	}
	
	/**
	 * edit the base data of a template
	 * @access	public
	 * @return	void
	 * @since	RSG 1.5
	 * @author John Caprez (john@porelaire.com)
	 */
	function editTemplate(){
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	= &$this->getModel( 'template' );
		$view	= &$this->getView( 'template' , '', '', array( 'base_path'=>rsgOptions_installer_path ) );
		
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);
		
		$template = JRequest::getVar( 'templateName' );
		
		// Update to handle components radio box
		// Checks there is only one extensions, we're uninstalling components
		// and then checks that the zero numbered item is set (shouldn't be a zero
		// if the eid is set to the proper format)
		if((count($eid) == 1) && ($type == 'components') && (isset($eid[0]))) $eid = array($eid[0] => 0);
		
		$model->template = $template;
		
		$view->setModel( $model, true );
		$view->display();
		
	}

	/**
	 * apply chnages to template
	 * @access	public
	 * @return	void
	 * @since	RSG 1.5
	 * @author John Caprez (john@porelaire.com)
	 */
	function applyTemplate(){
		JError::raiseWarning( 500, 'not implemented' );
	}
	/**
	* save chenges to template
	* @access	public
	* @return	void
	* @since	RSG 1.5
	* @author John Caprez (john@porelaire.com)
	*/
	function saveTemplate(){
		JError::raiseWarning( 500, 'not implemented' );
	}
	
	/**
	* cancel changes to template
	* @access	public
	* @return	void
	* @since	RSG 1.5
	* @author John Caprez (john@porelaire.com)
	*/
	function cancelTemplate(){
		$this->manage();
//		JError::raiseWarning( 500, 'not implemented' );
	}
	
	
	/**
	 * select witch css file has to be edited
	 * @access	public
	 * @return	void
	 * @since	RSG 1.5
	 * @author John Caprez (john@porelaire.com)
	 */
	function selectCSS(){
		JError::raiseWarning( 500, 'not implemented' );
	}
	
	/**
	* edit a CSS file
	* @access	public
	* @return	void
	* @since	RSG 1.5
	* @author John Caprez (john@porelaire.com)
	*/
	function editCSS(){
		JError::raiseWarning( 500, 'not implemented' );
	}

	/**
	 * select witch html file has to be edited
	 * @access	public
	 * @return	void
	 * @since	RSG 1.5
	 * @author John Caprez (john@porelaire.com)
	 */
	function selectHTML(){
		JError::raiseWarning( 500, 'not implemented' );
	}
	
	/**
	* edit a HTML file
	* @access	public
	* @return	void
	* @since	RSG 1.5
	* @author John Caprez (john@porelaire.com)
	*/
	function editHTML() {
		JError::raiseWarning( 500, 'not implemented' );
	}
}
