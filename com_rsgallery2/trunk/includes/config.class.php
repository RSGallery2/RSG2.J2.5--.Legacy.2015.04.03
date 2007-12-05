<?php
/**
 * Class handles all configuration parameters for RSGallery2
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
 * Generic Config class
 * @package RSGallery2
 */
class rsgConfig {

	//	General
	var $intro_text 		= 'RSGallery2 alpha release.';
	var $version    		= 'depreciated';  // this is set and loaded from includes/version.rsgallery2.php
	var $debug      		= false;
	var $allowedFileTypes 	= "jpg,jpeg,gif,png";
	var $hideRoot = false; // hide the root gallery and it's listing.  this is to publish multiple independant galleries.

	// new image paths, use imgUtils::getImg*() instead of calling these directly
	var $imgPath_thumb 		= '/images/rsgallery/thumb';
	var $imgPath_display 	= '/images/rsgallery/display';
	var $imgPath_original 	= '/images/rsgallery/original';
    var $createImgDirs 		= false;

	// graphics manipulation
	var $graphicsLib        = 'gd2';   // imagemagick, netbpm, gd1, gd2
	var $keepOriginalImage	= true;
	var $jpegQuality        = '85';
	var $image_width		= '400';  //todo: rename to imgWidth_display
	var $resize_portrait_by_height = true;
    var $thumb_style        = 1; //0 = proportional, 1 = square
	var $thumb_width        = '80';  //todo: rename to imgWidth_thumb
	var $imageMagick_path	= '';
	var $netpbm_path		= '';
	var $ftp_path			= '';

	// front display
    var $display_thumbs_style = 'table'; // float, table, magic
    var $display_thumbs_floatDirection = 'left'; // left, right
	var $display_thumbs_colsPerPage	= 3;
    var $display_thumbs_maxPerPage = 9;
    var $display_thumbs_showImgName = true;
	var $display_img_dynamicResize	= 5;
    var $displayRandom	            = 1;
	var $displayLatest	            = 1;
	var $displayBranding			= true;
	var $displayDesc		        = 1;
    var $displayHits                = 0;
	var $displayVoting	            = 1;
	var $displayComments	        = 1;
	var $displayEXIF		        = 1;
	var $displaySlideshow 			= 1;
	var $current_slideshow			= "slideshow_parth";
	var $displayDownload			= true;
	var $displayPopup				= 1; //0 = Off; 1 = Normal; 2 = Fancy;
	var $displayStatus				= 1;
	var $dispLimitbox				= 1; //0 = never; 1 = If more galleries then limit; 2 = always
	var $galcountNrs				= 5;
	var $template					= 'semantic';
	var $showGalleryOwner			= 1;
	var $showGallerySize			= 1;
	var $showGalleryDate			= 1;
	var $exifTags					= 'FileName|FileDateTime|resolution';
	
	var $filter_order				= 'ordering';
	var $filter_order_Dir			= 'ASC';
	
	/* var $gallery_sort_order			= 'order_id';*/ //'order_id' = ordering by DB ordering field; 'desc' = Last uploaded first; 'asc' = Last uploaded last

    // user uploads
	var $uu_enabled         = 0;
	//var $uu_registeredOnly  = 1;
	var $uu_createCat       = 0;
	var $uu_maxCat          = 5;
	var $uu_maxImages       = 50;
	var $acl_enabled		= 0;
	var $show_mygalleries	= 0;
    
    // watermarking
    var $watermark           = 0;
    var $watermark_type		 = "text"; //Values are text or image
    var $watermark_text      = "(c) 2007 - RSGallery2";
    var $watermark_image	 = "watermark.png";
    var $watermark_angle     = 0;
    var $watermark_position  = 5;
    var $watermark_font_size = 20;
    var $watermark_font		 = "arial.ttf";
    var $watermark_transparency = 50;
    
    // Commenting system
    var $comment						= 1;
    var $comment_security				= 1;
    var $comment_once		 			= 0;
    var $comment_allowed_public			= 1;
    
