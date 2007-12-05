<?php
/**
* This file contains the non-presentation processing for the Admin section of RSGallery.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_JEXEC' ) or die( 'Access Denied.' );

// initialize RSG2 core functionality
require_once( JPATH_SITE.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );

// instantate user variables but don't show a frontend template
rsgInstance::instance( 'request', false );

// XML library
require_once( JPATH_SITE .'/includes/domit/xml_domit_lite_include.php' );

//Load Tooltips
JHTML::_('behavior.tooltip');

?>
<link href="<?php echo JURI_SITE; ?>/administrator/components/com_rsgallery2/admin.rsgallery2.css" rel="stylesheet" type="text/css" />
<?php

require_once( $mainframe->getPath( 'admin_html' ) );

global $opt, $catid, $uploadStep, $numberOfUploads, $e_id ;
$opt                = rsgInstance::getVar('opt', null );
$catid 				= rsgInstance::getInt('catid', null);
$uploadStep         = rsgInstance::getInt('uploadStep', 0 );
$numberOfUploads    = rsgInstance::getInt('numberOfUploads', 1 );
$e_id               = rsgInstance::getInt('e_id', 1 );

$cid    = rsgInstance::getInt('cid', array(0) );
$id     = rsgInstance::getInt('id', 0 );

$rsgOption = rsgInstance::getVar('rsgOption', null );

$my = JFactory::getUser();

/**
 * this is the new $rsgOption switch.  each option will have a switch for $task within it.
 */
switch( $rsgOption ) {
    case 'galleries':
        require_once( $rsgOptions_path . 'galleries.php' );
    	break;
    case 'images':
        require_once( $rsgOptions_path . 'images.php' );
    	break;
    case 'comments':
        require_once( $rsgOptions_path . 'comments.php' );
   		break;
    case 'config':
        require_once( $rsgOptions_path . 'config.php' );
    	break;
    case 'templateManager':
        require_once( $rsgOptions_path . 'templateManager'.DS.'admin.templates.php' );
		break;
	case 'template':
		require_once( $rsgOptions_path . 'templates.php' );
		break;
	case 'maintenance':
    	require_once( $rsgOptions_path . 'maintenance.php' );
    	break;
}

/**
 * admin pathway hack when $rsgOption is used.
 * this probably only works with Joomla <1.5
 */
if( $rsgOption != '' ){
    $option = '<a href="'.JURI_SITE
        . '/administrator/index2.php?option=com_rsgallery2">'
        . "RSGallery2</a> / ";
    if( $task == '' ){
        $option .= "$rsgOption";
    }
    else{
        $option .= '<a href="'.JURI_SITE
        . '/administrator/index2.php?option=com_rsgallery2&rsgOption=$rsgOption">'
        . "$rsgOption</a>";
    }
}

// only use the legacy task switch if rsgOption is not used.
// these tasks require admin or super admin privledges.
if( $rsgOption == '' && $my->gid > 23 )
switch ( rsgInstance::getVar('task', null) ){
//     special/debug tasks
    case 'purgeEverything':
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        purgeEverything();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
    case 'reallyUninstall':
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        reallyUninstall();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
/**    case "migration":
        HTML_RSGallery::RSGalleryHeader('dbrestore', 'Migration options');
        showMigration();
        HTML_RSGallery::RSGalleryFooter();
        break;*/
    case "install":
        HTML_RSGallery::RSGalleryHeader('install', _RSGALLERY_HEAD_MIGRATE);
        RSInstall();
        HTML_RSGallery::RSGalleryFooter();
        break;
}

