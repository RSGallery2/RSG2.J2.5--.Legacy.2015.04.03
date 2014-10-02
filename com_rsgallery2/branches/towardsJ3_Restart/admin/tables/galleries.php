<?php
/**
* category class
* @version $Id: galleries.class.php 1049 2011-11-08 13:57:16Z mirjam $
* @package RSG2
* @copyright (C) 2005 - 2011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Galleries database table class
* @package RSG2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsg2TableGalleries extends JTable {

    /** @var int Primary key */
/*
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
	var $access = null;
*/
    /**
    * @param database A database connector object
    */
    public function __construct(&$db) {
        parent::__construct('#__rsgallery2_galleries', 'id', $db );
    }

    public function bind ($array, $ignore = '')
    {
        parent::bind($array, $ignore);
    }

    public function store ($updateNulls = false)
    {
        parent::store ($updateNulls);
    }

    /**
 * build the select list for parent item
 * ripped from joomla.php: mosAdminMenus::Parent()
 * @param row current gallery
 * @return HTML Selectlist
 */
    function galleryParentSelectList( &$row )
    {
        $database = JFactory::getDBO();

        $id = '';
        if ( $row->id ) {
            $id = " AND id != $row->id";
        }

        // Get a list of the items
        // [J!1.6 has parent_id instead of parent and title instead of name in menu.treerecurse]
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
        $list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );

        // assemble menu items to the array
        $mitems     = array();
        //Only add Top gallery as a choice is galleries may be created there or if the current parent is the Top gallery
        if ((JFactory::getUser()->authorise('core.create', 'com_rsg2')) OR ($row->parent == 0)) {
            $mitems[]   = JHtml::_('select.option', '0', JText::_('COM_RSG2_TOP_GALLERY') );
        }

        foreach ( $list as $item ) {
            //[hack] [the original treename holds &#160; as a non breacking space for subgalleries, but JHtmlSelect::option cannot handle that, nor &nbsp;]
            $item->treename = str_replace  ( '&#160;&#160;'  ,  '...' ,  $item->treename  );
            //Check create permission for each possible parent
            $canCreateInParentGallery = JFactory::getUser()->authorise('core.create', 'com_rsg2.gallery.'.$item->id);
            //Get the allowed parents and the current parent
            if (($canCreateInParentGallery) OR ($row->parent == $item->id)) {
                $mitems[] = JHtml::_('select.option', $item->id, '...'. $item->treename);
            }
        }

        //genericlist(array of objects, value of HMTL name attribute, additional HTML attributes for <select> tag, name of objectvarialbe for the option value, name of objectvariable for option text, key that is selected,???,???)
        $output = JHtml::_("select.genericlist", $mitems, 'parent', 'class="inputbox" size="10"', 'value', 'text', $row->parent );

        return $output;
    }
    /**
     * Returns an array with the gallery ID's from the children of the parent
     * @param int Gallery ID from the parent ID to check
     * @return array Array with Gallery ID's from children
     */
    //old:function subList( $gallery_id ) {
    function GalleryChilds( $gallery_id )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$sql = "SELECT id FROM #__rsgallery2_galleries WHERE parent = '$gallery_id'";
        $query->select('id');
        $query->from('#__rsgallery2_galleries');
        $query->where('parent = ' . $gallery_id);

        $db->setQuery($query);

        $childs = $db->loadObjectList();
    /*
        if (count($result) > 0)
            return result;
        else
            return 0;
    */

        return ($childs);
    }

    function GalleryChildIds( $gallery_id )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //$sql = "SELECT id FROM #__rsgallery2_galleries WHERE parent = '$gallery_id'";
        $query->select('id');
        $query->from('#__rsgallery2_galleries');
        $query->where('parent = '.$gallery_id);

        $db->setQuery($query);

        $childs = $db->loadColumn();
    /*
        if (count($result) > 0)
            return result;
        else
            return 0;
    */
        return ($childs);
    }








}
/* ACL functions from here */
?>


