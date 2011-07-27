<?php
/**
* This file contains code for frontend My Galleries.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
global $rsgConfig;
$document=& JFactory::getDocument();

if($document->getType() == 'html') {
	$cssTemplate = JURI_SITE."components/com_rsgallery2/templates/".$rsgConfig->template."/css/template.css";
	$document->addStyleSheet($cssTemplate);
	$cssMyGalleries = JURI_SITE."components/com_rsgallery2/lib/mygalleries/mygalleries.css";	
	$document->addStyleSheet($cssMyGalleries);
	
}

//Load required class file
require_once( JPATH_RSGALLERY2_SITE . DS . 'lib' . DS . 'mygalleries' . DS . 'mygalleries.class.php' );
//Need galleries.class.php for rsgGalleriesItem that extends JTable
$rsgOptions_path = JPATH_RSGALLERY2_ADMIN .DS. 'options' .DS;	
require_once( $rsgOptions_path . 'galleries.class.php' );
require_once( $rsgOptions_path . 'images.class.php' );

//Get parameters from URL and/or form
$task   = JRequest::getVar('task', '' );
$id		= JRequest::getInt('id','' );
$gid	= JRequest::getInt('gid','' );
$currentState = JRequest::getInt('currentstate','' );

switch( $task ){
    case 'saveUploadedItem':
    	saveuploadedItem();
    	break;
    case 'editItem':
    	editItem();
    	break;
    case 'deleteItem':
    	deleteItem();
    	break;
    case 'saveItem':
    	saveItem();
    	break;
    case 'newCat':
    	editCat(null);
    	break;
    case 'editCat':
    	editCat($gid);  		
    	break;
    case 'saveCat':
    	saveCat($gid);
    	break;
    case 'deleteCat':
    	deleteCat();
    	break;
	case 'editStateGallery':
		editStateGallery($gid, 1-$currentState);
		break;
	case 'editStateItem':
		editStateItem($id, 1-$currentState);
		break;
	default:
		showMyGalleries();
		break;
}

function showMyGalleries() {
	global $rsgConfig;
	$mainframe =& JFactory::getApplication();
	$my = JFactory::getUser();
	$database = JFactory::getDBO();
	
	//Check if My Galleries is enabled in config, if not .............. 
	if ( !$rsgConfig->get('show_mygalleries') ) die(JText::_('COM_RSGALLERY2_UNAUTHORIZED_ACCESS_ATTEMPT_TO_MY_GALLERIES'));
	
	//Set limits for pagenav
	$limit      = trim(JRequest::getInt( 'limit', 10 ) );
	$limitstart = trim(JRequest::getInt( 'limitstart', 0 ) );
	
	//Get total number of records for pagination
	$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files");
	$total = $database->loadResult();
	
	//New instance of mosPageNav
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );
	
	//Get all images
	$database->setQuery("SELECT * FROM #__rsgallery2_files" .
	//					" WHERE userid = '$my->id'" .	//Limit to items for this user
						" ORDER BY date DESC" .
						" LIMIT $pageNav->limitstart, $pageNav->limit");
	$images = $database->loadObjectList();
	//Get all galleries based on hierarchy
	$rows = myGalleries::recursiveGalleriesList();
/*	$database->setQuery("SELECT * FROM #__rsgallery2_galleries"
						." WHERE parent = 0 " 
	//					." AND uid = '$my->id'" 		//Limit to galleries for this user
						);
	$rows = $database->loadObjectList();
*/	
	if($my->id) {
		//User is logged in, show it all!
		myGalleries::viewMyGalleriesPage($rows, $images, $pageNav);
	} else {
		//Not logged in, back to main page
		$mainframe->redirect(JRoute::_("index.php?option=com_rsgallery2"), JText::_('COM_RSGALLERY2_MY_GALLERIES_NEED_TO_LOGIN') );
	}	
}

/**
 * Deletes an item through the frontend My Galleries part
 */
