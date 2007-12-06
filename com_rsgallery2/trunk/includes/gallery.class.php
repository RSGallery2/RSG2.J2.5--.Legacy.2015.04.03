<?php
/**
* This file contains the class representing a gallery.
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 rsgallery2.net
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Access Denied.' );

/**
* Class representing a gallery.
* Don't access variables directly, use get(), kids() or items()
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgGallery extends JObject{
//     variables from the db table
	/** @var array the entire table row */
	var $row = null;
	
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
	/** @var array representing images.  generated on demand!  use itemRows() */
	var $_itemRows = null;
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

	var $_itemCount = null;

    function __construct( $row ){
		global $Itemid;

		$this->row = $row;

		// bind db row to this object
		foreach ( $row as $k=>$v ){
			$this->$k = $row[$k];
		}

		$this->thumb();

		//Write status icons
		$this->status = galleryUtils::writeGalleryStatus( $this );
		//Write owner name
		$this->owner = galleryUtils::genericGetUserName( $this->get('uid') );

		//Write gallery name
		$this->url = JRoute::_("index.php?option=com_rsgallery2&Itemid=$Itemid&gid=".$this->get('id'));
		$this->galleryName = htmlspecialchars( stripslashes( $this->get( 'name' )));
		
		//Write HTML for thumbnail
		$this->thumbHTML = "<a href=\"".$this->url."\">".galleryUtils::getThumb( $this->get('id'),0,0,"" )."</a>";
		
		//Write description
		$this->description = ampReplace($this->get('description'));
	}
	
	/**
	 * @return true if there is an image within a week old
	 * @todo rewrite the sql to use better date features
	 */
	function hasNewImages(){
		global $database;
		$lastweek  = mktime (0, 0, 0, date("m"),    date("d") - 7, date("Y"));
		$lastweek = date("Y-m-d H:m:s",$lastweek);
		$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE date >= '$lastweek' AND gallery_id = '{$this->id}'");
		$database->query();
		return (bool) $database->getNumRows();
	}
	
	/**
	* returns the total number of items in this gallery.
	*/
	function itemCount(){
		if( $this->_itemCount === null ){
			global $database;
			
			$gid = $this->id;
			$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id='$gid' AND published = '1'");
			$this->_itemCount = $database->loadResult();
		}
		return $this->_itemCount;
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
	* returns the parent gallery item.
	*/
	function parent(){
		return rsgGalleryManager::get( $this->parent );
	}
	
	/**
	*  returns an array of item db rows
	* @todo image listing should be based on what the current visitor can see (owner, administrator, un/published, etc.)
	*/
	function itemRows( ){
		
		if( $this->_itemRows === null ){

			global $rsgConfig;
			global $database;
			$my =& JFactory::getUser();
			
			
			$filter_order = rsgInstance::getWord( 'filter_order',  $rsgConfig->get("filter_order") );
			$filter_order_Dir = rsgInstance::getWord( 'filter_order_Dir', $rsgConfig->get("filter_order_Dir"));
	
			$where = ' WHERE gallery_id = '. $this->get('id');

			if($my->get('gid') != 25)
				$where .= ' AND published = 1 ';
			
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	
			$query = ' SELECT * FROM #__rsgallery2_files '
				. $where
				. $orderby;

			// limit handling was borked but I had this fixed.  we can use it again....
			$database->setQuery( $query);//, $limitstart, $limit );

			$this->_itemRows = $database->loadAssocList( 'id' );
		}
		return $this->_itemRows;
	}

	/**
	*  returns an array of all item objects
	*/
	function items( ){
		if( $this->items === null ){
			$this->items = array();
			$rows = $this->itemRows( );

			foreach( $rows as $row ){
				$this->items[$row['id']] = rsgItem::getCorrectItemObject( $this, $row );
			}
		}
		return $this->items;
	}


	/**
	* returns an array of item objects viewable with the current pagination
	*/
	function currentItems(){
		global $rsgConfig;
		if( $this->items === null )
			$this->items();
		
		$length = $rsgConfig->get("display_thumbs_maxPerPage");

		$current = $this->indexOfItem(rsgInstance::getInt( 'id', 0 ));
		$current = rsgInstance::getInt( 'limitstart', $current );
		
		// calculate page from current position
		$start =  floor($current  / $length) * $length;
		return $this->array_slice_preserve_keys($this->items, $start, $length);
		
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
	function getItem( $id = null ){

		if( $id !== null )
			return $this->items[$id];

		$id = rsgInstance::getInt( 'id', null );
		if( $id !== null )
			return $this->items[$id];
		
		$id = rsgInstance::getInt( 'limitstart', 0 );
		return array_pop(array_slice($this->items, $id, 1));
					

	}
	
	function indexOfItem($id = null){
	
		if( $id === null ){
			$id = rsgInstance::getInt( 'id', null );
			if( $id === null ){
				return 0;
			}
		}
		
		if (!array_key_exists($id, $this->items))
			return 0;

		$keys = array_keys($this->items);
		$index = array_search($id, $keys);
		return $index;
		
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
	
	/**
	 * increases the hit counter for this object
	 * @todo doesn't work right now
	 */
	function hit(){
		$query = "UPDATE #__rsgallery2_galleries SET hits = hits + 1 WHERE id = {$this->id}";
		
		global $database;
		$database->setQuery( $query );
		
		if( !$database->query() ) {
// 			$this->setError( $db->getErrorMsg() );
			return false;
		}
		
		$this->hits++;
	}

	/**
	 * Method to get a pagination object for the the gallery items
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->itemCount(), rsgInstance::getInt( 'limitstart', 0 ), rsgInstance::getInt( 'limit', 0 ) );
		}

		return $this->_pagination;
	}
	
	
	/**
	 * array_slice with preserve_keys for every php version (taken form http://www.php.net/array_slice )
	 *
	 * @param array $array Input array
	 * @param int $offset Start offset
	 * @param int $length Length
	 * @return array
	 */
	function array_slice_preserve_keys($array, $offset, $length = null)
	{
		// PHP >= 5.0.2 is able to do this itself
		if((int)str_replace('.', '', phpversion()) >= 502)
			return(array_slice($array, $offset, $length, true));

		// prepare input variables
		$result = array();
		$i = 0;
		if($offset < 0)
			$offset = count($array) + $offset;
		if($length > 0)
			$endOffset = $offset + $length;
		else if($length < 0)
			$endOffset = count($array) + $length;
		else
			$endOffset = count($array);
	   
		// collect elements
		foreach($array as $key=>$value)
		{
			if($i >= $offset && $i < $endOffset)
				$result[$key] = $value;
			$i++;
		}
	   
		// return
		return($result);
	}

}
