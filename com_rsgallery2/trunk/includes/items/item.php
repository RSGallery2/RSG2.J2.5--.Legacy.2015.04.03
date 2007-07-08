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
class rsgItem extends JObject{
	/**
	 * the general content type
	 */
	var $type = null;
	
	/**
	 * the full mimetype
	 */
	var $mimetype = null;
	
	/**
	 * the parent gallery
	 */
	var $gallery = null;

	/**
	 * rsgResource: thumbnail for this item
	 */
	var $thumb = null;

	/**
	 * @param array a database row
	 */
	function __construct( $type, $mimetype, &$gallery, $row ){
		$this->type = $type;
		$this->mimetype = $mimetype;
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
	
	/**
	 * static class returns the appropriate object for the item
	 * @param rsgGallery of the parent gallery
	 * @param array of the database row
	 * @return the apropriate item object
	 */
	function getCorrectItemObject( &$gallery, $row ){
		// get mime type of file
		$mimetype = MimeTypes::getMimeType( $row['name'] );
		
		// get only the general content type
		$type = explode( '/', $mimetype );
		$type = $type[0];
		
		if( file_exists( JPATH_RSGALLERY2_ADMIN.'/includes/items/'. $type .'.php' )){
			require_once( JPATH_RSGALLERY2_ADMIN.'/includes/items/'. $type .'.php' );
			$itemClass = "rsgItem_$type";
			return new $itemClass( $type, $mimetype, &$gallery, $row );
		}
		else{
			$itemClass = "rsgItem";
			return new $itemClass( $type, $mimetype, &$gallery, $row );
		}
	}
}

/**
* This class represents a file
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgResource extends JObject{
	/**
	 * the unique name to retrieve this resource
	 */
	var $name = null;
	
	function __construct( $name ){
		$this->name = $name;
	}
	
	/**
	 * @return working URL to the resource
	 */
	function url(){
		global $mosConfig_live_site;
		return $mosConfig_live_site . DS . $this->name;
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
		return JPATH_ROOT . DS . $this->name;
	}
}