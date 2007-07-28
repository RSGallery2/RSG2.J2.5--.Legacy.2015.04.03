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
		foreach( $this->gallery->items() as $item ) {
			if( is_a( $item, 'rsgItem_audio' ) ) {
				echo 'This gallery currently only supports images';
				return;
			}
		}
		echo '<div id="photo-box">';
			$this->showImages();
		echo '<div class="desc">Click Thumbnails to View <span class="desc_right">Click Large Image To See Full</span></div>';
		echo '<div class="thumbs">';
			$this->showThumbs();
		echo '</div>';
		echo '</div>';
	}
	
	function showThumbs () {
		$count			 = 0;
		$gallery		 =  rsgInstance::getGallery();
		$this->gallery	 = $gallery;
		
		foreach ($gallery->items() as $item) {
			$thumb = $item->thumb();
			?>
			<a href="javascript: select_thumb('<?php echo $item->get('id'); ?>', '<?php echo $item->get('id'); ?>');"><img src="<?php echo $thumb->url(); ?>" alt="" id="thumb-id-<?php echo $item->get('id'); ?>" <?php if(!$count){?>class="selected"<?php } ?> /></a>
			<?php
			$count++;
		}
	}
	
	function showImages () {
		$count 			= 0;
		$gallery 		=  rsgInstance::getGallery();
		$this->gallery	= $gallery;
		
		foreach ($gallery->items() as $item) {
			$display = $item->display();
			
			$this->RSGallery2_template_photoBox($item->title, $item->name, $display->url(), $item->descr, $item->get("id"), $count, $item->original);
			$count++;
		}
	}
	
	function RSGallery2_template_photoBox($title, $name, $image, $descr, $id, $count, $original) {
		?>
		<div id="photo-id-<?php echo $id?>" class="large<?php if(!$count){?>selected<?php } ?>">
			<a href="<?php echo $original->url(); ?>" rel="lightbox" title="<?php echo $title; echo $descr;?>">
				<img src="<?php echo $image?>" alt="<?php echo $title ?>" border="0" /><br />
			</a>
			<p><?php echo $desc ?></p>
		</div>
		<?
	}
}
?>

