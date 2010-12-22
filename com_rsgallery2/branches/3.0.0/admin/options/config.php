<?php
/**
* Galleries option for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Restricted Access' );

require_once( $rsgOptions_path . 'config.html.php' );

// anyone can use these config functions
switch( $task ){
    case 'cancel';
    	cancelConfig($option);
		break;
    case 'config_dumpVars':
        //HTML_RSGallery::RSGalleryHeader('viewChangelog', JText::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'));
        config_dumpVars();
        HTML_RSGallery::RSGalleryFooter();
    break;
}

$user = &JFactory::getUser();
// these config functions require an admin check
if (1 )//MK// [change] [only admin + may see this]  $user->get('gid') > 23
switch( $task ){
    case 'applyConfig':
        //HTML_RSGallery::RSGalleryHeader('config', JText::_('COM_RSGALLERY2_CONFIGURATION'));
        saveConfig();
        showConfig($option);
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'saveConfig':
        //HTML_RSGallery::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_CONTROL_PANEL'));
        saveConfig();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
    break;
    case "showConfig":
        //HTML_RSGallery::RSGalleryHeader('config', JText::_('COM_RSGALLERY2_CONFIGURATION'));
        showConfig();
        HTML_RSGallery::RSGalleryFooter();
    break;

    case 'config_rawEdit_apply':
        //HTML_RSGallery::RSGalleryHeader('config_rawEdit', JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'));
        saveConfig();
        config_rawEdit( );
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'config_rawEdit_save':
        //HTML_RSGallery::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_CONTROL_PANEL'));
        saveConfig();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'config_rawEdit':
        //HTML_RSGallery::RSGalleryHeader('config_rawEdit', JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'));
        config_rawEdit( );
        HTML_RSGallery::RSGalleryFooter();
    break;
}
else
    HTML_RSGALLERY::printAdminMsg( 'Access denied OR config feature does not exist.' );

function config_dumpVars(){
    global $rsgConfig;

    $vars = get_object_vars( $rsgConfig );

    echo '<pre>';
    print_r( $rsgConfig );
    echo '</pre>';
}

function config_rawEdit( $save=false ){
    if( $save ){
        // save
    }

    html_rsg2_config::config_rawEdit();
}

/**
 * @todo if thumbname size has changed, advise user to regenerate thumbs
 */
function saveConfig(){
    global $rsgConfig;
    $rsgConfig = new rsgConfig();
    
    if( $rsgConfig->saveConfig( $_REQUEST )){
            HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_CONFIGURATION_SAVED'));

            // save successful, try creating some image directories if we were asked to
            if( JRequest::getVar( 'createImgDirs' ))
                HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_CREATING_IMAGE_DIRECTORIES_NOT_IMPLEMENTED_YET'), true);
            
    }else{
            HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_ERROR_SAVING_CONFIGURATION'));
    }
    
}

function showConfig(){
    global $rsgConfig;

    $langs      = array();
    //$imageLib   = array();
    $lists      = array();

    // PRE-PROCESS SOME LISTS

    // -- Languages --
	/*
    if ($handle = opendir( JPATH_RSGALLERY2_ADMIN.'/language/' )) {
        $i=0;
        while (false !== ($file = readdir( $handle ))) {
            if (!strcasecmp(substr($file,-4),".php") && $file <> "." && $file <> ".." && strcasecmp(substr($file,-11),".ignore.php")) {
                //$langs[] = mosHTML::makeOption( substr($file,0,-4) );
            }
        }
    }
    

    // sort list of languages
    sort( $langs );
    reset( $langs );
	*/
    /**
     * detect available graphics libraries
     * @todo call imgUtils graphics lib detection when it is built
    */
    $graphicsLib = array();

    $result = GD2::detect();
	if( $result )
		$graphicsLib[] = JHTML::_("select.option", 'gd2', $result );
	else
		$graphicsLib[] = JHTML::_("select.option", 'gd2', JText::_('COM_RSGALLERY2_GD2_NOT_DETECTED') );

    $result = ImageMagick::detect();
    if( $result )
        $graphicsLib[] = JHTML::_("select.option", 'imagemagick', $result );
    else
        $graphicsLib[] = JHTML::_("select.option", 'imagemagick', JText::_('COM_RSGALLERY2_IMAGEMAGICK_NOT_DETECTED') );

    $result = Netpbm::detect();
    if( $result )
        $graphicsLib[] = JHTML::_("select.option", 'netpbm', $result );
    else
        $graphicsLib[] = JHTML::_("select.option", 'netpbm', JText::_('COM_RSGALLERY2_NETPBM_NOT_DETECTED') );
    
    
    $lists['graphicsLib'] = JHTML::_("select.genericlist",$graphicsLib, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );

    html_rsg2_config::showconfig( $lists );
}
function cancelConfig( $option ) {
	$mainframe =& JFactory::getApplication();
	$mainframe->redirect("index.php?option=$option");
}
?>