// only use the legacy task switch if rsgOption is not used.
if( $rsgOption == '' )
switch ( rsgInstance::getVar('task', null ) ){
    // config tasks
    // this is just a kludge until all links and form vars to configuration functions have been updated to use $rsgOption = 'config';
    case 'config_dumpVars':
    case 'applyConfig':
    case 'saveConfig':
    case "showConfig":
    case 'config_rawEdit_apply':
    case 'config_rawEdit_save':
    case 'config_rawEdit':
		$rsgOption = 'config';
		require_once( $rsgOptions_path . 'config.php' );
    break;

    // template tasks
    // this is just a kludge until all links and form vars to template functions have been updated to use $rsgOption = 'templates';
    case 'templates':
    case 'upload_template':
    case 'remove':
    case 'default':
    case 'edit_css':
	case 'save_css':
	case 'edit_main':
	case 'edit_thumbs':
	case 'edit_display':
	case 'save_main':
	case 'save_thumbs':
	case 'save_display':
		$rsgOption = 'templates';
		require_once( $rsgOptions_path . 'templates.php' );
	break;

//     image tasks

    case "edit_image":
        HTML_RSGallery::RSGalleryHeader('edit', _RSGALLERY_HEAD_EDIT);
        editImageX($option, $cid[0]);
        HTML_RSGallery::RSGalleryFooter();
        break;

    case "uploadX":
        HTML_RSGallery::RSGalleryHeader('browser', _RSGALLERY_HEAD_UPLOAD);
        showUpload();
        HTML_RSGallery::RSGalleryFooter();
        break;

    case "batchuploadX":
        HTML_RSGallery::RSGalleryHeader('', _RSGALLERY_HEAD_UPLOAD_ZIP);
        batch_upload($option, $task);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "save_batchuploadX":
        save_batchupload();
        break;
    /*
    case "view_images":
        HTML_RSGallery::RSGalleryHeader();
        viewImagesX($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "save_image":
        saveImageX($option, $id);
        break;
    case "move_image":
        moveImageX($option, $cid);
        break;
    case "delete_image":
        HTML_RSGallery::RSGalleryHeader();
        deleteImageX( $cid, $option );
        viewImagesX($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
	*/


    //image and category tasks
    case "categories_orderup":
    case "images_orderup":
        orderRSGallery( $cid[0], -1, $option, $task );
        break;
    case "categories_orderdown":
    case "images_orderdown":
        orderRSGallery( $cid[0], 1, $option, $task );
        break;

//  special/debug tasks
    case 'viewChangelog':
        HTML_RSGallery::RSGalleryHeader('viewChangelog', _RSGALLERY_HEAD_LOG);
        viewChangelog();
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "controlPanel":
    default:
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
}

/**
* @param string The name of the php (temporary) uploaded file
* @param string The name of the file to put in the temp directory
* @param string The message to return
*/
function uploadFileX( $filename, $userfile_name, $msg ) {
	
	$baseDir = JPATH_SITE . '/media' ;

	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
				if (JFTP::chmod( $baseDir . $userfile_name )) {
					return true;
				} else {
					$msg = _RSGALLERY_ERMSG_FAILD_CHMOD;
				}
			} else {
				$msg = _RSGALLERY_ERMSG_FAILD_MOVE_MEDIA;
			}
		} else {
			$msg = _RSGALLERY_ERMSG_MEDIA_NOT_WRITE;
		}
	} else {
		$msg = _RSGALLERY_ERMSG_MEDIA_NOT_EXIST;
	}
	return false;
}

function viewChangelog(){
    echo '<pre>';
    readfile( JPATH_RSGALLERY2_ADMIN.'/changelog.php' );
    echo '</pre>';
}

/**
 * deletes all pictures, thumbs and their database entries. It leaves category information in DB intact.
 * this is a quick n dirty function for development, it shouldn't be available for regular users.
 */
function purgeEverything(){
    global $rsgConfig;
    
    $fullPath_thumb = JPATH_ROOT.$rsgConfig->get('imgPath_thumb') . '/';
    $fullPath_display = JPATH_ROOT.$rsgConfig->get('imgPath_display') . '/';
    $fullPath_original = JPATH_ROOT.$rsgConfig->get('imgPath_original') . '/';

    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_files', _RSGALLERY_PURGE_IMG );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_galleries', _RSGALLERY_PURGE_GAL );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_config', _RSGALLERY_PURGE_CONFIG );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_comments', _RSGALLERY_PURGE_COMMENTS );

    // remove thumbnails
    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_PURGE_THUMB );
    foreach ( glob( $fullPath_thumb.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    // remove display imgs
    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_PURGE_DISPLAY );
    foreach ( glob( $fullPath_display.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    // remove display imgs
    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_PURGE_ORIGINAL );
    foreach ( glob( $fullPath_original.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_PURGE_PURGED, true );
}

/**
 * drops all RSG2 tables, deletes image directory structure
 * use before uninstalling to REALLY uninstall
 * @todo This is a quick hack.  make it work on all OS and with non default directories.
 */
function reallyUninstall(){
    
    
    passthru( "rm -r ".JPATH_SITE."/images/rsgallery");
    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_REAL_UNINST_DIR );

    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_acl', _RSGALLERY_REAL_UNINST_DROP_GAL );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_files', _RSGALLERY_REAL_UNINST_DROP_FILES );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_cats', _RSGALLERY_REAL_UNINST_DROP_GAL );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_galleries', _RSGALLERY_REAL_UNINST_DROP_GAL );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_config', _RSGALLERY_REAL_UNINST_DROP_CONF );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_comments', _RSGALLERY_REAL_UNINST_DROP_COM );

    HTML_RSGALLERY::printAdminMsg( _RSGALLERY_REAL_UNINST_DONE );
}

/**
 * runs a sql query, displays admin message on success or error on error
 * @param String sql query
 * @param String message to display on success
 * @return boolean value indicating success
 */
function processAdminSqlQueryVerbosely( $query, $successMsg ){
    $database =& JFactory::getDBO();
    
    $database->setQuery( $query );
    $database->query();
    if($database->getErrorMsg()){
            HTML_RSGALLERY::printAdminMsg( $database->getErrorMsg(), true );
            return false;
    }
    else{
        HTML_RSGALLERY::printAdminMsg( $successMsg );
        return true;
    }
}

