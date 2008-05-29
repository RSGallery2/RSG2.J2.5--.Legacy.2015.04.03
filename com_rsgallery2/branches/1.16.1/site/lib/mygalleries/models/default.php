<?php
/**
 * @package		RSGallery2
 * @subpackage	MyGalleries
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// Import library dependencies
jimport( 'joomla.filesystem.folder' );

/**
 * RSGallery2 Template Manager Template Model
 *
 * @package		RSGallery2
 * @subpackage	MyGalleries
 * @since		1.5
 */
class ModelDefault extends JModel
{
	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		global $mainframe;
		
		// Call the parent constructor
		parent::__construct();
		
		//		// Set state variables from the request
		//		$this->setState('filter.string', $mainframe->getUserStateFromRequest( "com_rsgallery2_com_installer.templates.string", 'filter', '', 'string' ));
	}
	
	function getItems()
	{
		$my = JFactory::getUser();

		$this->db->setQuery("SELECT * FROM #__rsgallery2_files" .
				" WHERE userid = '$my->id'" .
				" ORDER BY date DESC");
		$images = $this->db->loadObjectList();

		$this->db->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE parent = 0 AND uid = '$my->id'");
		$galleries = $this->db->loadObjectList();
		
		$data = new stdClass();
		$data->images = $images;
		$data->galleries = $galleries;

		return $data;

	}
	
	function saveItem(){
		global $mainframe;

		$id 	= JRequest::getInt( 'id'  , '');
		$title 	= JRequest::getVar( 'title'  , '');
		$descr 	= JRequest::getVar( 'descr'  , '', 'post', 'string', JREQUEST_ALLOWRAW);
		$catid 	= JRequest::getInt( 'catid'  , '');
		
		$database->setQuery("UPDATE #__rsgallery2_files SET ".
				"title = '$title', ".
				"descr = '$descr', ".
				"gallery_id = '$catid' ".
				"WHERE id= '$id'");
		
		if ($this->db->query()) {
			$this->redirect("index.php?option=com_rsgallery2&rsgOption=myGalleries", 
			                JText::_('Details saved succesfully') );
		} else {
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('Error: ').mysql_error());
		}
		
	}
	
	function saveGallery(){
		global $rsgConfig, $mainframe;
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		
		//If gallery creation is disabled, unauthorized attempts die here.
		if (!$rsgConfig->get('uu_createCat')) die ("User category creation is disabled by administrator.");
		
		//Set redirect URL
		$redirect = "index.php?option=com_rsgallery2&rsgOption=myGalleries&";
		
		$parent 		= rsgInstance::getVar( 'parent'  , 0);
		$id 			= rsgInstance::getInt( 'catid'  , null);
		$catname1 		= rsgInstance::getVar( 'catname1'  , null);
		$description 	= rsgInstance::getVar( 'description'  , null);
		$published 		= rsgInstance::getInt( 'published'  , 0);
		$ordering 		= rsgInstance::getInt( 'ordering'  , null);
		$maxcats        = $rsgConfig->get('uu_maxCat');
		
		if ($id) {
			$database->setQuery("UPDATE #__rsgallery2_galleries SET ".
					"name = '$catname1', ".
					"description = '$description', ".
					"published = '$published', ".
					"parent = '$parent' ".
					"WHERE id = '$id' ");
			if ($database->query()) {
				echo "Query gelukt";
				$mainframe->redirect( $redirect , JText::_('Gallery details updated!') );
			} else {
				echo "Query failed: ".mysql_error();
				$mainframe->redirect( $redirect , JText::_('Could not update gallery details!') );
			}
		} else {
			//New category
			$userCatTotal = galleryUtils::userCategoryTotal($my->id);
			if (!isset($parent))
				$parent = 0;
			if ($userCatTotal >= $maxcats) {
				?>
				<script type="text/javascript">
				//<![CDATA[
				alert(' <?php echo JText::_('MAX_USERCAT_ALERT');?>');
				location = ' <?php echo JRoute::_("index.php?option=com_rsgallery2&page=my_galleries", false); ?>';
				//]]>
				</script>
				<?php
				//$mainframe->redirect( $redirect ,JText::_('MAX_USERCAT_ALERT'));
			} else {
				//Create ordering, start at last position
				$database->setQuery("SELECT MAX(ordering) FROM #__rsgallery2_galleries WHERE uid = '$my->id'");
				$ordering = $database->loadResult() + 1;
				//Insert into database
				$database->setQuery("INSERT INTO #__rsgallery2_galleries ".
						"(name, description, ordering, parent, published, user, uid, date) VALUES ".
						"('$catname1','$description','$ordering','$parent','$published','1' ,'$my->id', now())");
				
				if ($database->query()) {
					//Create initial permissions for this gallery
					$database->setQuery("SELECT id FROM #__rsgallery2_galleries WHERE name = '$catname1' LIMIT 1");
					$gallery_id = $database->loadResult();
					$acl = new rsgAccess();
					if ( $acl->createDefaultPermissions($gallery_id) )
						$mainframe->redirect( $redirect , JText::_('New gallery created!') );
				} else {
					$mainframe->redirect( $redirect , JText::_('ALERT_NONEWCAT') );
				}
			}
		}
		
	}
	
	function uploadItem(){
		global $rsgConfig, $rsgAccess, $mainframe;
		$database = JFactory::getDBO();
		//Set redirect URL
		$redirect = "index.php?option=com_rsgallery2&rsgOption=myGalleries&";
		
		//Get category ID to check rights
		$i_cat = rsgInstance::getVar( 'i_cat'  , '');
		
		//Get maximum number of images to upload
		$max_images = $rsgConfig->get('uu_maxImages');
		
		//Check if user can upload in this gallery
		if ( !$rsgAccess->checkGallery('up_mod_img', $i_cat) ) die('Unauthorized upload attempt!');
		
		//Check if number of images is not exceeded
		$count = 0;
		if ($count > $max_images) {
			//Notify user and redirect
		} else {
			//Go ahead and upload
			$upload = new fileHandler();
			
			//Get parameters from form
			$i_file = rsgInstance::getVar( 'i_file', null, 'files', 'array'); 
			$i_cat = rsgInstance::getInt( 'i_cat'  , ''); 
			$title = rsgInstance::getVar( 'title'  , ''); 
			$descr = rsgInstance::getVar( 'descr'  , ''); 
			$uploader = rsgInstance::getVar( 'uploader'  , ''); 
			
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
						$ziplist = $upload->handleZIP($i_file);
						
						//Set extract dir for uninstall purposes
						$extractdir = JPATH_ROOT . DS . "media" . DS . $upload->extractDir . DS;
						
						//Import images into right folder
						for ($i = 0; $i<sizeof($ziplist); $i++) {
							$import = imgUtils::importImage($extractdir . $ziplist[$i], $ziplist[$i], $i_cat);
						}
						
						//Clean mediadir
						fileHandler::cleanMediaDir( $upload->extractDir );
						
						//Redirect
						$mainframe->redirect( $redirect , JText::_('Item uploaded succesfully!') );
						
					} else {
						//Error message
						$mainframe->redirect( $redirect , JText::_('ZIP-file is too big!'));
					}
					break;
				case 'image':
					//Check if image is too big
					if ($i_file['error'] == 1)
						$mainframe->redirect( $redirect , '*Image size is too big for upload!*' );
					
					$file_name = $i_file['name'];
					if ( move_uploaded_file($i_file['tmp_name'], JPATH_ROOT . DS ."media" . DS . $file_name) ) {
						//Import into database and copy to the right places
						$imported = imgUtils::importImage(JPATH_ROOT . DS ."media" . DS . $file_name, $file_name, $i_cat, $title, $descr);
						
						if ($imported == 1) {
							if (file_exists( JPATH_ROOT . DS ."media" . DS . $file_name ))
								unlink( JPATH_ROOT . DS ."media" . DS . $file_name );
						} else {
							$mainframe->redirect( $redirect , 'Importing image failed! Notify RSGallery2. This should never happen!');
						}
						$mainframe->redirect( $redirect , JText::_('Item uploaded succesfully!') );
					} else {
						$mainframe->redirect( $redirect , JText::_('Upload failed.\\nBack to uploadscreen') );
					}
					break;
				case 'error':
					$mainframe->redirect( $redirect , JText::_('Wrong image format.\\nWe will redirect you to the upload screen') );
					break;
			}
	}	}
	
	function deleteItem(){
		global $rsgAccess, $mainframe;
		$id = rsgInstance::getInt( 'id'  , '');
		if ($id) {		
			//Get gallery id
			$gallery_id = galleryUtils::getCatidFromFileId($id);
			
			//Check if file deletion is allowed in this gallery
			if ($rsgAccess->checkGallery('del_img', $gallery_id )) {
				$filename 	= galleryUtils::getFileNameFromId($id);
				imgUtils::deleteImage($filename);
				$mainframe->redirect("index.php?option=com_rsgallery2&rsgOption=myGalleries", JText::_('Image is deleted') );
			} else {
				$mainframe->redirect("index.php?option=com_rsgallery2&rsgOption=myGalleries", JText::_('USERIMAGE_NOTOWNER') );
			}
		} else {
			//No ID sent, no delete possible, back to my galleries
			$mainframe->redirect("index.php?option=com_rsgallery2&rsgOption=myGalleries", JText::_('No Id provided. Contact component developer') );
		}
		
	}
	function deleteGallery(){
		global $rsgConfig, $mainframe;
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		
		//Get values from URL
		$catid = rsgInstance::getInt( 'catid'  , null);
		
		//Set redirect URL
		$redirect = "index.php?option=com_rsgallery2&rsgOption=myGalleries";
		
		//Get category details
		$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id = '$catid'");
		$rows = $database->LoadObjectList();
		foreach ($rows as $row) {
			$uid = $row->uid;
			$parent = $row->parent;
		}
		
		//Check if gallery has children
		$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_galleries WHERE parent = '$catid'");
		$count = $database->loadResult();
		if ($count > 0) {
			$mainframe->redirect( $redirect ,JText::_('_USERCAT_SUBCATS'));
		}
		
		//No children from here, so lets continue
		if ($uid == $my->id OR $my->usertype == 'Super Administrator') {
			//Delete images
			$database->setQuery("SELECT name FROM #__rsgallery2_files WHERE gallery_id = '$catid'");
			$result = $database->loadResultArray();
			$error = 0;
			foreach ($result as $filename) {
				if ( !imgUtils::deleteImage($filename) ) 
					$error++;
			}
			
			//Error checking
			if ($error == 0) {
				//Gallery can be deleted
				$database->setQuery("DELETE FROM #__rsgallery2_galleries WHERE id = '$catid'");
				if ( !$database->query() ) {
					//Error message, gallery could not be deleted
					$mainframe->redirect( $redirect ,JText::_('Gallery could not be deleted!'));
				} else {
					//Ok, goto mainpage
					$mainframe->redirect( $redirect ,JText::_('Gallery deleted!'));
				}
			} else {
				//There were errors. Gallery will not be deleted
				$mainframe->redirect( $redirect ,JText::_('Gallery could not be deleted!'));
			}
		} else {
			//Abort and return to mainscreen
			$mainframe->redirect( $redirect ,JText::_('USER_CAT_NOTOWNER'));
		}
	}
}
?>






