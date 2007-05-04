<?php
/**
* This class handles version management for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
 * Version information class, based on the Joomla version class
 * @package RSGallery2
 */
class rsgalleryVersion {
    /** @var string Product */
    var $PRODUCT    = 'RSGallery2';
    /** @var int Main Release Level */
    var $RELEASE    = '1.12';
    /** @var string Development Status */
    var $DEV_STATUS = 'Alpha';
    /** @var int Sub Release Level */
    var $DEV_LEVEL  = '2';
    /** @var int build Number */
    var $BUILD      = 'SVN: $GlobalRev$';
    /** @var string Codename */
    var $CODENAME   = '';
    /** @var string Date */
    var $RELDATE    = '27 Mar 2007';
    /** @var string Time */
    var $RELTIME    = '08:10';
    /** @var string Timezone */
    var $RELTZ      = 'GMT';
    /** @var string Copyright Text */
    var $COPYRIGHT  = '&copy; 2005 - 2007 <strong><a class="rsg2-footer" href="http://rsgallery2.net">RSGallery2</a></strong>. All rights reserved.';
    /** @var string URL */
    var $URL        = '<strong><a class="rsg2-footer" href="http://rsgallery2.net">RSGallery2</a></strong>';
    /** @var string Whether site is a production = 1 or demo site = 0: 1 is default */
    var $SITE       = 1;
    /** @var string Whether site has restricted functionality mostly used for demo sites: 0 is default */
    var $RESTRICT   = 0;
    /** @var string Whether site is still in development phase (disables checks for /installation folder) - should be set to 0 for package release: 0 is default */
    var $SVN        = 0;

    /**
     * @return string Long format version
     */
    function getLongVersion() {
        return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' '
            . $this->DEV_STATUS
            .' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
            . $this->RELTIME .' '. $this->RELTZ;
    }

    /**
     * @return string Short version format
     */
    function getShortVersion() {                                               
        return $this->PRODUCT . ' ' . $this->RELEASE .'.'. $this->DEV_LEVEL .' '.$this->DEV_STATUS . ' - '.$this->BUILD.'<br />'.$this->COPYRIGHT;
    }

    /**
     * @return string PHP standardized version format
     */
    function getVersionOnly() {
        return $this->RELEASE .'.'. $this->DEV_LEVEL;
    }
    
    /**
     * checks if checked version is lower, equal or higher that the current version
     * @return int -1 (lower), 0 (equal) or 1 (higher)
     */
    function checkVersion($version) {
        $check = version_compare($version, $this->RELEASE .'.'. $this->DEV_LEVEL);
        return $check;
    }
	//return svn number
	function getSVNonly() {
		return $this->BUILD;
	}
}
?>
