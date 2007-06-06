<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_photoBox extends rsgDisplay{
	function mainPage(){
		
		echo '<div id="photo-box">';
			$this->showImages( $this->gallery );
		echo '</div>';
		echo '<div class="thumbs">';
		echo '<div>Click Thumbnails to View</div>';
			$this->showThumbs( $this->gallery );
		echo '</div>';
	}
	
	function showThumbs ( $gallery ) {
		$count = 0;
		foreach ($gallery->items() as $item) {
			$thumb = imgUtils::getImgThumb( $item['name'] );
			$image_id = $item['id'];
			?>
			<a href="javascript: select_thumb('<?php echo $image_id ?>', '<?php echo $image_id ?>');"><img src="<?php echo $thumb ?>" alt="" id="thumb-id-<?php echo $image_id ?>" <?php if(!$count){?>class="selected"<?php } ?> /></a>
			<?php
			$count++;
		}
	}
	
	function showImages ( $gallery ) {
		$count = 0;
		foreach ($gallery->items() as $item) {
//			print '<pre>' .print_r($item,true) . '</pre>';
			$title = $item['title'];
			$name = $item['name'];
			$full_image = imgUtils::getImgDisplay($name);
			$desc = $item['descr'];
			$image_id = $item['id'];
								
			$this->RSGallery2_template_photoBox($title, $name, $full_image, $desc, $image_id, $count);
			$count++;
		}
	}
	
	function RSGallery2_template_photoBox($title, $name, $image, $desc, $id, $count) {
		?>
		<div id="photo-id-<?php echo $id?>" class="large<?php if(!$count){?>selected<?php } ?>">
			<img src="<?php echo $image?>" alt="" /><br />
			<p><?php echo $desc ?></p>
		</div>
		<?
	}
}
?>