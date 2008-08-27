<?php
/**
* Tag class
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
**/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* Category database table class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgTagsItem extends mosDBTable {
    /** @var int Primary key */
    var $id = null;
    var $name = null;
    var $description = null;
    var $published = null;
    var $checked_out        = null;
    var $checked_out_time   = null;
    var $ordering = null;
    var $date_added = null;
    var $params = null;
    var $user = null;
    var $uid = null;
    var $enabled = null;


    /**
    * @param database A database connector object
    */
    function rsgTagsItem( &$db ) {
        $this->mosDBTable( '#__rsgallery2_tags', 'id', $db );
    }
    /** 
     * overloaded check function 
     */
    function check() {
        // filter malicious code
        $ignoreList = array( 'params','description' );
        $this->filter( $ignoreList );

        /** check for valid name */
        if (trim( $this->name ) == '') {
            $this->_error = _RSGALLERY_CATNAME;
            return false;
        }

        /** check for existing name */
        $query = "SELECT id"
        . "\n FROM #__rsgallery2_tags"
        . "\n WHERE name = '$this->name'"
        ;
        $this->_db->setQuery( $query );

        $xid = intval( $this->_db->loadResult() );
        if ($xid && $xid != intval( $this->id )) {
            $this->_error = _RSGALLERY_TAGS_EXIST_ERROR;
            return false;
        }
        return true;
    }
}


?>