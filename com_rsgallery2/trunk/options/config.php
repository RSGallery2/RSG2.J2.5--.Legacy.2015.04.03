<?php
/**
* Galleries option for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

require_once( $rsgOptions_path . 'config.html.php' );

// anyone can use these config functions
switch( $task ){
    case '';
    case 'config_dumpVars':
        HTML_RSGallery::RSGalleryHeader('viewChangelog', _RSGALLERY_HEAD_CONF_VARIA);
        config_dumpVars();
        HTML_RSGallery::RSGalleryFooter();
    break;
}

// these config functions require an admin check
if ( $my->gid > 23 )
switch( $task ){
    case 'applyConfig':
        HTML_RSGallery::RSGalleryHeader('config', _RSGALLERY_HEAD_CONFIG);
        saveConfig();
        showConfig($option);
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'saveConfig':
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        saveConfig();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
    break;
    case "showConfig":
        HTML_RSGallery::RSGalleryHeader('config', _RSGALLERY_HEAD_CONFIG);
        showConfig();
        HTML_RSGallery::RSGalleryFooter();
    break;

    case 'config_rawEdit_apply':
        HTML_RSGallery::RSGalleryHeader('config_rawEdit', _RSGALLERY_HEAD_CONF_RAW_EDIT);
        saveConfig();
        config_rawEdit( );
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'config_rawEdit_save':
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        saveConfig();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
    break;
    case 'config_rawEdit':
        HTML_RSGallery::RSGalleryHeader('config_rawEdit', _RSGALLERY_HEAD_CONF_RAW_EDIT);
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
            HTML_RSGALLERY::printAdminMsg(_RSGALLERY_CONF_SAVED);

            // save successful, try creating some image directories if we were asked to
            if( rsgInstance::getVar( 'createImgDirs' ))
                HTML_RSGALLERY::printAdminMsg(_RSGALLERY_CONF_CREATE_DIR, true);
            
    }else{
            HTML_RSGALLERY::printAdminMsg(_RSGALLERY_CONF_SAVE_ERROR);
    }
}

function showConfig(){
    global $rsgConfig;

    $langs      = array();
    $imageLib   = array();
    $lists      = array();

    // PRE-PROCESS SOME LISTS

    // -- Languages --

    if ($handle = opendir( JPATH_RSGALLERY2_ADMIN.'/language/' )) {
        $i=0;
        while (false !== ($file = readdir( $handle ))) {
            if (!strcasecmp(substr($file,-4),".php") && $file <> "." && $file <> ".." && strcasecmp(substr($file,-11),".ignore.php")) {
                $langs[] = mosHTML::makeOption( substr($file,0,-4) );
            }
        }
    }

    // sort list of languages
    sort( $langs );
    reset( $langs );

    /**
     * detect available graphics libraries
     * @todo call imgUtils graphics lib detection when it is built
    */
    $graphicsLib = array();

    $result = GD2::detect();
    if( $result )
        $graphicsLib[] = mosHTML::makeOption( 'gd2', $result );
    else
        $graphicsLib[] = mosHTML::makeOption( 'gd2', _RSGALLERY_CONF_NOGD2 );

    $result = ImageMagick::detect();
    if( $result )
        $graphicsLib[] = mosHTML::makeOption( 'imagemagick', $result );
    else
        $graphicsLib[] = mosHTML::makeOption( 'imagemagick', _RSAGALLERY_CONF_NOIMGMAGICK );

    $result = Netpbm::detect();
    if( $result )
        $graphicsLib[] = mosHTML::makeOption( 'netpbm', $result );
    else
        $graphicsLib[] = mosHTML::makeOption( 'netpbm', _RSAGALLERY_CONF_NONETPBM );
    
    
    $lists['graphicsLib'] = mosHTML::selectList( $graphicsLib, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );

    html_rsg2_config::showconfig( $lists );
}

?>