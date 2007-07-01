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
class rsgItem_audio extends rsgItem{
	/**
	 * rsgResource: the original image
	 */
	var $original = null;

	function __construct( &$gallery, $row){
		parent::__construct( &$gallery, $row );
		
		$this->_determineResources();
	}
	
	/**
	 * @return the thumbnail
	 * @todo: we need to return a nice generic audio thumbnail
	 */
	function thumb(){
		return $this->thumb;
	}
	
	/**
	 * @return the original image
	 */
	function original(){
		return $this->original;
	}
}