<?php
/**
* category class
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Category database table class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgGalleriesItem extends JTable {
    /** @var int Primary key */
    var $id = null;
    var $parent = 0;
    var $name = null;
	var $alias = null;
    var $description = null;
    var $published = null;
    var $checked_out        = null;
    var $checked_out_time   = null;
    var $ordering = null;
    var $hits = null;
    var $date = null;
    var $params = null;
    var $user = null;
    var $uid = null;
    var $allowed = null;
    var $thumb_id = null;
	var $asset_id = null;

    /**
    * @param database A database connector object
    */
    function rsgGalleriesItem( &$db ) {
		parent::__construct('#__rsgallery2_galleries', 'id', $db );
    }
    /** 
     * overloaded check function 
     */
    function check() {
		$db =& JFactory::getDBO();
        // filter malicious code
        $ignoreList = array( 'params','description' );
		
		$ignore = is_array( $ignoreList );
		
		$filter = & JFilterInput::getInstance();
		foreach ($this->getProperties() as $k => $v)
		{
			if ($ignore && in_array( $k, $ignoreList ) ) {
				continue;
			}
			$this->$k = $filter->clean( $this->$k );
		}
		
        /** check for valid name */
        if (trim( $this->name ) == '') {
            $this->_error = JText::_('COM_RSGALLERY2_GALLERY_NAME');
            return false;
        }

        /** check for existing name */
        $query = "SELECT id"
        . " FROM #__rsgallery2_galleries"
        . " WHERE name = ".$db->quote($this->name)
        . " AND parent = ".(int) $this->parent
        ;
        $this->_db->setQuery( $query );

        $xid = intval( $this->_db->loadResult() );
        if ($xid && $xid != intval( $this->id )) {
            $this->_error = JText::_('COM_RSGALLERY2_THERE_IS_A_GALLERY_ALREADY_WITH_THAT_NAME_PLEASE_TRY_AGAIN');
            return false;
        }
        return true;
    }
	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `com_rsgallery2.gallery.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return      string
	 */
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com_rsgallery2.gallery.'.(int) $this->$k;
	}
	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return      string
	 */
	protected function _getAssetTitle() {
		return $this->name;
	}
	/**
	 * Get the parent asset id for the gallery
	 *
	 * @return      int
	 */
	protected function _getAssetParentId() {
		// Initialise variables
		$assetId = null;
		$db		= $this->getDbo();	//$this is the rsgGalleriesItem object

		//If the current gallery has the Top gallery as a parent, the parent asset is the one of the extension
		if ($this->parent == 0){
			$asset = JTable::getInstance('Asset');
			$asset->loadByName('com_rsgallery2');
			$assetId = $asset->id;
		}
		//else the parent asset is the asset of the parent gallery 
		else {
			// Build the query to get the asset id for the parent category.
			$query	= $db->getQuery(true);
			$query->select('asset_id');
			$query->from('#__rsgallery2_galleries');
			$query->where('id = '.(int) $this->parent);

			// Get the asset id from the database.
			$db->setQuery($query);
			if ($result = $db->loadResult()) {
				$assetId = (int) $result;
			}
		}
	
		// Return the asset id.
		if ($assetId) {
			return $assetId;
		} else {
			return parent::_getAssetParentId($table, $id);
		}
	}
}

/**
 * build the select list for parent item
 * ripped from joomla.php: mosAdminMenus::Parent()
 * @param row current gallery
 * @return HTML Selectlist
 */
function galleryParentSelectList( &$row ) {
    $database =& JFactory::getDBO();

    $id = '';
    if ( $row->id ) {
        $id = " AND id != $row->id";
    }

    // get a list of the menu items
    // excluding the current menu item and its child elements
    //$query = "SELECT *" //MK// [change] [J!1.6 has parent_id instead of parent and title instead of name]
    $query = "SELECT *, parent AS parent_id, name AS title"
    . " FROM #__rsgallery2_galleries"
    . " WHERE published != -2"
    . $id
    . " ORDER BY parent, ordering"
    ;
    $database->setQuery( $query );
    
    $mitems = $database->loadObjectList();

    // establish the hierarchy of the menu
    $children = array();

    if ( $mitems ) {
        // first pass - collect children
        foreach ( $mitems as $v ) {
            $pt     = $v->parent;
            $list   = @$children[$pt] ? $children[$pt] : array();
            array_push( $list, $v );
            $children[$pt] = $list;
        }
    }

    // second pass - get an indent list of the items
    $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );

    // assemble menu items to the array
    $mitems     = array();
    $mitems[]   = JHTMLSelect::option( '0', JText::_('COM_RSGALLERY2_TOP_GALLERY') );

    foreach ( $list as $item ) {
    	$item->treename = str_replace  ( '&#160;&#160;'  ,  '...' ,  $item->treename  ); //MK// [hack] [the original treename holds &#160; as a non breacking space for subgalleries, but JHTMLSelect::option cannot handle that, nor &nbsp;] 
        $mitems[] = JHTMLSelect::option( $item->id, '...'. $item->treename );//MK// [change] [Non-breaking space: HTML name: &nbsp; replaced by '...']
    }
    
//genericlist(array of objects, value of HMTL name attribute, additional HTML attributes for <select> tag, name of objectvarialbe for the option value, name of objectvariable for option text, key that is selected,???,???)
    $output = JHTML::_("select.genericlist", $mitems, 'parent', 'class="inputbox" size="10"', 'value', 'text', $row->parent );

    return $output;
}
/* ACL functions from here */
/**
 * Returns an array with the gallery ID's from the children of the parent
 * @param int Gallery ID from the parent ID to check
 * @return array Array with Gallery ID's from children
 */
function subList( $gallery_id ) {
	$database =& JFactory::getDBO();
	$sql = "SELECT id FROM #__rsgallery2_galleries WHERE parent = '$gallery_id'";
	$database->setQuery( $sql );
	$result = $database->loadResultArray();
	if (count($result) > 0)
		return result;
	else
		return 0;
}
?>