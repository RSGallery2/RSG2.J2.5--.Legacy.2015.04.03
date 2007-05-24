<?php
/**
* This file handles gallery manipulation functions for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 rsgallery2.net
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Access Denied.' );
require_once( $mosConfig_absolute_path .'/administrator/components/com_installer/installer.class.php' );
/**
* Gallery utilities class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgGalleryManager{

    /**
     * returns a gallery
     * @param id of the gallery
     */
    function get( $id ){
        global $rsgAccess, $rsgConfig;

        // since the user will never be offered the chance to view a gallery they can't, unauthorized attempts at viewing are a hacking attempt, so it is ok to print an unfriendly error.
        $rsgAccess->checkGallery( 'view', $id ) or die("RSGallery2: Access denied to gallery $id");

        $gallery = rsgGalleryManager::_get( $id );
        
        // if gallery is unpublished don't show it unless ACL is enabled and users has permissions to modify (owners can view their unpublished galleries).
        if( $gallery->get('published')< 1 ) {
            if( $rsgConfig->get( 'acl_enabled' )){
                if( !$rsgAccess->checkGallery( 'create_mod_gal', $id )) die("RSGallery2: Access denied to gallery $id");
            }
            else{
                die("RSGallery2: Access denied to gallery $id");
            }
        }
        
        return $gallery;
    }

    /**
     * returns an array of all images in $parent and sub galleries
     * @param int id of parent gallery
     * @todo this is a stub, no functionality yet
     */
    function getFlatArrayofImages( $parent ){
        return true;
    }
    /**
     * returns an array of all sub galleris in $parent including $parent
     * @param int id of parent gallery
     * @todo this is a stub, no functionality yet
     */
    function getFlatArrayofGalleries( $parent ){
        return true;
    }

    /**
     * returns an array of galleries from an array of IDs
     * @param id of the gallery
     */
    function getArray( $cid ){
        $galleries = array();
        
        foreach( $cid as $gid ){
            $galleries[] = rsgGalleryManager::_get( $gid );
        }
        return $galleries;
    }
    
    /**
     * returns an array of galleries
     * @param id of parent gallery
     */
    function getList( $parent ){
        global $database, $rsgAccess, $rsgConfig;

        if( !is_numeric( $parent )) return false;
        
        // since the user will never be offered the chance to view a gallery they can't, unauthorized attempts at viewing are a hacking attempt, so it is ok to print an unfriendly error.
        $rsgAccess->checkGallery( 'view', $parent ) or die("RSGallery2: Access denied to gallery $parent");

        $database->setQuery("SELECT * FROM #__rsgallery2_galleries".
                            " WHERE parent = '$parent'".
                            " ORDER BY ordering ASC");
        $rows = $database->loadAssocList();
        $galleries = array();

        foreach( $rows as $row ){
            // check if user has view access
            if( !$rsgAccess->checkGallery( 'view', $row['id'] )) continue;

            // if gallery is unpublished don't show it unless ACL is enabled and users has permissions to modify (owners can view their unpublished galleries).
            if( $row['published']<1 ){
                if( $rsgConfig->get( 'acl_enabled' )){
                    if( !$rsgAccess->checkGallery( 'create_mod_gal', $row['id'] )) continue;
                }
                else{
                    continue;
                }
            }
            
            $galleries[] = new rsgGallery( $row );
        }

        return $galleries;
    }

    /**
     * recursively deletes all galleries and subgalleries in array
     * @param array of gallery ids
     */
    function deleteArray( $cid ){
        global $rsgAccess;

        // check if user has access
        // note we don't check sub galleries of these galleries.  if a user has the right to delete a gallery, they automatically have the right to delete any sub galleries therein.
        foreach( $cid as $gid ){
            // an unfriendly error since the user will never be offered the chance to delete a gallery they cannot.
            $rsgAccess->checkGallery( 'delete', $gid ) or die("RSGallery2: Access denied to delete gallery $gallery");
        }

        // delete all galleries and sub galleries
        $galleries = rsgGalleryManager::_getArray( $cid );

        return rsgGalleryManager::_deleteTree( $galleries );
    }

    /*
        private functions
        no access checks are made, do not use outside this class!
    */

    /**
     * returns a gallery
     * @param rsgGallery object
     */
    function _get( $gallery ){
        global $database;

        if( !is_numeric( $gallery )) die("gallery id is not a number: $gallery");
        
        $database->setQuery("SELECT * FROM #__rsgallery2_galleries ".
                            "WHERE id = '$gallery' ".
                            "ORDER BY ordering ASC ");

        $row = $database->loadAssocList();
        if( count($row)==0 && $gallery!=0 ){
            die("gallery id does not exist: $gallery");
        }
        else if( count($row)==0 && $gallery==0 ){
            // gallery is root, and it aint in the db, so we have to create it.
            return rsgGalleryManager::_getRootGallery();
        }
        $row = $row[0];

        return new rsgGallery( $row );
    }

    /**
     * return the top level gallery
     * this is a little interesting, because the top level gallery is a pseudo gallery, but we need to create some 
     * usefull values so that it can be used as a real gallery.
     * @todo possibly have the top level gallery be a real gallery in the db.  this obviously needs to be discussed more.
     * @todo are these good defaults?  not sure....
     * @param rsgGallery object
     */
    function _getRootGallery(){
        global $rsgConfig;

        return new rsgGallery( array(
            'id'=>0,
            'parent'=>null,
            'name'=>'',
            'description'=>$rsgConfig->get("intro_text"),
            'published'=>1,
            'checked_out'=>false,
            'checked_out_time'=>null,
            'ordering'=>0,
            'date'=>'0000-00-00 00:00:00',
            'hits'=>0,
            'params'=>'',
            'user'=>'',
            'uid'=>'',
            'allowed'=>'',
            'thumb_id'=>''
        ));
    }
    
    /**
     * returns an array of galleries from an array of IDs
     * @param id of the gallery
     */
    function _getArray( $cid ){
        $galleries = array();
        
        foreach( $cid as $gid ){
            $galleries[] = rsgGalleryManager::_get( $gid );
        }
        return $galleries;
    }

    /**
     * recursively deletes a tree of galleries
     * @param id of the gallery
     * @todo this is a quick hack.  galleryUtils and imgUtils need to be reorganized; and a rsgImage class created to do this proper
     */
    function _deleteTree( $galleries ){
        global $database, $rsgAccess;
        
        foreach( $galleries as $gallery ){
            rsgGalleryManager::_deleteTree( $gallery->kids() );

            // delete images in gallery
            foreach( $gallery->items() as $item ){
                imgUtils::deleteImage( galleryUtils::getFileNameFromId( $item['id'] ));
            }

            // delete gallery
            $id = $gallery->get('id');
            if( !is_numeric( $id )) return false;

            $query = "DELETE FROM #__rsgallery2_galleries WHERE id = $id";
            echo "<br>deleting gallery $id";

            $database->setQuery( $query );
            if (!$database->query())
                echo $database->error();

            // Delete permissions here
            $rsgAccess->deletePermissions( $id );
        }
    }
}