function save_batchuploadX() {
    global $database, $mainframe, $rsgConfig;
    
    //Try to bypass max_execution_time as set in php.ini
    set_time_limit(0);
    
    $FTP_path = $rsgConfig->get('ftp_path');

    $teller 	= rsgInstance::getInt('teller'  , null);
    $delete 	= rsgInstance::getVar('delete'  , null);
    $filename 	= rsgInstance::getVar('filename'  , null);
    $ptitle 	= rsgInstance::getVar('ptitle'  , null);
    $descr 		= rsgInstance::getVar('descr'  , array(0));
	$extractdir = rsgInstance::getVar('extractdir'  , null);
	
    //Check if all categories are chosen
    if (isset($_REQUEST['category']))
        $category = rsgInstance::getVar('category'  , null);
    else
        $category = array(0);

    if ( in_array("0",$category) ) {
        $mainframe->redirect("index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_ALERT_NOCATSELECTED);
	}

     for($i=0;$i<$teller;$i++) {
        //If image is marked for deletion, delete and continue with next iteration
        if (isset($delete[$i]) AND ($delete[$i] == 'true')) {
            //Delete file from server
            unlink(JPATH_ROOT.DS."media".DS.$extractdir.DS.$filename[$i]);
            continue;
        } else {
            //Setting variables for importImage()
            $imgTmpName = JPATH_ROOT.DS."media".DS.$extractdir.DS.$filename[$i];
            $imgName 	= $filename[$i];
            $imgCat	 	= $category[$i];
            $imgTitle 	= $ptitle[$i];
            $imgDesc 	= $descr[$i];
            
            //Import image
            $e = imgUtils::importImage($imgTmpName, $imgName, $imgCat, $imgTitle, $imgDesc);
            
            //Check for errors
            if ( $e !== true ) {
                $errors[] = $e;
            }
        }
    }
    //Clean up mediadir
    fileHandler::cleanMediaDir( $extractdir );
    
    // Error handling
    if (isset($errors )) {
        if ( count( $errors ) == 0) {
            echo _RSGALLERY_ALERT_UPLOADOK;
        } else {
            foreach( $errors as $err ) {
                echo $err->toString();
            }
        }
    } else {
        //Everything went smoothly, back to Control Panel
        $mainframe->redirect("index2.php?option=com_rsgallery2", _RSGALLERY_ALERT_UPLOADOK);
    }
}

function cancelGallery($option) {
    $mainframe->redirect("index2.php?option=$option");
}

/**
 * This function is called when you select batchupload from the backend. It
 * detects whether you choose ZIP or FTP and acts accordingly.
 * When you choose ZIP it unzips the file you upload to "/media" for further
 * handling, if you choose FTP it reads the files from the directory you uploaded
 * the files to and copies them to "/media".(this dir must be on the local server).
 * @todo Better error trapping
 * @todo Check FTP handling bit
 */
 
function batch_uploadX($option) {
	global $database, $mainframe, $rsgConfig;
	$FTP_path = $rsgConfig->get('ftp_path');
	
	//Retrieve data from submit form
	$batchmethod 	= rsgInstance::getVar('batchmethod', null);
	$uploaded 		= rsgInstance::getVar('uploaded', null);
	$selcat 		= rsgInstance::getInt('selcat', null);
	$zip_file 		= rsgInstance::getVar('zip_file', null, 'FILES'); 
	$ftppath 		= rsgInstance::getVar('ftppath', null);
	$xcat 			= rsgInstance::getInt('xcat', null);
	
	//Check if a gallery exists, if not link to gallery creation
	$database->setQuery( "SELECT id FROM #__rsgallery2_galleries" );
	$database->query();
	if( $database->getNumRows()==0 ){
		HTML_RSGALLERY::requestCatCreation( );
		return;
	}
	
	//New instance of fileHandler
	$uploadfile = new fileHandler();
	
	if (isset($uploaded)) {
		if ($batchmethod == "zip") {
			//Check if file is really a ZIP-file
			if (!eregi( '.zip$', $zip_file['name'] )) {
				$mainframe->redirect( "index2.php?option=com_rsgallery2&task=batchupload", $zip_file['name']._RSGALLERY_BACTCH_NOT_VALID_ZIP);
			} else {
				//Valid ZIP-file, continue
				if ($uploadfile->checkSize($zip_file) == 1) {
					$ziplist = $uploadfile->handleZIP($zip_file);
				} else {
					//Error message
					$mainframe->redirect( "index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_ZIP_TO_BIG);
				}
			}
		} else {
			$ziplist = $uploadfile->handleFTP($ftppath);
		}
		HTML_RSGALLERY::batch_upload_2($ziplist, $uploadfile->extractDir);
	} else {
		HTML_RSGALLERY::batch_upload($option);
	}
}//End function
?>
