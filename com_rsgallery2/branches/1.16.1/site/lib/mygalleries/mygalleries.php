<?php
/**
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/


defined('_JEXEC') or die('Restricted access');

global $rsgConfig, $mainframe;
$my = JFactory::getUser();

//Check if My Galleries is enabled in config, if not .............. 
if ( !$rsgConfig->get('show_mygalleries') ) 
	die(JText::_('Unauthorized access attempt to My Galleries!'));
//Not logged in, back to main page
if ( $my->id == 0)	
	$mainframe->redirect("index.php?option=com_rsgallery2", 
	                     JText::_('User galleries are disabled by administrator') );

$css = "<link rel=\"stylesheet\" href=\"".JURI::root()."/components/com_rsgallery2/lib/mygalleries/mygalleries.css\" type=\"text/css\" />";
$mainframe->addCustomHeadTag($css);
$css = "<link rel=\"stylesheet\" href=\"".JURI::root()."/components/com_rsgallery2/templates/".$rsgConfig->template."/css/template.css\" type=\"text/css\" />";
$mainframe->addCustomHeadTag($css);

require_once( dirname(__FILE__) . DS . 'controller.php' );

$controller = new MyGalleriesController( array(
			'default_task' => 'default',
			'base_path' => JPATH_RSGALLERY2_LIBS . DS . 'mygalleries',
			'model_path' => JPath_RSGaller2_MOdels
			));

$controller->execute( JRequest::getCmd('task') );
$controller->redirect();

?>
