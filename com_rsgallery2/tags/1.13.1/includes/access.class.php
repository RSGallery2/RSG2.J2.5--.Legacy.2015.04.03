<?php
/**
* Access Manager Class for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 rsgallery2.net
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Access Denied.' );

/**
* Access Manager
* Handles permissions on galleries
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca> & Ronald Smit <ronald.smit@rsdev.nl>
*/
class rsgAccess{
    /** @var array List of rights per gallery for a specific user type */
    var $actions;
    /** @var array List of rights per gallery for a all user types */
    var $allActions;
    /** @var string table name */
    var $_table;
    
    /** Constructor */
    function rsgAccess() {
    	$this->actions = array(
							'view',				//View gallery from frontend
							'up_mod_img',		//Upload and modify images to gallery
							'del_img',			//Delete images from gallery
							'create_mod_gal',	//Create and modify galleries
							'del_gal'			//Delete galleries
							);
		$this->allActions = array(
							'public_view',					//View gallery from frontend
							'public_up_mod_img',			//Upload and modify images to gallery
							'public_del_img',				//Delete images from gallery
							'public_create_mod_gal',		//Create and modify galleries
							'public_del_gal',				//Delete galleries
							'registered_view',				//View gallery from frontend
							'registered_up_mod_img',		//Upload and modify images to gallery
							'registered_del_img',			//Delete images from gallery
							'registered_create_mod_gal',	//Create and modify galleries
							'registered_del_gal'			//Delete galleries		
							);
		$this->_table = "#__rsgallery2_acl";
    }
    
    /**
     * Checks whether Access Control is activated by the user
     * @return boolean True or False
     */
    function aclActivated() {
    	global $rsgConfig;
    	$enabled = $rsgConfig->get('acl_enabled');
		return $enabled;
    }
    
    /**
     * Returns an array with all permissions for a specific gallery
     * @param int Gallery ID
     * @return array actions as key, permissions as value
     */
	function checkGalleryAll($gallery_id) {
		global $database;
		foreach ($this->actions as $action) {
			$list[] = rsgAccess::checkGallery($action, $gallery_id);
		}
		$list = array_combine($this->actions, $list);
		return $list;	
    }
    
    /**
     * Checks if a specific action on a gallery is allowed by the logged in user
     * Public (not logged in user) and Registered users have specific permissions.
     * Users above registered are treated as Registered. Administrator and Super Administrator 
     * have all rights.  
     * @param string action to perform on gallery(view, up_mod_img, del_img, create_mod_gal, del_gal)
     * @param int gallery ID of gallery to perform action on
     * @return int 1 if allowed, 0 if not allowed.
     */
    function checkGallery($action, $gallery_id ) {
    	global $database, $my, $check, $Itemid;
    	//Check if Access Control is enabled
    	if ( !rsgAccess::aclActivated() ) {
    		//Acl not activated, always return 1;
    		return 1;
    	} elseif ($gallery_id == 0){
    		//Check for root, always return 1
    		return 1;
    	} else {	
	    	//Check usertype
	    	$type = rsgAccess::returnUserType();
	    	
	    	if ( !rsgAccess::arePermissionsSet($gallery_id) ) {
	    		//Aparently no permissions were found in #__rsgallery2_acl, so create default permissions
	    		rsgAccess::createDefaultPermissions($gallery_id);
	    		mosRedirect( "index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries", _RSGALLERY_ACL_NO_PERM_FOUND);
	    	} else {
		    	//Determine right action
		    	switch ($type) {
		    	case "public":
		    		$action = $type."_".$action;
					$sql = "SELECT $action FROM $this->_table WHERE gallery_id = '$gallery_id'";
					$database->setQuery( $sql );
					$check = $database->loadResult();
					break;
		    	case "registered":
		    	case "author":
		    	case "editor":
		    	case "publisher":
		    	case "manager":
		    		$action = "registered_".$action;
					$sql = "SELECT $action FROM $this->_table WHERE gallery_id = '$gallery_id'";
					$database->setQuery( $sql );
					$check = $database->loadResult();
					break;
				case "administrator":
				case "super administrator":
					$check = 1;
					break;
		    	}
		    //Return true(1) or false(0)
		    return intval($check);
	    	}
    	}
    }
    
    /**
     * Returns formatted usertype from $my for use in checkGallery().
     */
     function returnUserType() {
     	global $my;
     	if ( isset($my->usertype) && $my->usertype != "" )
     		$type = strtolower($my->usertype);
     	else
     		$type = "public";
     	return $type;
     }
     
	/**
	 * Returns permissions for a specific gallery
	 * @param int gallery id
	 * @return array gallery permissions
	 */
	function returnPermissions($id) {
		global $database;
		$sql = "SELECT * FROM $this->_table WHERE gallery_id = '$id'";
		$database->setQuery( $sql );
		$rows = $database->loadObjectList();
		if ($rows)
			{
			foreach ($rows as $row)
				$perms = $row;
			return $perms;
			}
		else
			{
			return false;
			}
	}
	
	/**
	 * Returns all gallery_id's where a specific action is permitted
	 * @param integer Action we want to check
	 * @return array Array with selected gallery_id's
	 */
	 function actionPermitted($action) {
	 	global $database;
	 	//Check usertype of the logged in user
		$type = rsgAccess::returnUserType();
		//Action switch based on that usertype 
		switch ($type) {
			case 'public':
				//Select all gallery_id's where the requested action is allowed for the logged in usertype(public)
				$sql = "SELECT gallery_id FROM #__rsgallery2_acl WHERE public_".$action." = 1";
				break;
			case "registered":
		    case "author":
		    case "editor":
		    case "publisher":
		    case "manager":
		    	////Select all gallery_id's where the requested action is allowed for the logged in usertype(registered, author, editor, publisher, manager)
		    	$sql = "SELECT gallery_id FROM #__rsgallery2_acl WHERE registered_".$action." = 1";
		    	break;
		    case "administrator":
		    case "super administrator":
		    	//Select them all, 'cause you're the man!'
		    	$sql = "SELECT gallery_id FROM #__rsgallery2_acl";
		    	break;
		}
		$database->setQuery($sql);
		$galleries = $database->loadResultArray();
		return $galleries;
	 }
	
	
	
