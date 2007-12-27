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
* @author Ronald Smit <ronald.smit@rsdev.nl>
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgAccess extends JObject{
	/** @var array List of rights per gallery for a specific user type */
	var $actions;
	/** @var array List of rights per gallery for a all user types */
	var $allActions;
	/** @var string table name */
	var $_table;
	
	/** Constructor */
	function __construct() {
		$this->actions = array(
				'view',				//View gallery from frontend
				'up_mod_img',		//Upload and modify images to gallery
				'del_img',			//Delete images from gallery
				'create_mod_gal',	//Create and modify galleries
				'del_gal',			//Delete galleries
				'vote_view',		//View votes result
				'vote_vote'			//Make vote
				);
		$this->levels = array(
				'public',
				'registrered'
				);
		$this->levelMaping = array(				// map user types 
				'public' => 'public',
				'registered' => 'registered',
				'author' => 'registered',
				'editor' => 'registered',
				'publisher' => 'registered',
				'manager' => 'registered',
				'administrator' => 'admin',
				'super administrator' => 'admin'
				);
		
		// assemble actions 							
		$this->allActions = array();
		foreach ($this->levels as $level) {
			foreach ($this->actions	as $action) {
				$this->allActions = $level.'_'.$action; 
			}
		}				
		
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
			// first check if user is the owner.  if so we can assume user has access to do anything
			if( $my->id ){  // check that user is logged in
				$sql = "SELECT uid FROM #__rsgallery2_galleries WHERE id = '$gallery_id'";
				$database->setQuery( $sql );
				if ( $my->id === $database->loadResult() )
					return 1;
			}
			
			if ( !rsgAccess::arePermissionsSet($gallery_id) ) {
				//Aparently no permissions were found in #__rsgallery2_acl, so create default permissions
				rsgAccess::createDefaultPermissions($gallery_id);
				// mosRedirect( "index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries", _RSGALLERY_ACL_NO_PERM_FOUND);
			} 
			
			// check user type for access
			$type = rsgAccess::returnUserType();
			if($this->levelMaping[$type] == "admin")
				// admins are allowed to do everything
				return 1;
			else{
				// get permission from acl table
				$sql = "SELECT ".$type."_".$action." FROM $this->_table WHERE gallery_id = '$gallery_id'";
				$database->setQuery( $sql );
				return intval( $database->loadResult() );
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
		
		//Get action based on that usertype 
		$type = $this->levelMaping[$type];
		$type = ($type == "admin") ? "1" : $type."_".$action;
		
		$sql = "SELECT gallery_id FROM $this->_table WHERE ".$type." = 1";
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
		
		//Check if permissions are set, if not, create them
		if ( rsgAccess::arePermissionsSet($gallery_id) ) {
			
			$parent_id = galleryUtils::getParentId($gallery_id);	
			$sql = "UPDATE $this->_table SET ".
				"gallery_id = '$gallery_id', ".
				"parent_id = '$parent_id', ";
			
			// assemble actions and add them to sql query 							
			foreach ($this->levels as $level) {
				foreach ($this->actions	as $action) {
					$sql .= $level.'_'.$action." = '".$perms[$level.'_'.$action]."', "; 
				}
			}
			$sql = substr($sql, 0, - 2);
			$sql .= " WHERE gallery_id = '$gallery_id'";
			
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
		
		$sql = "DELETE FROM $this->_table WHERE gallery_id = '$gallery_id'";
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
}
//end class //
?>