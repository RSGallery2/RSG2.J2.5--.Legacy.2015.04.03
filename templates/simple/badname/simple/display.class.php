<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_simple extends rsgDisplay{
	function mainPage(){
		if( $this->gallery->kids() ){
			$this->display( 'subgalleries.php' );
		}
		else{
			// no sub galleries, show thumbnails
			$this->display( 'thumbs.php' );
		}
	}
}
