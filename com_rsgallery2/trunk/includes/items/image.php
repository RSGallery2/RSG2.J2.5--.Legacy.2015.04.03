<?php
/**
* Item class
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 rsgallery2.net
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

/**
* The generic item class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgItem_image extends rsgItem{
	/**
	 * rsgResource: display image for this item
	 */
	$display = null;

	/**
	 * rsgResource: the original image
	 */
	$original = null;

	rsgItem_image( &$gallery, $row){
		$parent->rsgItem( &$gallery, $row );
		
		$this->_determineResources();
	}
	
	/**
	 * @return the thumbnail
	 */
	function thumb(){
		return $this->thumb;
	}
	
	/**
	 * @return the display image
	 */
	function display(){
		return $this->display;
	}
	
	/**
	 * @return the original image
	 */
	function original(){
		return $this->original;
	}

	_determineResources(){
		$thumb = $rsgConfig->get('imgPath_thumb') . DS . imgUtils::getImgNameThumb( $name );
		$display = $rsgConfig->get('imgPath_display') . DS . imgUtils::getImgNameDisplay( $name );
		$original = $rsgConfig->get('imgPath_original') . DS . $name;
		
		if( file_exists( JPATH_ROOT . $original )){
			// original image exists
			$this->original = new rsgResource( $original );
		}
		else{
			// original image does not exist, therefore display and thumb MUST exist
			$this->display = new rsgResource( $display );
			$this->thumb = new rsgResource( $thumb );
			return;
		}
		
		// if original was smaller than thumb or display those won't exist
		if( !file_exists( JPATH_ROOT . $thumb ){
			$this->thumb =& $this->original;
			$this->display =& $this->original;
		}
		elseif( !file_exists( JPATH_ROOT . $display ){
			$this->thumb = new rsgResource( $thumb );
			$this->display =& $this->original;
		}
		else{
			$this->thumb = new rsgResource( $thumb );
			$this->display = new rsgResource( $display );
		}
	}
}
