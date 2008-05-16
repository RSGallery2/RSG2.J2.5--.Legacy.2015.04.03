<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_moopop extends rsgDisplay{
	function mainPage(){
		$this->showThumbs( $this->gallery );
		
		foreach( $this->gallery->kids() as $kid ){
			$this->showThumbs( $kid );
		}
	}
	
	function showThumbs( $g ){
		$items = $g->items();
		$keys = array_rand( $items, 7 );
		foreach( $keys as $key ){
			$item = $items[$key];
			$thumb = $item->thumb();
			$thumb = $thumb->url();

			$display = $item->display();
			$display = $display->url();
			
			$params = $item->parameters();
			$link = $params->get('link');
			$title = $item->title;
			$descr = $item->descr;

			echo <<<EOD
<a href="$link" target='_blank' class='rsg2-moopop-thumb' title='$title' rel='$display' >
	<img src='$thumb' />
</a>

EOD;
		}
	}
}
