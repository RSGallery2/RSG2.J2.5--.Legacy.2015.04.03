<?php
/**
* Item class
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 rsgallery2.net
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Access Denied.' );

/**
* The generic item class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgItem{
	/**
	 * the parent gallery
	 */
	$gallery = null;

	/**
	 * rsgResource: thumbnail for this item
	 */
	$thumb = null;

	/**
	 * @param array a database row
	 */
	function rsgItem( &$gallery, $row ){
		$this->gallery =& $gallery;
		
		foreach( $row as $n=>$a )
			$this->$n = $a;
	}
	
	/**
	 * increases the hit counter for this object
	 */
	function hit(){
		$query = "UPDATE #__rsgallery2_files SET hits = hits + 1 WHERE id = {$this->id}";
		
		$db = &JFactory::getDBO();
		$db->setQuery( $query );
		
		if( !$db->query() ) {
			$this->setError( $db->getErrorMsg() );
			return false;
		}
		
		$this->hits++;
	}
}

/**
* This class represents a file
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgResource{
	/**
	 * the unique name to retrieve this resource
	 */
	$name = null;
	
	function rsgResource( $name ){
		$this->name = $name;
	}
	
	/**
	 * @return working URL to the resource
	 */
	function url(){
		global $mosConfig_live_site;
		return $mosConfig_live_site . DS . rawurlencode($name);
	}
	
	/**
	 * @return the mime type of the file 
	 */
	function mimeType(){
	}
	
	/**
	 * @return the absolute local file path
	 */
	function filePath(){
		return JPATH_ROOT . DS . rawurlencode($name);
	}
}