    //Voting system
    var $voting					= 1;
    var $voting_registered_only	= 1;
    var $voting_once			= 1;
    var $cookie_prefix			= "rsgvoting_";

	// private vars for internal use
	var $_configTable = '#__rsgallery2_config';

    /**
     * constructor
     * @param bool true loads config from db, false will retain defaults
     * @todo: fix why we can't get the version from $rsgVersion!
     */
    function rsgConfig( $loadFromDB = true ){
        // get version
        // global $rsgVersion;
        // $this->version = $rsgVersion->getVersionOnly();
        $this->version = '1.14.1';

        if( $loadFromDB )
            $this->_loadConfig();
    }

	/**
	 * @return array An array of the public vars in the class
	 */
	function getPublicVars() {
		$public = array();
		$vars = array_keys( get_class_vars( get_class( $this ) ) );
		sort( $vars );
		foreach ($vars as $v) {
			if ($v{0} != '_') {
				$public[] = $v;
			}
		}
		return $public;
	}

	/**
	 *	binds a named array/hash to this object
	 *	@param array $hash named array
	 *	@return null|string	null is operation was satisfactory, otherwise returns an error
	 */
	function _bind( $array, $ignore='' ) {
		if (!is_array( $array )) {
			$this->_error = strtolower(get_class( $this )).'::bind failed.';
			return false;
		} else {
			return mosBindArrayToObject( $array, $this, $ignore );
		}
	}

	/**
	 * Writes the configuration file line for a particular variable
	 * @return string
	 */
	function getVarText() {
		$txt = '';
		$vars = $this->getPublicVars();
		foreach ($vars as $v) {
			$k = str_replace( 'config_', 'mosConfig_', $v );
			$txt .= "\$$k = '" . addslashes( $this->$v ) . "';\n";
		}
		return $txt;
	}

	/**
	 * Binds the global configuration variables to the class properties
	 */
	function _loadConfig() {
		global $database;

		$query = "SELECT * FROM " . $this->_configTable;
		$database->setQuery($query);

		if( !$database->query() ){
			// database doesn't exist, use defaults.
			return;
		}

		$vars = $database->loadAssocList();

		foreach ($vars as $v) {
			$this->$v['name'] = $v['value'];
		}
	}

	/**
	 * takes an array, binds it to the class and saves it to the database
	 * @param array of settings
	 * @return false if fail
	 */
	function saveConfig( $config=null ) {
		global $database;
		
		//bind array to class
		if( $config !== null){
			$this->_bind($config);
			if(array_key_exists('exifTags', $config))
				$this->exifTags = implode("|", $config['exifTags']);
		}
		
		$vars = $this->getPublicVars();
		foreach ( $vars as $name ){
			//Checks if the value exists and overrides it if present, inserting if not
			//can seem a bit too much but since config is not gonna be changed often...
			$query = "SELECT * FROM " . $this->_configTable ." WHERE name='$name'";
			$database->setQuery( $query );
			if(!$database->query()){
				echo $database->getErrorMsg();
				return false;
			}
			$isCreated = $database->getNumRows();
			if ($isCreated==1) {
				if ($name == 'intro_text')
					$this->$name = addslashes($this->$name);
				$query = "UPDATE " . $this->_configTable . " SET value='".$this->$name."' WHERE name='$name'";
			}	
			else {
				$query = "INSERT INTO " . $this->_configTable . "  VALUES ('', '$name', '".$this->$name."')";
			}
				
			
            $database->setQuery( $query );
			if(!$database->query()){
				echo $database->getErrorMsg();
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string name of variable
	 * @return the requested variable
	 */
	function get($varname){
		return $this->$varname;
	}
    
    /**
     * @param string name of variable
     * @param var new value
     */
    function set( $varname, $value ){
        $this->$varname = $value;
    }
    
    /**
     * @param string name of variable
     * @return the default value of requested variable
     */
    function getDefault( $varname ){
        $defaultConfig = new rsgConfig( false );
        return $defaultConfig->get( $varname );
    }
}
?>
