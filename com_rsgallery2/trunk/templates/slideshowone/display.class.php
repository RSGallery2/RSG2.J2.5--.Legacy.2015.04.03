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
 * @author Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_slideshowone extends rsgDisplay{

	function showSlideShow(){
		global $rsgConfig, $mosConfig_live_site;
		
		$gallery = rsgGalleryManager::get();
		
		$k = 0;
		$text = "";
		foreach ($gallery->items() as $item){
			if( $item->type != 'image' ) return;

			$display = $item->display();

			$text .= "SLIDES[".$k."] = ['". $display->url() ."', '{$item->title}'];\n";
			$k++;
		}
		$this->slides = $text;
		$this->display('slideshow.php');
	}
}