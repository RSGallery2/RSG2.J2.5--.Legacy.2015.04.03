<?php
/**
 * RSGallery2 template helper class
 * Derived from Joomla 1.5 mod_template
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');


/*
 * Make sure the user is authorized to view this page
 */
//$user = & JFactory::getUser();
//if (!$user->authorize('com_templates', 'manage')) {
//	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
//}
global $rsgOptions_path, $templateManager_path;

$rsgOptions_path = JPATH_RSGALLERY2_ADMIN . DS .'options' .DS;
$templateManager_path = $rsgOptions_path . 'templateManager' .DS;
$task = JRequest::getCmd('task');
$fileType = JRequest::getCmd('fileType');

// Import file dependencies
require_once ($templateManager_path  . 'helpers'.DS.'template.php');
require_once ($templateManager_path  . 'controller.php');
require_once ($templateManager_path  . 'toolbar.templates.php');

if (!class_exists("JToolBarHelper")) {
	require_once(J15B_PATH .DS.'includes'.DS.'adminToolbar.php');
}

// set sub menu item
switch($task)
{
	case 'showInstall' :
	case 'doInstall' :
	{
		JSubMenuHelper::addEntry(JText::_('Manage'), 'index.php?option=com_rsgallery2&rsgOption=templateManager');
		JSubMenuHelper::addEntry(JText::_('Install'), 'index.php?option=com_rsgallery2&rsgOption=templateManager&task=showInstall',true);
		break;
	}
	default:{
		JSubMenuHelper::addEntry(JText::_('Manage'), 'index.php?option=com_rsgallery2&rsgOption=templateManager', true);
		JSubMenuHelper::addEntry(JText::_('Install'), 'index.php?option=com_rsgallery2&rsgOption=templateManager&task=showInstall');
		break;
	}
}

// main control switch
switch ($task)
{
	case 'edit' :
		TemplatesController::editTemplate();
		break;

	case 'save'  :
	case 'apply' :
		TemplatesController::saveTemplate();
		break;

	case 'edit_source' :
		TemplatesController::editTemplateSource();
		break;

	case 'edit_display' :
		TemplatesController::editTemplateDisplay();
		break;

	case 'choose_override' :
		TemplatesController::chooseTemplateOverride();
		break;

	case 'edit_override' :
		TemplatesController::editTemplateOverride();
		break;
		
	case 'choose_css' :
		TemplatesController::chooseTemplateCSS();
		break;

	case 'edit_css' :
		TemplatesController::editTemplateCSS();
		break;

	case 'save_file'  :
	case 'apply_file' :
		
		switch($fileType)
		{
			case "template":
				TemplatesController::saveTemplateSource();
				break;
			case "css":
				TemplatesController::saveTemplateCSS();
				break;
			case "override":
				TemplatesController::saveTemplateOverride();
				break;
			case "display":
				TemplatesController::saveTemplateDisplay();
			default:
				JError::raiseWarning(500,"Wrong file type to save/apply :".$fileType, $fileType );
				TemplatesController::viewTemplates();
				break;
		}
		break;
	

	case 'publish' :
	case 'default' :
		TemplatesController::publishTemplate();
		break;

	case 'preview' :
		TemplatesController::previewTemplate();
		break;
	
	case 'showInstall' :
		TemplatesController::chooseInstall();
		break;

	case 'doInstall' :
		TemplatesController::doInstall();
		break;

	case 'remove' :
		TemplatesController::removeTemplate();
		break;
		
	case "cancel":		
	default :
		TemplatesController::viewTemplates();
		break;
}

?>