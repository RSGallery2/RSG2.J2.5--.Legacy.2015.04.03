<?php
/**
 * @version		$Id: templates.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Import library dependencies
require_once(dirname(__FILE__).DS.'extension.php');
jimport( 'joomla.filesystem.folder' );

/**
 * RSGallery2 Template Manager Template Model
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelTemplate extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'template';
	var $template = '';
	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		global $mainframe;
		
		// Call the parent constructor
		parent::__construct();
		
		// Set state variables from the request
		$this->setState('filter.string', $mainframe->getUserStateFromRequest( "com_rsgallery2_com_installer.templates.string", 'filter', '', 'string' ));
	}
	
	function getItem()
	{
		global $mainframe;
		
		jimport('joomla.filesystem.path');
		if (!$this->template) {
			return JError::raiseWarning( 500, 'Template not specified' );
		}

		$tBaseDir	= JPath::clean(JPATH_RSGALLERY2_SITE .DS. 'templates');

		if (!is_dir( $tBaseDir . DS . $this->template )) {
			return JError::raiseWarning( 500, 'Template not found' );
		}
		$lang =& JFactory::getLanguage();
		$lang->load( 'tpl_'.$this->template, JPATH_RSGALLERY2_SITE );

		$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$this->template.DS.'params.ini';
		$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$this->template.DS.'templateDetails.xml';
		$row	= TemplatesHelper::parseXMLTemplateFile($tBaseDir, $this->template);

		jimport('joomla.filesystem.file');
		// Read the ini file
		if (JFile::exists($ini)) {
			$content = JFile::read($ini);
		} else {
			$content = null;
		}

		$params = new JParameter($content, $xml, 'template');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

		$item = new stdClass();
		$item->params = $params;
		$item->row = $row;
		$item->template = $this->template;

		return $item;

	}
}