	/**
	 * Saves the permissions from the form into the database
	 * @param array Array with permissions
	 * @return boolean True if succesfull, false if otherwise
	 */
	function savePermissions( $perms, $gallery_id ) {
		global $database;
		//Set variables
		$public_view 				= $perms['public_view'];
		$public_up_mod_img 			= $perms['public_up_mod_img'];
		$public_del_img 			= $perms['public_del_img'];
		$public_create_mod_gal 		= $perms['public_create_mod_gal'];
		$public_del_gal 			= $perms['public_del_gal'];
		$registered_view 			= $perms['registered_view'];
		$registered_up_mod_img 		= $perms['registered_up_mod_img'];
		$registered_del_img 		= $perms['registered_del_img'];
		$registered_create_mod_gal 	= $perms['registered_create_mod_gal'];
		$registered_del_gal 		= $perms['registered_del_gal'];
		
		//Check if permissions are set, if not, create them
		if ( rsgAccess::arePermissionsSet($gallery_id) ) {
			//echo "Perms zijn gevonden, updaten die hap";
			$parent_id = galleryUtils::getParentId($gallery_id);	
			$sql = "UPDATE $this->_table SET ".
					"gallery_id = '$gallery_id', ".
					"parent_id = '$parent_id', ".
					"public_view = '$public_view', ".
					"public_up_mod_img = '$public_up_mod_img', ".
					"public_del_img = '$public_del_img', ".
					"public_create_mod_gal = '$public_create_mod_gal', ".
					"public_del_gal = '$public_del_gal', ".
					"registered_view = '$registered_view', ".
					"registered_up_mod_img = '$registered_up_mod_img', ".
					"registered_del_img = '$registered_del_img', ".
					"registered_create_mod_gal = '$registered_create_mod_gal', ".
					"registered_del_gal = '$registered_del_gal'".
					" WHERE gallery_id = '$gallery_id'";
			$database->setQuery($sql);
			if ( $database->query() )
				return true;
			else
				return false;
		} else {
			//
			if ( rsgAccess::createDefaultPermissions($gallery_id) )
				return true;
			else
				return false;
			}
	}

	/**
	 * Creates the initial permission list for the specified gallery
	 * $param int Gallery ID
	 * @return boolean True if succesfull, false if not.
	 */
	 function createDefaultPermissions($gallery_id) {
	 	global $database;
	 	$parent_id = galleryUtils::getParentId($gallery_id);
	 	
	 	$sql = "INSERT INTO $this->_table ".
	 			"(gallery_id, parent_id) VALUES ".
	 			"('$gallery_id','$parent_id')";
	 	$database->setQuery($sql);
	 	if ($database->query())
	 		return true;
	 	else
	 		return false;
	 }
	 
	 /**
	  * Deletes permissions from a specific gallery out of #__rsgallery2_acl
	  * @param int Gallery ID
	  * @return boolean True if succesfull, false if otherwise
	  */
	 function deletePermissions( $gallery_id ) {
        global $database;
        
	 	$sql = "DELETE FROM #__rsgallery2_acl WHERE gallery_id = '$gallery_id'";
	 	$database->setQuery($sql);
	 	if ($database->query())
	 		return true;
	 	else
	 		return false;
	 }
	 
	 /**
	  * function will create initial permissions for all existing galleries
	  * Is called only once from install script on upgrade.
	  */
	 function initializePermissions() {
	 	global $database;
	 	$i = 0;
	 	$sql = "SELECT id FROM #__rsgallery2_galleries";
	 	$database->setQuery($sql);
	 	$row = $database->loadResultArray();
	 	if (count($row) < 1) {
	 		return false;
	 	} else {
	 		foreach ($row as $id) {
		 		if (!rsgAccess::createDefaultPermissions($id)) {
		 			$i++;
		 		}
	 		}
	 	}
	 	
	 	if ($i > 0)
	 		return true;
	 	else
	 		return false;
	 }
	 /**
	  * Checks if a set of permissions is available for this specific gallery
	  * @param integer Gallery id
	  * @return boolean True or false
	  */
	 function arePermissionsSet($gallery_id) {
	 	global $database;
	 	$sql = "SELECT COUNT(1) FROM $this->_table WHERE gallery_id = '$gallery_id'";
    	$database->setQuery($sql);
    	$count = $database->loadresult();
    	if ($count > 0) {
    		return true;
    	} else {
    		return false;
    	}
	 }
	 /**
	  * Pads the array to the original size, filling them up with 0 when permission is not granted
	  * Creates a key=>value pair for easy handling
	  * @param array Checkbox data from the permissions form
	  * @return array Padded array, containing all values for the ACL table
	  */
	 function makeArrayComplete($array) {
	 	//For PHP versions < 5.x
		if (!function_exists('array_combine')) {
			function array_combine($a, $b) {
				$c = array();
				if (is_array($a) && is_array($b))
					while (list(, $va) = each($a))
						if (list(, $vb) = each($b))
							$c[$va] = $vb;
				else
					break 1;
			return $c;
			}
		}
		
		$array2 = array_pad(array(), 10, 0);
    	$newArr = $array + $array2;
    	ksort($newArr);
    	$array_complete = array_combine($this->allActions, $newArr);
    	return $array_complete;	 	
	 }
	 
	
}//end class

?>