/**
* Class representing a gallery.
* Don't access variables directly, use get(), kids() or items()
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgGallery{
//     variables from the db table
	/** @var int Primary key */
	var $id = null;
	/** @var int id of parent */
	var $parent = null;
	/** @var string name of gallery*/
	var $name = null;
	/** @var string */
	var $description = null;
	/** @var boolean */
	var $published = null;
	/** @var int */
	var $checked_out        = null;
	/** @var datetime */
	var $checked_out_time   = null;
	/** @var int */
	var $ordering = null;
	/** @var datetime */
	var $date = null;
	/** @var int */
	var $hits = null;
	/** @var string */
	var $params = null;
	/** @var int */
	var $user = null;
	/** @var int */
	var $uid = null;
	/** @var string */
	var $allowed = null;
	/** @var int */
	var $thumb_id = null;

//     variables for sub galleries and image items
	/** @var array representing child galleries.  generated on demand!  use kids() */
	var $kids = null;
	/** @var array representing images.  generated on demand!  use items() */
	var $items = null;

//     misc other generated variables
	/** @var the thumbnail object representing the gallery.  generated on demand!  use thumb() */
	var $thumb = null;
	/** @var string containing the html image code */
	var $thumbHTML = null;
	/** @var url to go to this gallery from the frontend */
	var $url = null;
	var $status = null;

    function rsgGallery( $row ){
		global $Itemid;
	
		// bind db row to this object
		foreach ( $row as $k=>$v ){
			$this->$k = $row[$k];
		}

		$this->thumb();

		// generate some vars
		$this->url 				= "index.php?option=com_rsgallery2&Itemid=$Itemid&catid=".$this->get('id');
		//Write status icons
		$this->status 			= galleryUtils::writeGalleryStatus( $this->get('id'));
		//Write owner name
		$this->owner 			= galleryUtils::genericGetUserName( $this->get('uid') );

        //Write HTML for thumbnail
		$this->thumbHTML 		= "<a href=\"".$this->url."\">".galleryUtils::getThumb( $this->get('id'),0,0,"" )."</a>";
		
		//Write gallery name
		//TODO: sef is only included for frontend stuff.  perhaps this shouldn't be here?....
		if( function_exists( 'sefRelToAbs' ) )
			$this->galleryName = "<a class='rsg2-galleryList-title' href=\"".sefRelToAbs($this->url)."\">".htmlspecialchars(stripslashes($this->get('name')), ENT_QUOTES)."</a>";
		else
			$this->galleryName = htmlspecialchars( stripslashes( $this->get( 'name' )));

		//Write description
		$this->description  	= ampReplace($this->get('description'));
	}
	
	/**
	* returns an array of sub galleries in this gallery
	*/
	function kids(){
		// check if we need to generate the list
		if( $this->kids == null ){
			$this->kids = rsgGalleryManager::getList( $this->get('id') );
		}
		
		return $this->kids;
	}
	
	/**
	*  returns an array of images in this gallery
	*/
	function items(){
		// check if we need to generate the list
		if( $this->items == null ){
			global $database;
			$database->setQuery( "SELECT * FROM #__rsgallery2_files".
				" WHERE gallery_id='". $this->get('id') ."'".
				" ORDER BY ordering ASC" );
	
			// there is no rsgImage object yet, so we'll just load the row arrays directly
			$this->items = $database->loadAssocList();
		}
		return $this->items;
	}
	
	/**
	*  returns basic information for this gallery
	*/
	function get( $key ){
		return $this->$key;
	}
	
	/**
	*  returns item by it's db id
	*/
	function getItem( $id ){
		foreach( $this->items() as $item ){
			if( $item['id'] == $id )
				return $item;
		}
	}
	
	/**
	*  returns the thumbnail representing this gallery
	*/
	function thumb( ){
		// check if we need to find out what it is first
		if( $this->thumb == null ){
			if( $this->thumb_id == 0 ){
				// thumbnail not set, use random
				$items = $this->items();
				if( count( $items ) == 0 )
					return null;

				shuffle( $items );
				$this->thumb = $items[0];
			}
			else{
				$this->thumb = $this->getItem( $this->thumb_id );
			}
		}
		return $this->thumb;
	}
}


