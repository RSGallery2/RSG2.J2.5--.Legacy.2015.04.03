<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_superClean extends rsgDisplay{
	function mainPage(){
		echo '<div id="rsg2">';
		$this->showThumbs( $this->gallery );
		
		foreach( $this->gallery->kids() as $kid ){
			$this->showThumbs( $kid );
		}
		echo '</div>';
	}
	
	function showThumbs( $g ){
		foreach( $g->itemRows() as $item ){
			$thumb = imgUtils::getImgThumb( $item['name'] );
			$original = imgUtils::getImgOriginal( $item['name'] );
			$name = $item['name'];
			$descr = $item['descr'];
			
			echo <<<EOD
<a href="$original" rel="lightbox" title='$name<br/>$descr' onmouseover="showInfo('$name', '$name', '$name', '$thumb')" onmouseout="return nd();">
	<img src='$thumb' />
</a>
EOD;
		}
	}
}
