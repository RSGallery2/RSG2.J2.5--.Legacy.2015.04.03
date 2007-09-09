<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

/**
 * Template class for RSGallery2
 * @package RSGallery2
 */
class rsgDisplay_overview extends rsgDisplay{
	function mainPage(){
		$page = rsgInstance::getVar( 'page', '' );

		switch( $page ){
			case 'viewChangelog':
				$this->viewChangelog();
			break;
			default:
				$this->showGalleries();
		}
	}
	
	/**
		shows thumbnails as a flat list of galleries
		no pagination
	**/
	function showGalleries( $gallery = null ){
		if( ! $gallery )
			$gallery = rsgInstance::getGallery();
		
		// show the current gallery first
		$this->_showGallery( $gallery );
		
		// show each of the sub galleries
		foreach( $gallery->kids() as $kid )
			$this->showGalleries( $kid );
	}

	function _showGallery( $gallery ){
		$items = $gallery->items();
		
		// dont show if there is nothing to display
		if( ! count( $items ) )
			return;
		
		// assign variables
		$this->gallery = $gallery;
		$this->items = $items;

		$this->display( 'gallery.php' );
		
	}
}
