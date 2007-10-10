<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_microMacro extends rsgDisplay{
	function mainPage(){
		$this->printGallery( $this->gallery );
	}
	
	function printGallery( $g ){
		$name = $g->name;
		$description = $g->description;
		
		$galleryThumb = $g->thumb();
		if( $galleryThumb ){
			$galleryThumb = $galleryThumb->thumb();
			$galleryThumb = "<img src='".$galleryThumb->url()."' class='galleryThumb' />";
		}
		if( $g->id == 0 )
			$galleryThumb = '';
		
		echo <<<EOD
<div>
<h2>$name</h2>
<div class='descr'>$description</div>
$galleryThumb
<ul class='thumbs'>
EOD;
		foreach( $g->items() as $item ):
			$thumb = $item->thumb();
			$thumb = $thumb->url();
			
			$original = $item->original();
			$original = $original->url();
			
			$title = $item->title;
			$descr = $item->descr;
			// note that we don't specify an alt parameter in the following image.  it interferes with overlib popup thumbnail
			// if you want to add the alt param, here it is: alt='$name'
			echo <<<EOD
<li>
	<a href="$original" rel="lightbox" title='$title<br/>$descr' onmouseover="showInfo('$title', '$title', '$title', '$thumb')" onmouseout="return nd();">
		<img src='$thumb' width='20' height='20' />
	</a>
</li>
EOD;
		endforeach;
		echo <<<EOD
</ul>
<div class='clr'>&nbsp;</div>
<ul>
EOD;
		foreach( $g->kids() as $kid ):
			echo '<li>';
			$this->printGallery( $kid );
			echo '</li>';
		endforeach;
		echo <<<EOD
</ul>
</div>
EOD;
	}
}
