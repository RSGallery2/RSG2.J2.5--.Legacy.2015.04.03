<?php
/**
 * RSGallery2 template manager toolbar helper class
 * Derived from Joomla 1.5 mod_template
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');



global $templateManager_path;
require_once ($templateManager_path . 'toolbar.templates.html.php');


switch ($task)
{
	case 'view'   :
	case 'preview':
		TOOLBAR_templates::_VIEW();
		break;

	case 'edit_source':
	case 'edit_display':
		TOOLBAR_templates::_EDIT_SOURCE();
		break;

	case 'edit':
		TOOLBAR_templates::_EDIT();
		break;

	case 'choose_css':
		TOOLBAR_templates::_CHOOSE_CSS();
		break;

	case 'edit_css':
		TOOLBAR_templates::_EDIT_CSS();
		break;
		
	case 'choose_override':
		TOOLBAR_templates::_CHOOSE_OVERRIDE();
		break;
	
	case 'edit_override':
		TOOLBAR_templates::_EDIT_OVERRIDE();
		break;

	case 'doInstall':
	case 'showInstall':
		TOOLBAR_templates::_INSTALL();
		break;
	default:
		TOOLBAR_templates::_DEFAULT();
		break;
}
?>