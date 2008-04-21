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
 * RSGallery2 Template Manager Templates Model
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelTemplates extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'template';

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

	function _loadItems()
	{
		global $mainframe, $option, $rsgConfig;

		$db = &JFactory::getDBO();

		$clientInfo =& $rsgConfig->getClientInfo( 'site', true );
		$client = $clientInfo->name;
		$templateDirs = JFolder::folders($clientInfo->path.DS.'templates');

		for ($i=0; $i < count($templateDirs); $i++) {
			$template = new stdClass();
			$template->folder = $templateDirs[$i];
			$template->client = $clientInfo->id;
			$template->baseDir = $clientInfo->path.DS.'templates';

			if ($this->_state->get('filter.string')) {
				if (strpos($template->folder, $this->_state->get('filter.string')) !== false) {
					$templates[] = $template;
				}
			} else {
				$templates[] = $template;
			}
		}

		// Get a list of the currently active templates
		$inactiveList = array( 'meta' );

		$rows = array();
		$rowid = 0;
		// Check that the directory contains an xml file
		foreach($templates as $template)
		{
			$dirName = $template->baseDir .DS. $template->folder;
			$xmlFilesInDir = JFolder::files($dirName,'.xml$');

			foreach($xmlFilesInDir as $xmlfile)
			{
				$data = JApplicationHelper::parseXMLInstallFile($dirName . DS. $xmlfile);

				$row = new StdClass();
				$row->id 		= $rowid;
				$row->client_id	= $template->client;
				$row->directory = $template->folder;
				$row->baseDir	= $template->baseDir;

				if ($data) {
					foreach($data as $key => $value) {
						$row->$key = $value;
					}
				}

				$row->isDisabled = (in_array($row->directory, $inactiveList));
				$row->isDefault = ( $rsgConfig->get( 'template' ) == $template->folder);
				$row->checked_out = 0;
				$row->jname = JString::strtolower( str_replace( ' ', '_', $row->name ) );


				$rows[] = $row;
				$rowid++;
			}
		}
		$this->setState('pagination.total', count($rows));
		if($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice( $rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit') );
		} else {
			$this->_items = $rows;
		}
	}
}
