<?php
/**
 * RSGallery2 template controller class
 * Derived from Joomla 1.5 TemplatesController
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class TemplatesController
{
	/**
	* Compiles a list of installed, version 4.5+ templates
	*
	* Based on xml files found.  If no xml file found the template
	* is ignored
	*/
	function viewTemplates()
	{
		global $mainframe, $option, $templateManager_path;

		// Initialize the pagination variables
		$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

		$tBaseDir = JPATH_RSGALLERY2_SITE .DS. 'templates';

		//get template xml file info
		$rows = array();
		$rows = TemplatesHelper::parseXMLTemplateFiles($tBaseDir);
		
		// set dynamic template information
		for($i = 0; $i < count($rows); $i++)  {
			$rows[$i]->published = TemplatesHelper::isTemplateDefault($rows[$i]->directory);
		}

		jimport('joomla.html.pagination');
		$page = new JPagination(count($rows), $limitstart, $limit);

		$rows = array_slice($rows, $page->limitstart, $page->limit);

		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::showTemplates($rows, $page, $option);
	}

	/**
	* Show the template with module position in an iframe
	*/
	function previewTemplate()
	{
		global $templateManager_path;
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$option 	= JRequest::getCmd('option');

		if (!$template)
		{
			return JError::raiseWarning( 500, 'Template not specified' );
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::previewTemplate($template, $option);
	}

	/**
	* Publish, or make current, the selected template
	*/
	function publishTemplate()
	{
		
		global $mainframe, $rsgConfig;

		// Initialize some variables
		$cid	= JRequest::getVar('cid', array(), 'method', 'array');
		$cid	= array(JFilterInput::clean(@$cid[0], 'cmd'));
		$option	= JRequest::getCmd('option');


		if ($cid[0])
		{
			$currentTemplate = $rsgConfig->set('template', $cid[0]);
			$rsgConfig->saveConfig();
		}

		$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager');
	}


	function editTemplate()
	{
		global $templateManager_path;
		
		jimport('joomla.filesystem.path');

		// Initialize some variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'method', 'array');
		$cid		= array(JFilterInput::clean(@$cid[0], 'cmd'));
		$template	= $cid[0];
		$option		= JRequest::getCmd('option');

		if (!$cid[0]) {
			return JError::raiseWarning( 500, 'Template not specified' );
		}

		$tBaseDir	= JPath::clean(JPATH_RSGALLERY2_SITE .DS. 'templates');

		if (!is_dir( $tBaseDir . DS . $template )) {
			return JError::raiseWarning( 500, 'Template not found' );
		}
		$lang =& JFactory::getLanguage();
		$lang->load( 'tpl_'.$template, JPATH_RSGALLERY2_SITE );

		$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'params.ini';
		$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'templateDetails.xml';
		$row	= TemplatesHelper::parseXMLTemplateFile($tBaseDir, $template);

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

		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::editTemplate($row, $params, $option, $ftp, $template);
	}
	
	function chooseTemplateCSS()
	{
		global $mainfram, $templateManager_path;

		// Initialize some variables
		$option 	= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');

		// Determine template CSS directory
		$dir = JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'css';

		// List template .css files
		jimport('joomla.filesystem.folder');
		$files = JFolder::files($dir, '\.css$', false, false);

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::chooseCSSFiles($template, $dir, $files, $option);
	}

	function chooseTemplateOverride()
	{
		global $mainfram, $templateManager_path;
		
		// Initialize some variables
		$option 	= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		
		// Determine template CSS directory
		$dir = JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'html';
		
		// List template .php files
		jimport('joomla.filesystem.folder');
		$files = JFolder::files($dir, '\.php$', false, false);
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		
		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::chooseOverrideFiles($template, $dir, $files, $option);
	}

	function editTemplateSource()
	{
		global $mainframe, $templateManager_path;

		// Initialize some variables
		$option		= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$filename	= 'index.php';
		$file		= JPATH_RSGALLERY2_SITE .DS.'templates'.DS.$template.DS.$filename ;
		
		// Read the source file
		jimport('joomla.filesystem.file');
		$content = JFile::read($file);

		if ($content !== false)
		{
			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

			$content = htmlspecialchars($content);
			require_once ($templateManager_path . 'admin.templates.html.php');
			TemplatesView::editFileSource($template, $filename, $content, $option, $ftp, "template");
		} else {
			$msg = JText::sprintf('Operation Failed Could not open', $file);
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', $msg);
		}
	}

	function editTemplateCSS()
	{
		global $mainframe, $templateManager_path;

		// Initialize some variables
		$option		= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$filename	= JRequest::getVar('filename', '', 'method', 'cmd');

		jimport('joomla.filesystem.file');

		if (JFile::getExt($filename) !== 'css') {
			$msg = JText::_('Wrong file type given, only CSS files can be edited.');
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=choose_css&id='.$template, $msg, 'error');
		}

		$content = JFile::read(JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'css'.DS.$filename);

		if ($content !== false)
		{
			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

			$content = htmlspecialchars($content);
			require_once ($templateManager_path . 'admin.templates.html.php');
			TemplatesView::editFileSource($template, $filename , $content, $option, $ftp, "css");
		}
		else
		{
			$msg = JText::sprintf('Operation Failed Could not open', $client->path.$filename);
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', $msg);
		}
	}

	function editTemplateOverride()
	{
		global $mainframe, $templateManager_path;
		
		// Initialize some variables
		$option		= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$filename	= JRequest::getVar('filename', '', 'method', 'cmd');
		
		jimport('joomla.filesystem.file');
		
		if (JFile::getExt($filename) !== 'php') {
			$msg = JText::_('Wrong file type given, only php files can be edited.');
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=choose_override&id='.$template, $msg, 'error');
		}
		
		$content = JFile::read(JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'html'.DS.$filename);
		
		if ($content !== false)
		{
			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
			
			$content = htmlspecialchars($content);
			require_once ($templateManager_path . 'admin.templates.html.php');
			TemplatesView::editFileSource($template, $filename, $content, $option, $ftp, "override");
		}
		else
		{
			$msg = JText::sprintf('Operation Failed Could not open', JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'html'.DS.$filename);
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', $msg);
		}
	}

	function editTemplateDisplay()
	{
		global $mainframe, $templateManager_path;

		// Initialize some variables
		$option		= JRequest::getCmd('option');
		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$filename	= 'display.class.php';
		$file		= JPATH_RSGALLERY2_SITE .DS.'templates'.DS.$template.DS.$filename ;
		
		// Read the source file
		jimport('joomla.filesystem.file');
		$content = JFile::read($file);

		if ($content !== false)
		{
			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

			$content = htmlspecialchars($content);
			require_once ($templateManager_path . 'admin.templates.html.php');
			TemplatesView::editFileSource($template, $filename, $content, $option, $ftp, "display");
		} else {
			$msg = JText::sprintf('Operation Failed Could not open', $file);
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', $msg);
		}
	}


	function saveTemplate()
	{
		global $mainframe;

		// Initialize some variables
		$db			 = & JFactory::getDBO();

		$template	= JRequest::getVar('id', '', 'method', 'cmd');
		$option		= JRequest::getVar('option', '', '', 'cmd');
		$params		= JRequest::getVar('params', array(), 'post', 'array');
		$default	= JRequest::getBool('default');

		if (!$template) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = JPATH_RSGALLERY2_SITE .DS.'templates'.DS.$template.DS.'params.ini';

		jimport('joomla.filesystem.file');
		if (JFile::exists($file) && count($params))
		{
			$txt = null;
			foreach ($params as $k => $v) {
				$txt .= "$k=$v\n";
			}

			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
				JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the template parameter file writable');
			}

			$return = JFile::write($file, $txt);

			// Try to make the params file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
				JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the template parameter file unwritable');
			}

			if (!$return) {
				$mainframe->redirect('index.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing.', $file));
			}
		}

		$task = JRequest::getCmd('task');
		if($task == 'apply') {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit&cid[]='.$template);
		} else {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager');
		}
	}
	
	function saveTemplateSource()
	{
		global $mainframe;
		
		// Initialize some variables
		$option			= JRequest::getCmd('option');
		$template		= JRequest::getVar('id', '', 'method', 'cmd');
		$filecontent	= JRequest::getVar('filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$template) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		if (!$filecontent) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = JPATH_RSGALLERY2_SITE .DS.'templates'.DS.$template.DS.'index.php';

		// Try to make the template file writeable
		if (!$ftp['enabled'] && !JPath::setPermissions($file, '0755')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the template file writable');
		}

		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);

		// Try to make the template file unwriteable
		if (!$ftp['enabled'] && !JPath::setPermissions($file, '0555')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the template file unwritable');
		}

		if ($return)
		{
			$task = JRequest::getCmd('task');
			switch($task)
			{
				case 'apply_file':
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit_source&id='.$template.'&filename=index.php&filetype=template', JText::_('Template source saved'));
					break;

				case 'save_file':
				default:
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit&cid[]='.$template, JText::_('Template source saved'));
					break;
			}
		}
		else {
			$mainframe->redirect('index.php?option='.$option, JText::_('Operation Failed').': '.JText::_('Failed to open file for writing.'));
		}
	}

	function saveTemplateCSS()
	{
		global $mainframe;

		// Initialize some variables
		$option			= JRequest::getCmd('option');
		$template		= JRequest::getVar('id', '', 'post', 'cmd');
		$filename		= JRequest::getVar('filename', '', 'post', 'cmd');
		$filecontent	= JRequest::getVar('filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$template) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		if (!$filecontent) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'css'.DS.$filename;

		// Try to make the css file writeable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the css file writable');
		}

		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);

		// Try to make the css file unwriteable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the css file unwritable');
		}

		if ($return)
		{
			$task = JRequest::getCmd('task');
			switch($task)
			{
				case 'apply_file':
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit_css&id='.$template.'&filename='.$filename.'&fileType=css',  JText::_('File Saved'));
					break;

				case 'save_file':
				default:
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=choose_css&id='.$template, JText::_('File Saved'));
					break;
			}
		}
		else {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&id='.$template.'&task=choose_css', JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing.', $file));
		}
	}
	
	function saveTemplateOverride()
	{
		global $mainframe;
		
		// Initialize some variables
		$option			= JRequest::getCmd('option');
		$template		= JRequest::getVar('id', '', 'post', 'cmd');
		$filename		= JRequest::getVar('filename', '', 'post', 'cmd');
		$filecontent	= JRequest::getVar('filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		if (!$template) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}
		
		if (!$filecontent) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}

		if (!$filename ) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No css file specified.'));
		}
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');
		
		$file = JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'html'.DS.$filename;
		
		// Try to make the css file writeable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the php file writable');
		}
		
		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);
		
		// Try to make the php file unwriteable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the php file unwritable');
		}
		
		if ($return)
		{
			$task = JRequest::getCmd('task');
			switch($task)
			{
				case 'apply_file':
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit_override&id='.$template.'&filename='.$filename.'&fileType=override',  JText::_('File Saved'));
					break;
				
				case 'save_file':
				default:
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=choose_override&id='.$template  , JText::_('File Saved'));
					break;
			}
		}
		else {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&id='.$template.'&task=choose_override', JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing.', $file));
		}
	}

	function saveTemplateDisplay()
	{
		global $mainframe;
		
		// Initialize some variables
		$option			= JRequest::getCmd('option');
		$template		= JRequest::getVar('id', '', 'method', 'cmd');
		$filecontent	= JRequest::getVar('filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		if (!$template) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}
		
		if (!$filecontent) {
			$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager', JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');
		
		$file = JPATH_RSGALLERY2_SITE .DS.'templates'.DS.$template.DS.'display.class.php';
		
		// Try to make the template file writeable
		if (!$ftp['enabled'] && !JPath::setPermissions($file, '0755')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the display file writable');
		}
		
		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);
		
		// Try to make the template file unwriteable
		if (!$ftp['enabled'] && !JPath::setPermissions($file, '0555')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the display file unwritable');
		}
		
		if ($return)
		{
			$task = JRequest::getCmd('task');
			switch($task)
			{
				case 'apply_file':
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit_display&id='.$template.'&filename=index.php&filetype=template', JText::_('Template display source saved'));
					break;
				
				case 'save_file':
				default:
					$mainframe->redirect('index.php?option='.$option.'&rsgOption=templateManager&task=edit&cid[]='.$template, JText::_('Template display source saved'));
					break;
			}
		}
		else {
			$mainframe->redirect('index.php?option='.$option, JText::_('Operation Failed').': '.JText::_('Failed to open file for writing.'));
		}
	}
	


	function chooseInstall()
	{
		global $templateManager_path;
		
		$option	= JRequest::getCmd('option');
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');
		
		require_once ($templateManager_path . 'admin.templates.html.php');
		TemplatesView::chooseInstall($option, $ftp);
	}

	function doInstall()
	{
		global $mainframe;
		
		switch(JRequest::getWord('installtype'))
		{
			case 'folder':
				$package = TemplatesController::_getPackageFromFolder();
				break;
			
			case 'upload':
				$package = TemplatesController::_getPackageFromUpload();
				break;
			
			case 'url':
				$package = TemplatesController::_getPackageFromUrl();
				break;
			
			default:
				$mainframe->redirect('index.php?option=com_rsgallery2&rsgOption=templateManager&task=chooseInstall', JText::_('Operation Failed').': '.JText::_('No Install Type Found'));
				
				return false;
				break;
		}
		
		// Was the package unpacked?
		if (!$package) {
			$mainframe->redirect('index.php?option=com_rsgallery2&rsgOption=templateManager&task=chooseInstall', JText::_('Operation Failed').': '.JText::_('Unable to find install package'));
			return false;
		}
		
	
		require_once(JPATH_RSGALLERY2_ADMIN .DS. 'options' . DS. 'templateManager'. DS. 'helpers' .DS. 'JInstallerRSGTemplate.php');
		// Get an installer instance
		$installer =& JInstaller::getInstance();
		
		// set custom Adapter for JInstaller
		$installer->SetAdapter("rsgTemplate", new JInstallerRSGTemplate($installer));

		// Install the package
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}
			
		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}
		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		$mainframe->redirect('index.php?option=com_rsgallery2&rsgOption=templateManager&task=chooseInstall', $msg);
	}
	
	function removeTemplate(){
		
		$cid		= JRequest::getVar('cid', array(), 'method', 'array');
		$cid		= array(JFilterInput::clean(@$cid[0], 'cmd'));
		$template	= $cid[0];
		$option		= JRequest::getCmd('option');
		
		if (!$cid[0]) {
			return JError::raiseWarning( 500, 'Template not specified' );
		}
		
		require_once(JPATH_RSGALLERY2_ADMIN .DS. 'options' . DS. 'templateManager'. DS. 'helpers' .DS. 'JInstallerRSGTemplate.php');
		// Get an installer instance
		$installer =& JInstaller::getInstance();
		
		// set custom Adapter for JInstaller
		$installer->SetAdapter("rsgTemplate", new JInstallerRSGTemplate($installer));
		
		if(!$installer->uninstall($template, -1)){
			// There was an error removing the package
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}
		
		$mainframe->redirect('index.php?option=com_rsgallery2&rsgOption=templateManager', $msg);
	}
	
	/**
	 * @param string The class name for the installer
	 */
	function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array' );
		
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}
		
		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}
		
		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
			return false;
		}
		
		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}
		
		// Build the appropriate paths
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];
		
		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		
		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);
		
		return $package;
	}
	
	/**
	 * Install an extension from a directory
	 *
	 * @static
	 * @return boolean True on success
	 * @since 1.0
	 */
	function _getPackageFromFolder()
	{
		// Get the path to the package to install
		$p_dir = JRequest::getString('install_directory');
		$p_dir = JPath::clean( $p_dir );
		
		// Did you give us a valid directory?
		if (!is_dir($p_dir)) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Please enter a package directory'));
			return false;
		}
		
		// Detect the package type
		$type = JInstallerHelper::detectType($p_dir);
		
		// Did you give us a valid package?
		if (!$type) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Path does not have a valid package'));
			return false;
		}
		
		$package['packagefile'] = null;
		$package['extractdir'] = null;
		$package['dir'] = $p_dir;
		$package['type'] = $type;
		
		return $package;
	}
	
	/**
	 * Install an extension from a URL
	 *
	 * @static
	 * @return boolean True on success
	 * @since 1.5
	 */
	function _getPackageFromUrl()
	{
		// Get a database connector
		$db = & JFactory::getDBO();
		
		// Get the URL of the package to install
		$url = JRequest::getString('install_url');
		
		// Did you give us a URL?
		if (!$url) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Please enter a URL'));
			return false;
		}
		
		// Download the package at the URL given
		$p_file = JInstallerHelper::downloadPackage($url);
		
		// Was the package downloaded?
		if (!$p_file) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Invalid URL'));
			return false;
		}
		
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path');
		
		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest.DS.$p_file);
		
		return $package;
	}
}
?>
