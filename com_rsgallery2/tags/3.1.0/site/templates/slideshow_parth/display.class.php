<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2011 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted Access' );

/**
 * Slideshow class for RSGallery2
 * Based on Smoothgallery from Johndesign.net
 * @package RSGallery2
 * @author Ronald Smit <ronald.smit@rsdev.nl>, based on contribution by Parth <parth.lawate@tekdi.net>
 */
class rsgDisplay_slideshow_parth extends rsgDisplay{
	var $maxSlideshowHeight;
	var $maxSlideshowWidth;

	function showSlideShow(){
		global $rsgConfig;
		
		$gallery = rsgGalleryManager::get();
		
		// show nothing if there are no items
		if( ! $gallery->itemCount() )
			return;
		
		$k = 0;
		$maxSlideshowHeight = 0;
		$maxSlideshowWidth = 0;
		$text = "";
		foreach ($gallery->items() as $item){
			if( $item->type != 'image' ) return;

			$display = $item->display();
			$thumb = $item->thumb();
			
			//Get maximum height/width of display images
			$imgSizes = getimagesize($display->filePath());
			if ($this->maxSlideshowWidth < $imgSizes[0]){
				$this->maxSlideshowWidth = $imgSizes[0];
			}
			if ($this->maxSlideshowHeight < $imgSizes[1]){
				$this->maxSlideshowHeight = $imgSizes[1];
			}

			//The subtitleSelector for jd.gallery.js is p. This interferes with any
			//p-tags in the item desciption. Changing p tag to div.descr tag works for Firefox
			//but not for IE (tested IE7). So removing p tags from item description:
			$search[] = '<p>';
			$search[] = '</p>';
			$replace = ' ';
			$item->descr = str_replace($search, $replace, $item->descr);
			$openImageLink = 'index.php?option=com_rsgallery2&page=inline&Itemid='.JRequest::getVar('Itemid').'&id='.$item->id;
			
			$text .= "<div class=\"imageElement\">" .
					"<h3>$item->title</h3>" .
					"<p>$item->descr</p>" .
					"<a href=\"$openImageLink\" title=\"open image\" class=\"open\"></a>" .
					"<img src=\"".$display->url()."\" class=\"full\" />" .
					"<img src=\"".$thumb->url()."\" class=\"thumbnail\" />" .
					"</div>";
			$k++;
		}
		$this->slides = $text;
		$this->galleryname = $gallery->name;
		$this->gid = $gallery->id;
		
		$this->display('slideshow.php');
	}
}