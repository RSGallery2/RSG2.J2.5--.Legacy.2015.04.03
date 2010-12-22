<?php
/**
* This file contains the non-presentation processing for the Admin section of RSGallery.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2010 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_JEXEC' ) or die( 'Access Denied.' );

// initialize RSG2 core functionality
require_once( JPATH_SITE.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );

// instantate user variables but don't show a frontend template
rsgInstance::instance( 'request', false );

// XML library
//MK// [removed for Joomla 1.6] require_once( JPATH_SITE .'/includes/domit/xml_domit_lite_include.php' );

//Load Tooltips
JHTML::_('behavior.tooltip');

//MK// [change] [include for J!1.6 ACL]	
require_once JPATH_COMPONENT.'/helpers/rsgallery2.php';

?>
<link href="<?php echo JURI_SITE; ?>/administrator/components/com_rsgallery2/admin.rsgallery2.css" rel="stylesheet" type="text/css" />
<?php

require_once( JApplicationHelper::getPath('admin_html') );

global $opt, $catid, $uploadStep, $numberOfUploads, $e_id ;
$opt                = JRequest::getVar('opt', null );
$catid 				= JRequest::getInt('catid', null);
$uploadStep         = JRequest::getInt('uploadStep', 0 );
$numberOfUploads    = JRequest::getInt('numberOfUploads', 1 );
$e_id               = JRequest::getInt('e_id', 1 );

$cid    = JRequest::getInt('cid', array(0) );
$id     = JRequest::getInt('id', 0 );

$rsgOption = JRequest::getVar('rsgOption', null );

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
//	case 'template':
//		require_once( $rsgOptions_path . 'templates.php' );
//		break;
	case 'installer':
		require_once( $rsgOptions_path . 'installer.php' );
		break;
	case 'maintenance':
    	require_once( $rsgOptions_path . 'maintenance.php' );
    	break;
}

// only use the legacy task switch if rsgOption is not used. [MK not truely legacy but still used!]
// these tasks require admin or super admin privledges.
//MK// [change] [J16 ACL]	original line "if( $rsgOption == '' && $my->gid > 23 )" where $my->gid > 23 indicates that the user group that the user belongs to is 23 Administrator or bigger, e.g. 24 Super administrator in J!.5. ACL works differently in J1.6. so removed this check for priviliges for now.
if( $rsgOption == '' )	//MK// [change] [J16 ACL] if( $rsgOption == '' && $my->gid > 23 )
switch ( JRequest::getVar('task', null) ){
//     special/debug tasks
    case 'purgeEverything':
        /* Replace all headers with JToolBarHelper::title() in toolbar.rsgallery2.html.php
        HTML_RSGallery::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_CONTROL_PANEL'));
        */
        purgeEverything();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
    case 'reallyUninstall':
        //HTML_RSGallery::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_CONTROL_PANEL'));
        reallyUninstall();
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
}

// only use the legacy task switch if rsgOption is not used.
if( $rsgOption == '' )
switch ( JRequest::getVar('task', null ) ){
    // config tasks
    // this is just a kludge until all links and form vars to configuration functions have been updated to use $rsgOption = 'config';
    /*
    case 'applyConfig':
    case 'saveConfig':
    case "showConfig":
    */
    case 'config_dumpVars':
    case 'config_rawEdit_apply':
    case 'config_rawEdit_save':
    case 'config_rawEdit':
		$rsgOption = 'config';
		require_once( $rsgOptions_path . 'config.php' );
    break;

//     image tasks

    case "edit_image":
        HTML_RSGallery::RSGalleryHeader('edit', JText::_('COM_RSGALLERY2_EDIT'));
        editImageX($option, $cid[0]);
        HTML_RSGallery::RSGalleryFooter();
        break;

    case "uploadX":
        HTML_RSGallery::RSGalleryHeader('browser', JText::_('COM_RSGALLERY2_UPLOAD'));
        showUpload();
        HTML_RSGallery::RSGalleryFooter();
        break;

    case "batchuploadX":
        HTML_RSGallery::RSGalleryHeader('', JText::_('COM_RSGALLERY2_UPLOAD_ZIP-FILE'));
        batch_upload($option, $task);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "save_batchuploadX":
        save_batchupload();
        break;

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
        HTML_RSGallery::RSGalleryHeader('viewChangelog', JText::_('COM_RSGALLERY2_CHANGELOG'));
        viewChangelog();
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "controlPanel":
    default:
        //HTML_RSGallery::RSGalleryHeader('cpanel', JText::_('COM_RSGALLERY2_CONTROL_PANEL'));
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
}