function deleteItem() {
	$mainframe =& JFactory::getApplication();
	$user = JFactory::getUser();
	$database = JFactory::getDBO();
	$id = JRequest::getInt( 'id'  , '');
	$Itemid = JRequest::getInt( 'Itemid'  , '');
	
	//Check if delete is allowed for this item
	if (!$user->authorise('core.delete', 'com_rsgallery2.item.'.$id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}

	if ($id) {		
		$filename 	= galleryUtils::getFileNameFromId($id);
		imgUtils::deleteImage($filename);
		$mainframe->redirect(JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&Itemid=$Itemid",false), JText::_('COM_RSGALLERY2_IMAGE_IS_DELETED') );
	} else {
		//No ID sent, no delete possible, back to my galleries
		$mainframe->redirect(JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries"), JText::_('COM_RSGALLERY2_NO_ID_PROVIDED_CONTACT_COMPONENT_DEVELOPER') );
	}
}

function editItem() {
	$database = JFactory::getDBO();
	$id = JRequest::getInt('id'  , null);
	$user = JFactory::getUser();
	$mainframe =& JFactory::getApplication();
	
	//Check if user is allowed to upload in this gallery
	if (!$user->authorise('core.edit', 'com_rsgallery2.item.'.$id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}

	if ($id) {
		$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$id'");
		$rows = $database->loadObjectList();
		myGalleries::editItem($rows);
	}
}

function saveItem() {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	$mainframe =& JFactory::getApplication();
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	
	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries", false);
	
	$id 	= JRequest::getInt( 'id'  , '');
	//Check if user is allowed to edit this item
	if (!$user->authorise('core.edit',      
	   'com_rsgallery2.item.'.$id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}
	
	//Create item object and get details
	$row = new rsgImagesItem( $database );
	$row->load($id);
	//Not using $row->bind( JRequest::get('post') ) here, too few variables that don't need attention
	$row->title 	= JRequest::getString( 'title'  , '');
	$row->descr 	= JRequest::getVar( 'descr'  , '', 'post', 'string', JREQUEST_ALLOWRAW);
	$row->descr 	= str_replace( '<br>', '<br />', $row->descr );
	$row->gallery_id= JRequest::getInt( 'gallery_id'  , '');
	//Make the alias for SEF
    $row->alias 	= JFilterOutput::stringURLSafe($row->title);
	
	if (!$row->check()) {
		$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_COULD_NOT_UPDATE_IMAGE_DETAILS') );
	}
	if (!$row->store()) {
		$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_COULD_NOT_UPDATE_IMAGE_DETAILS') );	
	}
	
	//Redirect after successfull save
	$mainframe->redirect(JRoute::_( $redirect ), JText::_('COM_RSGALLERY2_DETAILS_SAVED_SUCCESFULLY') );
}

function saveUploadedItem() {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	global $rsgConfig;
	$mainframe =& JFactory::getApplication();
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries",false);

	//Get maximum number of images to upload
	$max_images = $rsgConfig->get('uu_maxImages');

	//Get category ID to check rights
	$gallery_id = JRequest::getVar( 'gallery_id'  , '');
	
	//Check if user is allowed to upload in this gallery
	if (!$user->authorise('core.create', 'com_rsgallery2.gallery.'.$gallery_id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}
	
	//Check if number of images is not exceeded
	$count = 0;
	if ($count > $max_images) {
		//Notify user and redirect
		JError::raiseWarning(404, JText::_('COM_RSGALLERY2_MAXIMUM_NUMBER_OF_IMAGES_UPLOADED_REACHED_DELETE_SOME_IMAGES_FIRST'));
		$mainframe->redirect(JRoute::_( $redirect ));
	} else {
		//Go ahead and upload
		$upload = new fileHandler();
		
		//Get parameters from form
		$i_file = JRequest::getVar( 'i_file', null, 'files', 'array'); 
		$gallery_id = JRequest::getInt( 'gallery_id'  , ''); 
		$title = JRequest::getVar( 'title'  , ''); 
		$descr = JRequest::getVar( 'descr', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$uploader = JRequest::getVar( 'uploader'  , ''); 
		
		//Get filetype
		$file_ext = $upload->checkFileType($i_file['name']);

		//Check whether directories are there and writable
		$check = $upload->preHandlerCheck();
		if ($check !== true ) {
			$mainframe->redirect( $redirect , $check);
		}

		switch ($file_ext) {
			case 'zip':
        		if ($upload->checkSize($i_file) == 1) {
					$ziplist = $upload->extractArchive($i_file);
            		//Set extract dir for uninstall purposes
            		$extractdir = JPATH_ROOT . DS . "media" . DS . $upload->extractDir . DS;
            		//Import images into right folder
            		for ($i = 0; $i<sizeof($ziplist); $i++) {
            			$import = imgUtils::importImage($extractdir . $ziplist[$i], $ziplist[$i], $gallery_id, $title, $descr);
            		}
            		
            		//Clean mediadir
            		fileHandler::cleanMediaDir( $upload->extractDir );

            		//Redirect
            		$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY') );
        		} else {
            		//Error message
            		$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_ZIP-FILE_IS_TOO_BIG'));
        		}
				break;
			case 'image':
				//Check if image is too big
				if ($i_file['error'] == 1){
					$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_IMAGE_SIZE_IS_TOO_BIG_FOR_UPLOAD') );
				}
				
				$file_name = $i_file['name'];
				if ( move_uploaded_file($i_file['tmp_name'], JPATH_ROOT . DS ."media" . DS . $file_name) ) {
					//Import into database and copy to the right places
					$imported = imgUtils::importImage(JPATH_ROOT . DS ."media" . DS . $file_name, $file_name, $gallery_id, $title, $descr);
					
					if ($imported == 1) {
						if (file_exists( JPATH_ROOT . DS ."media" . DS . $file_name ))
							unlink( JPATH_ROOT . DS ."media" . DS . $file_name );
					} else {
						$mainframe->redirect( $redirect , 'Importing image failed! Notify RSGallery2. This should never happen!');
					}
					$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY') );
				} else {
					$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_UPLOAD_FAILED_BACK_TO_UPLOADSCREEN') );
				}
				break;
			case 'error':
			default:
				$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_WRONG_IMAGE_FORMAT_WE_WILL_REDIRECT_YOU_TO_THE_UPLOAD_SCREEN') );
				break;
		}	//end switch ($file_ext)
	}
}

function editCat($id) {
	global $rsgConfig;
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	$mainframe =& JFactory::getApplication();

	//Check if user is allowed to upload in this gallery
	if (!$user->authorise('core.edit', 'com_rsgallery2.gallery.'.$id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}

	if ($id) {
		//Edit category
		$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id ='$id'");
		$rows = $database->LoadObjectList();
		myGalleries::editCat($rows);
	} else {
		//Check if maximum number of usercats are already made
		$count = galleryUtils::userCategoryTotal($user->id);
		if ($count >= $rsgConfig->get('uu_maxCat') ) {
			$mainframe->redirect(JRoute::_("index.php?option=com_rsgallery2&page=my_galleries"), JText::_('COM_RSGALLERY2_MAX_USERCAT_ALERT') );
		} else {
			//New category
			myGalleries::editCat();
		}
	}
}

function saveCat($gid) {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );
	
	global $rsgConfig;
	$mainframe =& JFactory::getApplication();
	$user = JFactory::getUser();
	$database = JFactory::getDBO();

	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries", false);
	
	//Check if user is allowed to upload in the choosen gallery
	$parent_gallery = JRequest::getVar( 'parent');
	if (!$user->authorise('core.create', 'com_rsgallery2.gallery.'.$parent_gallery)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}

	//Get number of galleries allowed and already present
	$maxcats  = $rsgConfig->get('uu_maxCat');	
	$userCatTotal = galleryUtils::userCategoryTotal($user->id);

	//Check if user is allowed to create more galleries (only if this is a new gallery)
	if ((!$gid) AND ($userCatTotal >= $maxcats)) {
		?>
		<script type="text/javascript">
			alert('<?php echo JText::_('COM_RSGALLERY2_MAX_USERCAT_ALERT');?>');
			location = '<?php echo JRoute::_("index.php?option=com_rsgallery2&page=my_galleries", false); ?>';
		</script>
		<?php
		//$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_MAX_USERCAT_ALERT'));
	} else {
		//Instantiate the gallery object
		$row = new rsgGalleriesItem( $database );
		//Not a new gallery? then load its data
		if ($gid){
			$row->load($gid);
		}
		//Bind user input to $row (parent, name, description, existing gallery: gid, ordering)
		if (!$row->bind( JRequest::get('post') )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		//Description: html is allowed
		$row->description = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		//Code cleaner for xhtml transitional compliance 
		$row->description = str_replace( '<br>', '<br />', $row->description );
		//Make the alias for SEF (no matter if it existed or not for frontend editing)
		$row->alias = JFilterOutput::stringURLSafe($row->name);
		//Get/do some additional stuff
		$row->date = date( 'Y-m-d H:i:s' );
		if (!$row->uid){	//Don't change owner
			$row->uid = $user->id;
		}
		//Do some checks (overloads JTable::check() with rsgGalleriesItem::check())
		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		//And store the row (this is where the asset is also stored; JTable::store())
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		//Then checkin and reorder (JTable:checkin() and JTable::reorder())
		$row->checkin();
		$row->reorder( );
		//Finally redirect with success message
		if ($gid) {
			$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_GALLERY_DETAILS_UPDATED') );
		} else {		
			$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_NEW_GALLERY_CREATED') );
		}
	}
	//JText::_('COM_RSGALLERY2_ALERT_NONEWCAT')
	//JText::_('COM_RSGALLERY2_COULD_NOT_UPDATE_GALLERY_DETAILS')
}

function deleteCat() {
	global $rsgConfig;
	$mainframe =& JFactory::getApplication();
	$my = JFactory::getUser();
	$database = JFactory::getDBO();

	//Get values from URL
	$gid = JRequest::getInt( 'gid'  , null);
	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries",false);

	//Is user allowed to delete this gallery?
	$canDelete = $my->authorise($core.delete, 'com_rsgallery2.gallery.'.$gid);

	if (!$canDelete) {
		$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_PERMISSION_NOT_ALLOWED_DELETE_GALLERY'));
	} else {
		//Check if gallery has children
		$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_galleries WHERE parent = '$gid'");
		$count = $database->loadResult();
		if ($count > 0) {
			$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_USERCAT_SUBCATS'));
		}
		
		//No children from here, so lets continue
		//Get rsgImagesItem object
		$gallery_row = new rsgGalleriesItem( $database );
		//Get category details
		$gallery_row->load($gid);

		//Delete images
		$database->setQuery("SELECT name FROM #__rsgallery2_files WHERE gallery_id = '$gid'");
		$result = $database->loadResultArray();
		$error = 0;
		foreach ($result as $filename) {
			if ( !imgUtils::deleteImage($filename) ) 
				$error++;
		}
		
		//Error checking
		if ($error == 0) {
			//Gallery can be deleted
			if (!$gallery_row->delete($gid)){
				$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_GALLERY_COULD_NOT_BE_DELETED'));
			} else {
				//Ok, goto mainpage
				$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_GALLERY_DELETED'));
			}
		} else {
			//Abort and return to mainscreen
			$mainframe->redirect( $redirect ,JText::_('COM_RSGALLERY2_GALLERY_NOT_DELETED_SINCE_NOT_ALL_IMAGES_DELETED'));
		}
	}
}

function editStateGallery($galleryId, $newState) {
	$mainframe =& JFactory::getApplication();
	$user = JFactory::getUser();
	$database = JFactory::getDBO();
	
	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries", false);

	//Check if user is allowed to edit the state of this gallery
	if (!$user->authorise('core.edit.state', 'com_rsgallery2.gallery.'.$galleryId)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}
	
	if ($galleryId) {
		$database->setQuery("UPDATE #__rsgallery2_galleries SET ".
			"published = $newState ".
			"WHERE id = $galleryId ");
		if ($database->query()) {
			$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_GALLERY_DETAILS_UPDATED') );
		} else {
			$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_COULD_NOT_UPDATE_GALLERY_DETAILS') );
		}
	}
	//$mainframe->redirect( $redirect  );
}
function editStateItem($id, $newState) {
	$mainframe =& JFactory::getApplication();
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	//Set redirect URL
	$redirect = JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries", false);
	
	//Check if user is allowed to edit the state of this item
	if (!$user->authorise('core.edit.state', 'com_rsgallery2.item.'.$id)){
		JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		$mainframe->redirect(JRoute::_( $redirect ));
	}
	
	$database->setQuery("UPDATE #__rsgallery2_files SET ".
			"published = $newState ".
			"WHERE id= '$id'");

	if ($database->query()) {
		$mainframe->redirect(JRoute::_( $redirect ), JText::_('COM_RSGALLERY2_DETAILS_SAVED_SUCCESFULLY') );
	} else {
		//echo JText::_('COM_RSGALLERY2_ERROR-').mysql_error();
		$mainframe->redirect( $redirect , JText::_('COM_RSGALLERY2_COULD_NOT_UPDATE_IMAGE_DETAILS') );
	}
}
?>