<?php
/**
* This file contains the non-presentation processing for the Admin section of RSGallery.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Access Denied.' );

// initialize RSG2 core functionality
require_once( $mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );

// instantate user variables but don't show a frontend template
rsgInstance::instance( 'request', false );

// XML library
require_once( $mosConfig_absolute_path .'/includes/domit/xml_domit_lite_include.php' );

//Load Tooltips
JHTML::_('behavior.tooltip');

?>
<link href="<?php echo $mosConfig_live_site; ?>/administrator/components/com_rsgallery2/admin.rsgallery2.css" rel="stylesheet" type="text/css" />
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
}

/**
    admin pathway hack when $rsgOption is used.
    this probably only works with Joomla <1.5
**/
if( $rsgOption != '' ){
    $option = "<a href='$mosConfig_live_site"
        . "/administrator/index2.php?option=com_rsgallery2'>"
        . "RSGallery2</a> / ";
    if( $task == '' ){
        $option .= "$rsgOption";
    }
    else{
        $option .= "<a href='$mosConfig_live_site"
        . "/administrator/index2.php?option=com_rsgallery2&rsgOption=$rsgOption'>"
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
    case "regen_thumbs":
        HTML_RSGallery::RSGalleryHeader();
        HTML_RSGALLERY::printAdminMsg( "Feature not implemented. To follow.\n\n\nRonald Smit", true );
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "consolidate_db":
        HTML_RSGallery::RSGalleryHeader('dbrestore',_RSGALLERY_HEAD_CONSDB);
        consolidateDbInform($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "consolidate_db_go":
        HTML_RSGallery::RSGalleryHeader('dbrestore',_RSGALLERY_HEAD_CONSDB);
        consolidateDbGo($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
    /*case "import_captions":
        HTML_RSGallery::RSGalleryHeader('generic', 'Import Captions');
        import_captions();
        HTML_RSGallery::RSGalleryFooter();
        break;
        */
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
    /*
    case "upload":
        HTML_RSGallery::RSGalleryHeader('browser', _RSGALLERY_HEAD_UPLOAD);
        showUpload();
        HTML_RSGallery::RSGalleryFooter();
        break;
    */
    case "batchupload":
        HTML_RSGallery::RSGalleryHeader('', _RSGALLERY_HEAD_UPLOAD_ZIP);
        batch_upload($option, $task);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case "save_batchupload":
        save_batchupload();
        break;
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


//     image and category tasks
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

    case 'c_delete':
        HTML_RSGallery::RSGalleryHeader('dbrestore', _RSGALLERY_HEAD_CONSDB);
        c_delete();
        consolidateDbGo($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case 'c_create':
        HTML_RSGallery::RSGalleryHeader('dbrestore', _RSGALLERY_HEAD_CONSDB);
        c_create($id);
        HTML_RSGALLERY::printAdminMsg(_RSGALLERY_HEAD_MISS_IMG_CREATE);
        consolidateDbGo($option);
        HTML_RSGallery::RSGalleryFooter();
        break;
    case 'db_create':
    	db_create();
    	break;
    case 'upload_watermark':
    	uploadWatermark();
    	break;
    case 'test':
    	test();
    	break;
//     default - the control panel
    case "controlPanel":
    default:
        HTML_RSGallery::RSGalleryHeader('cpanel', _RSGALLERY_HEAD_CPANEL);
        HTML_RSGallery::showCP();
        HTML_RSGallery::RSGalleryFooter();
        break;
}

function uploadWatermark() {
	//Catch variables from form
	$filename 	= rsgInstance::getVar('watermark', null, 'FILES');
	echo "<pre>";
	print_r($filename);
	echo "</pre>";
}
	/*
	//Check if image is png, if not, abort.
	if ( !strtolower( $filename['type'] ) == 'image/png') {
		mosRedirect('index2.php?option=com_rsgallery2&page=showConfig', 'Watermark is not a PNG image!');
	}
	//Move uploaded file to images/rsgallery/watermark.png
	if ( !move_uploaded_file($filename['tmp_name'], JPATH_ROOT . DS . 'images' . DS . 'rsgallery' . DS . 'watermark.png') ) {
		mosRedirect('index2.php?option=com_rsgallery2&page=showConfig', 'Watermark upload failed');
	} else {
		mosRedirect('index2.php?option=com_rsgallery2&page=showConfig', 'Watermark uploaded succesfully');
	}
	*/

/**
* @param string The name of the php (temporary) uploaded file
* @param string The name of the file to put in the temp directory
* @param string The message to return
*/
function uploadFile( $filename, $userfile_name, $msg ) {
	global $mosConfig_absolute_path;
	$baseDir = mosPathName( $mosConfig_absolute_path . '/media' );

	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
				if (mosChmod( $baseDir . $userfile_name )) {
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



function test() {
	global $rsgAccess, $my;
	$rsgAccess->actionPermitted('del_img');
}


function viewChangelog(){
    //global $mosConfig_absolute_path;
    
    echo '<pre>';
    readfile( JPATH_RSGALLERY2_ADMIN.'/changelog.php' );
    echo '</pre>';
}

/**
 * This function is called during step 2 of the RSGallery installation. It
 * outputs the HTML allowing the user to select between a "fresh" install,
 * or an "upgrade" install.
 */
function RSInstall() {
    global $opt, $mosConfig_live_site;
    require_once(JPATH_RSGALLERY2_ADMIN.'/includes/install.class.php');
    
    //Initialize new install instance
    $rsgInstall = new rsgInstall();

	$type = rsgInstance::getVar('type', null);

    switch ($opt) {
        case "fresh":
            $rsgInstall->FreshInstall();
            break;
        case "upgrade":
            $rsgInstall->upgradeInstall();
            break;
        case "migration":
            if( $type=='' ) {
            	$rsgInstall->showMigrationOptions();	
            	} else {
                $result = $rsgInstall->doMigration( $type );
                if( $result !==true ) {
                    echo $result;
                    HTML_RSGallery::showCP();
                	} else {
                    	echo _RSGALLERY_MIGR_OK;
                    	HTML_RSGallery::showCP();
                	}
            	}
            break;
        default:
            $rsgInstall->showInstallOptions();
            break;
        }
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
    global $mosConfig_absolute_path;
    
    passthru( "rm -r $mosConfig_absolute_path/images/rsgallery");
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
    global $database;
    
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

function c_delete() {
global $database;
    $cid    = rsgInstance::getInt( 'cid', null);
	//Dani�l: "should this conditional be sill here?"
    if (isset($_REQUEST['name']))
    	$name = rsgInstance::getVar('name', null);
    else
    	$name = galleryUtils::getFileNameFromId( $cid );
    
    //Check if file is in database
    $sql ="SELECT count(name) FROM #__rsgallery2_files WHERE name = '$name'";
    $database->setQuery($sql);
    $result = $database->loadResult();
    
    if ($result > 0) {
    	//Delete from database
    	imgUtils::deleteImage( galleryUtils::getFileNameFromId( $cid ) );
    	HTML_RSGALLERY::printAdminMsg( _RSGALLERY_ALERT_IMGDELETEOK );
    } else {
    	imgUtils::deleteImage( $name );
    	HTML_RSGALLERY::printAdminMsg( _RSGALLERY_ALERT_IMGDELETEOK );
    }
}
/**
 * Used in the consolidate database function
 * Creates images based on an image id or an image name
 */
function c_create() {
	global $rsgConfig, $database;
	//Check if id or name is set
	if ( isset( $_REQUEST['id'] ) ) {
		$id = rsgInstance::getInt( 'id', null);
		$name = galleryUtils::getFileNameFromId($id);
	}
	elseif ( isset($_REQUEST['name'] ) ) {
		$name    = rsgInstance::getVar( 'name', null);
	} else {
		mosRedirect("index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_CC_NO_FILE_INFO);
	}
	
	//Just for readability of code
	$original = JPATH_ORIGINAL.DS.$name;
	$display  = JPATH_DISPLAY.DS.imgUtils::getImgNameDisplay($name);
	$thumb    = JPATH_THUMB.DS.imgUtils::getImgNameThumb($name);
	    
	if ( file_exists($original) ) {
		//Check if display image exists, if not make it.
		if (!file_exists($display)) {
	    	imgUtils::makeDisplayImage($original, NULL, $rsgConfig->get('image_width') );
	    }
		if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($original);
	    }
	} else {
	    if (file_exists($display)) {
	        copy($display, $original);
	    }
	    if (!file_exists($thumb)) {
	        imgUtils::makeThumbImage($display);
	    }
	}
}
/**
 * Creates DB records for images in system without DB entries
 */
function db_create() {
	global $database;
	$name = rsgInstance::getVar('name'  , null);
    $gallery_id = rsgInstance::getInt('gallery_id'  , null);
    
    //Force only first entry, if more are selected. Temporary measure untill multiple entries is supported!
    if ( is_array($name) )
    	$name = $name[0];
    	
    if ( is_array($gallery_id) )
    	$gallery_id = $gallery_id[0];
    
    //Redirect if no gallery chosen
    if ($gallery_id < 1)
    	mosRedirect("index2.php?option=com_rsgallery2&task=consolidate_db_go", _RSGALLERY_DB_CREATE_NO_GAL);
    
    //If we are here, we're good to go. Save entry into database
    $title = explode(".", $name);
    $descr = "";
    
    // determine ordering
	$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id = '$gallery_id'");
	$ordering = $database->loadResult() + 1;
	
	$database->setQuery("INSERT INTO #__rsgallery2_files".
                " (title, name, descr, gallery_id, date, ordering, userid) VALUES".
                " ('$title[0]', '$name', '$descr', '$gallery_id', now(), '$ordering', '$my->id')");
	if ( $database->query() )
		mosRedirect("index2.php?option=com_rsgallery2&task=consolidate_db_go", _RSGALLERY_DB_CREATE_IMG_SUCCES);
	else
		mosRedirect("index2.php?option=com_rsgallery2&task=consolidate_db_go", _RSGALLERY_DB_CREATE_IMG_FAIL);
}

function save_batchupload() {
    global $database, $mosConfig_live_site, $rsgConfig;
    
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
        mosRedirect("index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_ALERT_NOCATSELECTED);
        }
	/*
	foreach ($filename as $file) {
		
	}
	*/
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
        mosRedirect("index2.php?option=com_rsgallery2", _RSGALLERY_ALERT_UPLOADOK);
    }
}

function cancelGallery($option)
    {
    mosRedirect("index2.php?option=$option");
    }
/**
 * Handles the batchupload function from backend
 * 
 */

function batch_uploadX( $option ) {
	global $rsgConfig, $mainframe, $database;
	$FTP_path = $rsgConfig->get('ftp_path');

    //Retrieve data from submit form
    $batchmethod 	= rsgInstance::getVar('batchmethod'	, null);
    $uploaded 		= rsgInstance::getVar('uploaded'	, null);
    $selcat 		= rsgInstance::getInt('selcat'		, null);
    $zip_file 		= rsgInstance::getVar('zip_file'	, null, 'FILES');
    $ftppath 		= rsgInstance::getVar('ftppath'		, null);
    $xcat 			= rsgInstance::getInt('xcat'		, null);
    
    //Check if a gallery exists, if not link to gallery creation
    $database->setQuery( "SELECT id FROM #__rsgallery2_galleries" );
    $database->query();
    if( $database->getNumRows()==0 ){
        HTML_RSGALLERY::requestCatCreation( );
        return;
    }
    
    //Start new archive extraction
    $batchupload = new fileHandler();
    
    if (isset($uploaded)) {
    	switch ($batchmethod) {
    		case 'zip':
    			//Check if size does not exceed limits
    			if ( $batchupload->checkSize($zip_file) ) {
	                $ziplist = $batchupload->extractArchive($zip_file);
	            } else {
	                //Error message
	                $mainframe->redirect( "index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_ZIP_TO_BIG);
	                exit;
	            }
	            HTML_RSGALLERY::batch_upload_2($ziplist, $batchupload->extractDir);
    			break;
    		case 'ftp':
    			$ziplist = $batchupload->handleFTP($ftppath);
    			HTML_RSGALLERY::batch_upload_2($ziplist, $batchupload->extractDir);
    			break;
    	}
    } else {
    	HTML_RSGALLERY::batch_upload($option);
    }
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
 
function batch_upload($option) {
	global $database, $mosConfig_live_site, $rsgConfig;
	$FTP_path = $rsgConfig->get('ftp_path');
	
	//Retrieve data from submit form
	$batchmethod = rsgInstance::getVar('batchmethod'  , null);
	$uploaded = rsgInstance::getVar('uploaded'  , null);
	$selcat = rsgInstance::getInt('selcat'  , null);
	$zip_file = rsgInstance::getVar('zip_file'  , null, 'FILES');
	$ftppath = rsgInstance::getVar('ftppath'  , null);
	$xcat = rsgInstance::getInt('xcat'  , null);
	
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
				mosRedirect( "index2.php?option=com_rsgallery2&task=batchupload", $zip_file['name']._RSGALLERY_BACTCH_NOT_VALID_ZIP);
			} else {
				//Valid ZIP-file, continue
				if ($uploadfile->checkSize($zip_file) == 1) {
					$ziplist = $uploadfile->handleZIP($zip_file);
				} else {
					//Error message
					mosRedirect( "index2.php?option=com_rsgallery2&task=batchupload", _RSGALLERY_ZIP_TO_BIG);
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

function consolidateDbInform($option){
    // inform user of purpose of this function, then provide a proceed button
	?>
    <script language="Javascript">
        function submitbutton(pressbutton){
            if (pressbutton != 'cancel'){
                submitform( pressbutton );
                return;
            } else {
                window.history.go(-1);
                return;
            }
        }
    </script>
    <form action="index2.php" method="post" name="adminForm">
    <table class="adminform" cellpadding="4" cellspacing="0" border="0" width="98%" align="center">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo _RSGALLERY_CONSOLIDATE_DB;?></td>
        </tr>
        <tr>
            <td>
                <div align="center">
                <input type="button" name="consolidate_db_go" value="<?php echo _RSGALLERY_PROCEED ?>" class="button" onClick="submitbutton('consolidate_db_go');" />
                <input type="button" name="cancel" value="<?php echo _RSGALLERY_CANCEL ?>" class="button" onClick="submitbutton('cancel');" />
                </div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    </form>

<?php
}
/**
 * Changes all values of an array to lowercase
 * @param array mixed case mixed or upper case values
 * @return array lower case values
 */
function arrayToLower($array) {
    $array = explode("|", strtolower(implode("|",$array)));
    return $array;
}

/**
 * Fills an array with the filenames, found in the specified directory
 * @param string Directory from Joomla root
 * @return array Array with filenames
 */
function getFilenameArray($dir){
    global $rsgConfig;
    
    //Load all image names from filesystem in array
    $dh  = opendir(JPATH_ROOT.$dir);
    //Files to exclude from the check
    $exclude = array('.', '..', 'Thumbs.db', 'thumbs.db');
    $allowed = array('jpg','gif');
    $names_fs = array();
    while (false !== ($filename = readdir($dh)))
        {
        $ext = explode(".", $filename);
        $ext = array_reverse($ext);
        $ext = strtolower($ext[0]);
        if (!is_dir(JPATH_ROOT.$dir."/".$filename) AND !in_array($filename, $exclude) AND in_array($ext, $allowed))
            {
            if ($dir == $rsgConfig->get('imgPath_display') OR $dir == $rsgConfig->get('imgPath_thumb'))
                {
                //Recreate normal filename, eliminating the extra ".jpg"
                $names_fs[] = substr(strtolower($filename), 0, -4);
                }
            else
                {
                $names_fs[] = strtolower($filename);
                }
            }
        else
            {
            //Do nothing
            continue;
            }
        }
    closedir($dh);
    return $names_fs;
    
}
function consolidateDbGo($option)
    {
    global $database, $rsgConfig;

    //Load all image names from DB in array
    $sql = "SELECT name FROM #__rsgallery2_files";
    $database->setQuery($sql);
    $names_db = arrayToLower($database->loadResultArray());

    $files_display  = getFilenameArray($rsgConfig->get('imgPath_display'));
    $files_original = getFilenameArray($rsgConfig->get('imgPath_original'));
    $files_thumb    = getFilenameArray($rsgConfig->get('imgPath_thumb'));
    $files_total    = array_unique(array_merge($files_display,$files_original,$files_thumb));
    
    HTML_RSGALLERY::consolidateDbGo($names_db, $files_display, $files_original, $files_thumb, $files_total);
    }

?>