/**
* @param string The name of the php (temporary) uploaded file
* @param string The name of the file to put in the temp directory
* @param string The message to return
*/
function uploadFile( $filename, $userfile_name, $msg ) {
	
	$baseDir = JPATH_SITE . '/media' ;

	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
				if (JFTP::chmod( $baseDir . $userfile_name )) {
					return true;
				} else {
					$msg = JText::_('COM_RSGALLERY2_FAILED_TO_CHANGE_THE_PERMISSIONS_OF_THE_UPLOADED_FILE');
				}
			} else {
				$msg = JText::_('COM_RSGALLERY2_FAILED_TO_MOVE_UPLOADED_FILE_TO_MEDIA_DIRECTORY');
			}
		} else {
			$msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_IS_NOT_WRITABLE');
		}
	} else {
		$msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_DOES_NOT_EXIST');
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

    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_files', JText::_('COM_RSGALLERY2_PURGED_IMAGE_ENTRIES_FROM_DATABASE') );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_galleries', JText::_('COM_RSGALLERY2_PURGED_GALLERIES_FROM_DATABASE') );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_config', JText::_('COM_RSGALLERY2_PURGED_CONFIG_FROM_DATABASE') );
    processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_comments', JText::_('COM_RSGALLERY2_PURGED_COMMENTS_FROM_DATABASE') );
	processAdminSqlQueryVerbosely( 'DELETE FROM #__rsgallery2_acl', JText::_('COM_RSGALLERY2_ACCESS_CONTROL_DATA_DELETED' ));
    
    // remove thumbnails
    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REMOVING_THUMB_IMAGES') );
    foreach ( glob( $fullPath_thumb.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    // remove display imgs
    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES') );
    foreach ( glob( $fullPath_display.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    // remove display imgs
    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES') );
    foreach ( glob( $fullPath_original.'*' ) as $filename ) {
        if( is_file( $filename )) unlink( $filename );
    }
    
    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_PURGED'), true );
}

/**
 * drops all RSG2 tables, deletes image directory structure
 * use before uninstalling to REALLY uninstall
 * @todo This is a quick hack.  make it work on all OS and with non default directories.
 */
function reallyUninstall(){
    
    
    passthru( "rm -r ".JPATH_SITE."/images/rsgallery");
    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_USED_RM_-R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY') );

    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_acl', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES') );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_files', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_FILES') );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_cats', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES') );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_galleries', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES') );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_config', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_CONFIG') );
    processAdminSqlQueryVerbosely( 'DROP TABLE IF EXISTS #__rsgallery2_comments', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_COMMENTS') );

    HTML_RSGALLERY::printAdminMsg( JText::_('COM_RSGALLERY2_REAL_UNINST_DONE') );
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

    $teller 	= JRequest::getInt('teller'  , null);
    $delete 	= JRequest::getVar('delete'  , null);
    $filename 	= JRequest::getVar('filename'  , null);
    $ptitle 	= JRequest::getVar('ptitle'  , null);
    $descr 		= JRequest::getVar('descr'  , array(0));
	$extractdir = JRequest::getVar('extractdir'  , null);
	
    //Check if all categories are chosen
    if (isset($_REQUEST['category']))
        $category = JRequest::getVar('category'  , null);
    else
        $category = array(0);

    if ( in_array("0",$category) ) {
        $mainframe->redirect("index.php?option=com_rsgallery2&task=batchupload", JText::_('COM_RSGALLERY2_ALERT_NOCATSELECTED'));
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
            echo JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
        } else {
            foreach( $errors as $err ) {
                echo $err->toString();
            }
        }
    } else {
        //Everything went smoothly, back to Control Panel
        $mainframe->redirect("index.php?option=com_rsgallery2", JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY'));
    }
}

function cancelGallery($option) {
    $mainframe->redirect("index.php?option=$option");
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
/* //MK// [not used check]	
function batch_uploadX($option) {
	global $mainframe, $rsgConfig;
	$database = JFactory::getDBO();
	$FTP_path = $rsgConfig->get('ftp_path');
	
	//Retrieve data from submit form
	$batchmethod 	= JRequest::getVar('batchmethod', null);
	$uploaded 		= JRequest::getVar('uploaded', null);
	$selcat 		= JRequest::getInt('selcat', null);
	$zip_file 		= JRequest::getVar('zip_file', null, 'FILES'); 
	$ftppath 		= JRequest::getVar('ftppath', null);
	$xcat 			= JRequest::getInt('xcat', null);
	
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
				$mainframe->redirect( "index.php?option=com_rsgallery2&task=batchupload", $zip_file['name'].' '.JText::_('COM_RSGALLERY2_NO_VALID_ARCHIVE_ONLY_ZIP_ALLOWED'));
			} else {
				//Valid ZIP-file, continue
				if ($uploadfile->checkSize($zip_file) == 1) {
					$ziplist = $uploadfile->handleZIP($zip_file);
				} else {
					//Error message
					$mainframe->redirect( "index.php?option=com_rsgallery2&task=batchupload", JText::_('COM_RSGALLERY2_ZIP-FILE_IS_TOO_BIG'));
				}
			}
		} else {
			$ziplist = $uploadfile->handleFTP($ftppath);
		}
		HTML_RSGALLERY::batch_upload_2($ziplist, $uploadfile->extractDir);
	} else {
		HTML_RSGALLERY::batch_upload($option);
	}
}*/ //MK// [not used check]	-end
?>
