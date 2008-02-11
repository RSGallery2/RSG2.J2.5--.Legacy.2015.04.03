<?php
/**
 * RSGallery2 template helper class
 * Derived from Joomla 1.5 TemplateHelper
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 *
 */
class TemplatesHelper
{
	function isTemplateDefault($template)
	{
		global $rsgConfig;
		$currentTemplate = $rsgConfig->get('template');
		return $currentTemplate == $template ? 1 : 0;
	}


	function parseXMLTemplateFiles($templateBaseDir)
	{
		// Read the template folder to find templates
		jimport('joomla.filesystem.folder');
		$templateDirs = JFolder::folders($templateBaseDir);

		$rows = array();

		// Check that the directory contains an xml file
		foreach ($templateDirs as $templateDir)
		{
			if(!$data = TemplatesHelper::parseXMLTemplateFile($templateBaseDir, $templateDir)){
				continue;
			} else {
				$rows[] = $data;
			}
		}

		return $rows;
	}

	function parseXMLTemplateFile($templateBaseDir, $templateDir)
	{
		// Check of the xml file exists
		if(!is_file($templateBaseDir.DS.$templateDir.DS.'templateDetails.xml')) {
			return false;
		}
		$xml = JApplicationHelper::parseXMLInstallFile($templateBaseDir.DS.$templateDir.DS.'templateDetails.xml');
		
		if ($xml['type'] == 'template') {
			JError::raiseWarning(0,"The type attribute in ".$templateBaseDir.DS.$templateDir.DS."templateDetails.xml has a not supported value.<br/>Please change it to 'rsgTemplate'.","");
		}
		
		if ($xml['type'] != 'rsgTemplate') {
			return false;
		}

		$data = new StdClass();
		$data->directory = $templateDir;

		foreach($xml as $key => $value) {
			$data->$key = $value;
		}

		$data->checked_out = 0;
		$data->mosname = JString::strtolower(str_replace(' ', '_', $data->name));

		return $data;
	}

}
?>