/**
* RSGTemplate installer, based on the Joomla installer
* @package RSGallery2
* @author Ronald Smit <ronald.smit@rsdev.nl>
*/
class rsgInstallerTemplate extends mosInstaller {
	/**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path,$database;

		if (!$this->preInstallCheck( $p_fromdir, 'template' )) {
			return false;
		}

		$xmlDoc 	=& $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName($e->getText());
		$this->elementDir(mosPathName( JPATH_RSGALLERY2_SITE . DS . "tpl" . DS . strtolower( str_replace( " ","_",$this->elementName() ) ) ) );
		if (!file_exists( $this->elementDir() ) && !mosMakePath( $this->elementDir() )) {
			$this->setError(1, 'Failed to create directory "' . $this->elementDir() . '"' );
			return false;
		}

		if ($this->parseFiles( 'files' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'images' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'css' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'media' ) === false) {
			return false;
		}
		if ($e = &$mosinstall->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}
		//Copy XML file to template directory
		return $this->copySetupFile('front');
	}
	/**
	* Custom install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function uninstall( $id, $option, $client=0 ) {
		global $database, $mosConfig_absolute_path;
		echo $id;
		if ($id == "default") {
			echo "Niet gelukt. ($id)";
			HTML_RSGallery::showInstallMessage( 'Cannot delete default template', 'Uninstall -  error',
				$this->returnTo( $option ) );
		} else {
			// Delete directories
			$path = JPATH_RSGALLERY2_SITE . '/tpl/' . $id;
	
			$id = str_replace( '..', '', $id );
			if (trim( $id )) {
				if (is_dir( $path )) {
					return deldir( mosPathName( $path ) );
				} else {
					HTML_RSGallery::showInstallMessage( 'Directory does not exist, cannot remove files', 'Uninstall -  error',
						$this->returnTo( $option ) );
				}
			} else {
				HTML_RSGallery::showInstallMessage( 'Template id is empty, cannot remove files', 'Uninstall -  error',
					$this->returnTo( $option ) );
				exit();
			}			
		}
	}
	/**
	* return to method
	*/
	function returnTo( $option ) {
		return "index2.php?option=com_rsgallery2&task=templates";
	}
}
?>