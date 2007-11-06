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
	
	function getGalleryThumbHTML( $g ){
		$gid = $g->id;
		if( $gid == 0 )
			return;
		
		$galleryThumb = $g->thumb();
		
		if( !$galleryThumb )
			return;
		
		if( $galleryThumb->type != 'image' )
			return;
		
		$galleryDisplay = $galleryThumb->display();
		$galleryThumb = $galleryThumb->thumb();
		$galleryThumbURL = $galleryThumb->url();

		return <<<EOD
<a class='galleryThumb' target='_blank' href='/index2.php?option=com_rsgallery2&rsgTemplate=slideshowone&gid=$gid'>
	<img src='$galleryThumbURL' />
	<div class='slideshowlink'>slideshow</div>
</a>
EOD;
	}
	
	function printGallery( $g ){
		$name = stripslashes($g->name);
		$description = $g->description;
		$gid = $g->id;
		
		$galleryThumb = $this->getGalleryThumbHTML( $g );
		echo <<<EOD
<div>
<h2>$name</h2>
<div class='descr'>$description</div>
$galleryThumb
<ul class='thumbs'>
EOD;

		if( $g->itemCount() > 1 )
		foreach( $g->items() as $item ):
			if( $item->type != 'image' )
				continue;
			$thumb = $item->thumb();
			$thumb = $thumb->url();
			
			$display = $item->display();
			$display = $display->url();
			
			$original = $item->original();
			$original = $original->url();
			
			$title = $item->title;
			$descr = $item->descr;
			// note that we don't specify an alt parameter in the following image.  it interferes with overlib popup thumbnail
			// if you want to add the alt param, here it is: alt='$name'
			echo <<<EOD
<li>
	<a href="$display" rel="lightbox[$gid]" title='$title' onmouseover="showInfo('$title', '$title', '$title', '$thumb')" onmouseout="return nd();">
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
